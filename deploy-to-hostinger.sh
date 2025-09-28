#!/bin/bash

# Automated WordPress Deployment to Hostinger
# Usage: ./deploy-to-hostinger.sh "yourdomain.com" "db_name" "db_user" "db_pass"

set -e

if [ $# -ne 4 ]; then
    echo "Usage: $0 <domain> <db_name> <db_user> <db_password>"
    echo "Example: $0 mysite.com u123456_wp u123456_wpuser mypassword"
    exit 1
fi

# Clean domain name for filename and URL replacement
DOMAIN_CLEAN=$(echo "$1" | sed 's|https\?://||' | sed 's|/$||')
DOMAIN_PROTOCOL=$(echo "$1" | grep -o '^https\?://')
DOMAIN_CLEAN_URL="${DOMAIN_PROTOCOL}${DOMAIN_CLEAN}"
DOMAIN=$1
DB_NAME=$2
DB_USER=$3
DB_PASS=$4
DEPLOY_DIR="deploy_$(date +%Y%m%d_%H%M%S)"

echo "🚀 Starting automated WordPress deployment..."

# 1. Export database and fix URLs
echo "📊 Exporting database..."
docker exec wp_simple_db mysqldump --single-transaction --routines --triggers -u wordpress -pwordpress_password wordpress_db > database_export.sql

# Replace localhost URLs in database dump
echo "🔗 Fixing URLs in database..."
sed -i "s|http://localhost:8000|$DOMAIN_CLEAN_URL|g" database_export.sql
sed -i "s|localhost:8000|$DOMAIN_CLEAN|g" database_export.sql

# 2. Create clean wp-config.php for production
echo "⚙️  Creating production wp-config.php..."
cat > wp-config-production.php << 'EOF'
<?php
// ** Database settings ** //
define('DB_NAME', '$DB_NAME');
define('DB_USER', '$DB_USER');
define('DB_PASSWORD', '$DB_PASS');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// ** Security keys - Generate at https://api.wordpress.org/secret-key/1.1/salt/ ** //
define('AUTH_KEY', 'put your unique phrase here');
define('SECURE_AUTH_KEY', 'put your unique phrase here');
define('LOGGED_IN_KEY', 'put your unique phrase here');
define('NONCE_KEY', 'put your unique phrase here');
define('AUTH_SALT', 'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT', 'put your unique phrase here');
define('NONCE_SALT', 'put your unique phrase here');

$table_prefix = 'wp_';

define('WP_DEBUG', false);

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
EOF

# Fix the placeholder values
sed -i "s/\$DB_NAME/$DB_NAME/g" wp-config-production.php
sed -i "s/\$DB_USER/$DB_USER/g" wp-config-production.php
sed -i "s/\$DB_PASS/$DB_PASS/g" wp-config-production.php

# 3. Create deployment package
echo "📦 Creating deployment package..."
mkdir -p "$DEPLOY_DIR"

# Copy all WordPress files except development stuff
rsync -av --progress \
    --exclude=mysql-data \
    --exclude=.git \
    --exclude=.env* \
    --exclude=docker-compose.yml \
    --exclude=test-* \
    --exclude=setup-* \
    --exclude=enable-* \
    --exclude=deploy_* \
    . "$DEPLOY_DIR/"

# Replace wp-config.php with production version
cp wp-config-production.php "$DEPLOY_DIR/wp-config.php"

# Copy database export
cp database_export.sql "$DEPLOY_DIR/"

# Create ZIP for upload
echo "🗜️  Creating deployment ZIP..."
cd "$DEPLOY_DIR"
zip -r "../${DOMAIN_CLEAN}_deployment.zip" .
cd ..

# 4. Create deployment instructions
cat > "${DOMAIN_CLEAN}_DEPLOY_INSTRUCTIONS.txt" << EOF
🚀 HOSTINGER DEPLOYMENT INSTRUCTIONS FOR $DOMAIN

STEP 1: Upload Files
- Go to Hostinger File Manager
- Navigate to public_html (or your domain folder)
- Upload ${DOMAIN_CLEAN}_deployment.zip
- Extract the ZIP file
- Delete the ZIP file

STEP 2: Import Database
- Go to Hostinger phpMyAdmin
- Select database: $DB_NAME
- Click "Import" tab
- Choose file: database_export.sql
- Click "Go"

STEP 3: Update URLs (if needed)
- In phpMyAdmin, go to wp_options table
- Find rows: 'home' and 'siteurl'
- Update both to: $DOMAIN

DONE! Your site should be live at $DOMAIN

Database: $DB_NAME
User: $DB_USER
Password: $DB_PASS
EOF

echo "✅ Deployment package created!"
echo "📁 Files created:"
echo "   - ${DOMAIN_CLEAN}_deployment.zip (WordPress files)"
echo "   - ${DOMAIN_CLEAN}_DEPLOY_INSTRUCTIONS.txt (Step-by-step guide)"
echo ""
echo "🕐 Total deployment time: < 5 minutes"
echo ""
echo "Next: Upload the ZIP to Hostinger and follow the instructions!"

# Cleanup
rm -rf "$DEPLOY_DIR" wp-config-production.php database_export.sql