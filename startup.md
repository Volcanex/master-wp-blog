# WordPress MCP Blog - Startup Guide

This guide covers setting up the WordPress blog with MCP (Model Context Protocol) integration on macOS.

## System Requirements

- macOS (Apple Silicon or Intel)
- Docker Desktop
- Node.js (optional, for asset building)

## Quick Start

### 1. Start WordPress

```bash
sudo ./test-startup.sh
```

The startup script will:
- Check prerequisites (Docker, Node.js)
- Set up environment variables
- Start WordPress, MySQL, and phpMyAdmin containers
- Wait for services to initialize

### 2. Access Your Site

Once startup completes:

- **WordPress Site**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Username: `wordpress`
  - Password: `wordpress_password`

### 3. WordPress MCP Integration

The WordPress MCP plugin enables AI integration through the Model Context Protocol.

#### Activate MCP Plugin (First Time Setup)

```bash
docker-compose exec -T wordpress php setup-mcp-complete.php
```

This script will:
- Activate the WordPress MCP plugin
- Create `mcpuser` with administrator role
- Generate an application password
- Enable all MCP tools (posts, pages, users, media, settings, etc.)
- Configure JWT authentication

#### Add MCP Server to Claude Code

```bash
claude mcp add --transport stdio wordpress-mcp \
  -e WP_API_URL=http://localhost:8000/ \
  -e WP_API_USERNAME=mcpuser \
  -e WP_API_PASSWORD=pDukIJx2mYL7NCSBHEd3NtrR \
  -- npx -y @automattic/mcp-wordpress-remote@latest
```

#### Verify MCP Connection

```bash
claude mcp list
```

You should see:
```
wordpress-mcp: npx -y @automattic/mcp-wordpress-remote@latest - ✓ Connected
```

## macOS Compatibility Notes

### Cross-Platform MySQL Compatibility

The startup script (`test-startup.sh`) now detects macOS vs Linux and uses the appropriate `stat` command for checking MySQL data directory permissions:

- **macOS**: Uses `stat -f %u` (BSD-style)
- **Linux**: Uses `stat -c %u` (GNU-style)

### Platform Warning (Safe to Ignore)

When starting containers, you may see:
```
The requested image's platform (linux/amd64) does not match the detected host platform (linux/arm64/v8)
```

This is expected for the phpMyAdmin image on Apple Silicon. Docker will run it through Rosetta 2 emulation automatically.

### MySQL Data Migration from Ubuntu

If you cloned this repo from a Ubuntu system, the MySQL data will be incompatible with macOS (different filesystem case sensitivity). The fix:

```bash
# Stop containers
docker-compose down

# Remove incompatible MySQL data
rm -rf mysql-data

# Restart - MySQL will create fresh data
docker-compose up -d
```

## MCP Credentials

### Application Password Authentication
- **Username**: `mcpuser`
- **Password**: `pDukIJx2mYL7NCSBHEd3NtrR`

### Generate New JWT Token

If you prefer JWT authentication over application passwords:

```bash
docker-compose exec -T wordpress php generate-token-simple.php
```

This will output a token valid for 24 hours that you can use with HTTP transport.

## Available MCP Tools

Once connected, Claude Code can interact with your WordPress site using these tool categories:

- **Posts Tools** - Create, read, update, delete blog posts
- **Pages Tools** - Manage WordPress pages
- **Users Tools** - User management and profiles
- **Media Tools** - Upload and manage media files
- **Settings Tools** - Configure site settings
- **Custom Post Types Tools** - Work with custom post types
- **WooCommerce Tools** - Products and orders (if WooCommerce is installed)

## Useful Commands

### Start/Stop Services

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# Restart services
docker-compose restart

# View logs
docker-compose logs -f wordpress
docker-compose logs -f db
```

### Database Management

Access phpMyAdmin at http://localhost:8080 or use command line:

```bash
# Access MySQL CLI
docker-compose exec db mysql -u wordpress -pwordpress_password wordpress_db
```

### Flush WordPress Rewrite Rules

If endpoints aren't accessible:

```bash
docker-compose exec -T wordpress php fix-permalinks.php
```

## Troubleshooting

### MySQL Won't Start

**Error**: `Different lower_case_table_names settings`

**Solution**: Delete incompatible MySQL data and restart:
```bash
docker-compose down
rm -rf mysql-data
docker-compose up -d
```

### REST API Returns 404

**Solution**: Ensure `.htaccess` has proper rewrite rules and flush permalinks:
```bash
docker-compose exec -T wordpress php fix-permalinks.php
```

The `.htaccess` should contain:
```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
```

### MCP Endpoint Returns 401

This is expected - the endpoint requires authentication. Make sure you've:
1. Activated the WordPress MCP plugin
2. Created the mcpuser account
3. Added the server to Claude Code with correct credentials

### MCP Returns 404 "rest_no_route" Error

**Symptoms**: The MCP endpoint returns `{"code":"rest_no_route","message":"No route was found matching the URL and request method"}`

**Root Cause**: The WordPress MCP plugin requires two things to register REST routes:
1. The `wordpress_mcp_settings['enabled']` option must be set to `true`
2. The `mcp-basic-auth-fix.php` plugin must be activated for Application Password authentication

**Fix**:
```bash
# Set the correct settings option
docker-compose exec -T wordpress php -r "require_once 'wp-config.php'; update_option('wordpress_mcp_settings', array('enabled' => true)); echo 'Fixed settings\n';"

# Activate the auth fix plugin
docker-compose exec -T wordpress php -r "require_once 'wp-config.php'; activate_plugin('mcp-basic-auth-fix.php'); echo 'Activated auth plugin\n';"

# Restart WordPress to clear singleton cache
docker-compose restart wordpress
```

**Note**: The `setup-mcp-complete.php` script has been updated to include these fixes automatically.

### Check MCP Status

```bash
docker-compose exec -T wordpress php check-mcp-status.php
```

## Site Information

- **Site Name**: Bodacious
- **WordPress Version**: 6.8.2
- **MCP Plugin Version**: 0.2.5
- **Database**: MySQL 8.0

## Files & Directories

- `test-startup.sh` - Main startup script
- `docker-compose.yml` - Docker services configuration
- `wp-config.php` - WordPress configuration
- `.env.local` - Environment variables
- `mysql-data/` - MySQL database files (excluded from git)
- `wp-content/plugins/wordpress-mcp/` - MCP plugin

## Security Notes

- Default credentials are for local development only
- Never commit `.env.local` or credentials to public repositories
- Change passwords before deploying to production
- JWT tokens expire after 24 hours and should be regenerated as needed
- The `mcpuser` account has full administrator access

## Next Steps

1. Complete WordPress setup at http://localhost:8000
2. Install a theme (Astra Business Child theme is included)
3. Create your first post using Claude Code via MCP
4. Configure site settings and permalinks
5. Add content and customize your site

## Deploying to Friends

To share your site with friends:

1. **Quick Local Share**: Use ngrok or similar tunnel service
2. **Cloud Deployment**: Deploy to DigitalOcean, AWS, or any Docker-compatible host
3. **Export/Import**: Use WordPress export tools to migrate content

Remember to change all default passwords before making your site publicly accessible!
