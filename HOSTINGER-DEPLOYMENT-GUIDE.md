# WordPress Business Template - Hostinger Deployment Guide

This comprehensive guide will help you deploy your WordPress Business Template to Hostinger hosting with optimal performance and security settings.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Pre-Deployment Checklist](#pre-deployment-checklist)
3. [Database Setup](#database-setup)
4. [File Upload Methods](#file-upload-methods)
5. [Configuration Steps](#configuration-steps)
6. [Domain Setup](#domain-setup)
7. [SSL Certificate](#ssl-certificate)
8. [Performance Optimization](#performance-optimization)
9. [Security Configuration](#security-configuration)
10. [Testing and Validation](#testing-and-validation)
11. [Post-Deployment Tasks](#post-deployment-tasks)
12. [Troubleshooting](#troubleshooting)
13. [Maintenance](#maintenance)

## Prerequisites

- Active Hostinger hosting account (Business or Premium plan recommended)
- Domain name (either purchased through Hostinger or pointed to Hostinger nameservers)
- FTP client (FileZilla, WinSCP) or access to Hostinger File Manager
- This WordPress Business Template files

## Pre-Deployment Checklist

### ✅ Hostinger Account Setup
- [ ] Hosting plan is active
- [ ] Domain is connected to your hosting account
- [ ] Access to hPanel (Hostinger control panel)
- [ ] Access to File Manager or FTP credentials

### ✅ Local Files Preparation
- [ ] All WordPress core files are present
- [ ] Custom wp-config.php is configured
- [ ] Astra parent theme is included
- [ ] Astra Business Child theme is ready
- [ ] .htaccess file is properly configured
- [ ] All file permissions are set correctly

## Database Setup

### Step 1: Create MySQL Database

1. **Log into hPanel**
   - Go to your Hostinger account
   - Access hPanel dashboard

2. **Navigate to Databases**
   - Click on "Databases" in the sidebar
   - Select "MySQL Databases"

3. **Create New Database**
   - Click "Create database"
   - Enter database name (e.g., `your_site_wp`)
   - Create database username and password
   - **Important**: Save these credentials securely

4. **Database Information to Note**
   ```
   Database Name: your_site_wp
   Database Username: your_username
   Database Password: your_secure_password
   Database Host: localhost (usually)
   ```

### Step 2: Database Security
- Use a strong, unique password
- Limit database user privileges to only necessary permissions
- Note the database prefix (default: wp_)

## File Upload Methods

Choose one of these methods to upload your WordPress files:

### Method 1: Hostinger File Manager (Recommended for Beginners)

1. **Access File Manager**
   - In hPanel, go to "Files" → "File Manager"
   - Navigate to public_html directory

2. **Upload Files**
   - If uploading a ZIP file:
     - Upload the WordPress ZIP file
     - Right-click and extract
   - If uploading individual files:
     - Upload all WordPress files to public_html
     - Ensure all folders maintain their structure

3. **File Structure Verification**
   ```
   public_html/
   ├── index.php
   ├── wp-config.php
   ├── wp-admin/
   ├── wp-content/
   │   ├── themes/
   │   │   ├── astra/
   │   │   └── astra-child/
   │   └── plugins/
   ├── wp-includes/
   └── .htaccess
   ```

### Method 2: FTP Upload (Recommended for Large Sites)

1. **Get FTP Credentials**
   - In hPanel: "Files" → "FTP Accounts"
   - Create FTP account or use main account

2. **FTP Client Setup**
   - Host: your-domain.com or ftp.your-domain.com
   - Port: 21
   - Username: your FTP username
   - Password: your FTP password

3. **Upload Files**
   - Connect via FTP client
   - Navigate to public_html directory
   - Upload all WordPress files maintaining directory structure

## Configuration Steps

### Step 1: Configure wp-config.php

1. **Locate wp-config.php**
   - Open wp-config.php in File Manager or via FTP

2. **Update Database Credentials**
   ```php
   define( 'DB_NAME', 'your_database_name' );
   define( 'DB_USER', 'your_database_user' );
   define( 'DB_PASSWORD', 'your_database_password' );
   define( 'DB_HOST', 'localhost' );
   ```

3. **Generate Security Keys**
   - Visit: https://api.wordpress.org/secret-key/1.1/salt/
   - Replace the placeholder keys in wp-config.php

4. **Hostinger-Specific Settings** (already included in template)
   ```php
   define( 'WP_MEMORY_LIMIT', '512M' );
   define( 'FS_CHMOD_DIR', (0755 & ~ umask()) );
   define( 'FS_CHMOD_FILE', (0644 & ~ umask()) );
   ```

### Step 2: File Permissions

Set correct permissions via File Manager or FTP:

```
Folders: 755
Files: 644
wp-config.php: 600 (for security)
```

**Using hPanel File Manager:**
1. Select files/folders
2. Right-click → "Change Permissions"
3. Set appropriate permissions

### Step 3: .htaccess Configuration

The included .htaccess file is optimized for Hostinger's LiteSpeed servers. If you need to modify:

1. **Access .htaccess** (may be hidden - enable "Show Hidden Files")
2. **Verify LiteSpeed Optimizations** are present
3. **Enable HTTPS redirect** (uncomment the SSL section when ready)

## Domain Setup

### Step 1: Domain Connection

If domain is purchased elsewhere:
1. **Update Nameservers** to Hostinger's:
   - ns1.dns-parking.com
   - ns2.dns-parking.com

If domain purchased through Hostinger:
- Domain should automatically connect

### Step 2: WordPress URL Configuration

1. **Visit Your Domain** (after DNS propagation)
2. **Run WordPress Installation**
   - Enter site title and admin credentials
   - Or import database if moving existing site

3. **Update WordPress URLs** (if necessary):
   ```php
   define( 'WP_HOME', 'https://your-domain.com' );
   define( 'WP_SITEURL', 'https://your-domain.com' );
   ```

## SSL Certificate

### Step 1: Enable SSL in hPanel

1. **Navigate to SSL**
   - hPanel → "Security" → "SSL"

2. **Install SSL Certificate**
   - Select "Free Let's Encrypt SSL"
   - Click "Install"
   - Wait for installation (usually 5-15 minutes)

### Step 2: Force HTTPS

1. **Enable HTTPS Redirect**
   - In .htaccess, uncomment the HTTPS redirect section:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteCond %{HTTPS} off
       RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   </IfModule>
   ```

2. **Update WordPress Settings**
   - WordPress Admin → Settings → General
   - Change both URLs to https://

## Performance Optimization

### Step 1: Enable LiteSpeed Cache

1. **Install LiteSpeed Cache Plugin**
   - WordPress Admin → Plugins → Add New
   - Search "LiteSpeed Cache"
   - Install and activate

2. **Basic Configuration**
   - LiteSpeed Cache → General Settings
   - Enable basic caching options

### Step 2: Hostinger Performance Features

1. **Enable Object Cache**
   - hPanel → "Performance" → "Object Cache"
   - Click "Enable"

2. **PHP Version**
   - Ensure PHP 8.0 or higher is active
   - hPanel → "Advanced" → "PHP Configuration"

3. **CDN Setup** (Optional)
   - Consider Hostinger's CDN or Cloudflare

### Step 3: Image Optimization

1. **Install Optimization Plugin**
   - Consider: Smush, ShortPixel, or Optimole
   - Compress existing images
   - Enable automatic optimization

## Security Configuration

### Step 1: Hostinger Security Features

1. **Enable Malware Scanner**
   - hPanel → "Security" → "Malware Scanner"
   - Schedule regular scans

2. **Backups**
   - hPanel → "Files" → "Backups"
   - Enable automatic backups
   - Test backup restoration process

### Step 2: WordPress Security

1. **Change Default Admin Username**
   - Never use "admin" as username

2. **Strong Passwords**
   - Use complex passwords for all accounts

3. **Security Plugin** (Optional)
   - Consider: Wordfence, Sucuri, or iThemes Security

4. **Disable File Editing** (already configured)
   ```php
   define( 'DISALLOW_FILE_EDIT', true );
   ```

### Step 3: Hide WordPress Version

The template already includes this, but verify:
```php
remove_action('wp_head', 'wp_generator');
```

## Testing and Validation

### Step 1: Basic Functionality Test

- [ ] Site loads correctly on HTTP and HTTPS
- [ ] Admin area is accessible
- [ ] Themes are working properly
- [ ] Child theme is active
- [ ] Contact forms work (test email delivery)
- [ ] Business information displays correctly

### Step 2: Performance Testing

1. **Page Speed Testing**
   - Use Google PageSpeed Insights
   - Test both mobile and desktop
   - Target score: 80+ (90+ preferred)

2. **Load Testing**
   - Use tools like GTmetrix or Pingdom
   - Optimize based on results

### Step 3: Security Testing

1. **SSL Certificate Verification**
   - Use SSL Labs SSL Test
   - Ensure A+ rating

2. **Security Headers**
   - Test with securityheaders.com
   - Verify proper implementation

### Step 4: Mobile Responsiveness

1. **Mobile Testing**
   - Test on various devices
   - Use browser developer tools
   - Verify responsive design works

## Post-Deployment Tasks

### Step 1: WordPress Configuration

1. **Activate Child Theme**
   - Appearance → Themes → Activate Astra Business Child

2. **Configure Business Information**
   - Appearance → Customize → Business Information
   - Add phone, email, address, hours

3. **Create Essential Pages**
   - Create pages using provided templates:
     - About (use "Business About Page" template)
     - Services (use "Business Services Page" template)
     - Contact (use "Business Contact Page" template)

4. **Set Up Navigation**
   - Appearance → Menus
   - Create main menu with essential pages

### Step 2: Content Setup

1. **Replace Placeholder Content**
   - Update all placeholder text
   - Add real business information
   - Upload actual images

2. **SEO Optimization**
   - Install SEO plugin (Yoast or RankMath)
   - Configure basic SEO settings
   - Set up Google Analytics

### Step 3: Backup and Monitoring

1. **Set Up Monitoring**
   - Configure uptime monitoring
   - Set up Google Search Console
   - Monitor site performance

2. **Documentation**
   - Document custom changes
   - Keep record of plugin configurations
   - Note any custom CSS or functionality

## Troubleshooting

### Common Issues and Solutions

#### 1. Database Connection Error
```
Error: "Error establishing a database connection"
Solution:
- Verify database credentials in wp-config.php
- Ensure database exists in hPanel
- Check database user permissions
```

#### 2. Permission Issues
```
Error: "Unable to write to files" or "Permission denied"
Solution:
- Set correct file permissions (644 for files, 755 for folders)
- Use hPanel → Advanced → Fix File Ownership
```

#### 3. SSL Issues
```
Error: Mixed content warnings or SSL not working
Solution:
- Ensure .htaccess redirect is enabled
- Update WordPress URLs to HTTPS
- Check for hardcoded HTTP links
```

#### 4. Plugin/Theme Errors
```
Error: White screen or plugin conflicts
Solution:
- Deactivate all plugins
- Switch to default theme temporarily
- Activate items one by one to identify issue
```

#### 5. Performance Issues
```
Issue: Slow loading times
Solution:
- Enable caching (LiteSpeed Cache)
- Optimize images
- Enable GZIP compression
- Minimize plugins
```

### Getting Help

1. **Hostinger Support**
   - Live chat available 24/7
   - Submit ticket through hPanel
   - Check Hostinger knowledge base

2. **WordPress Community**
   - WordPress.org support forums
   - Theme-specific support

3. **Documentation**
   - This deployment guide
   - Child theme README.txt
   - WordPress Codex

## Maintenance

### Regular Tasks

#### Weekly
- [ ] Update plugins and themes
- [ ] Check for WordPress core updates
- [ ] Review security scan results
- [ ] Monitor site performance

#### Monthly
- [ ] Full site backup (manual)
- [ ] Review analytics data
- [ ] Check for broken links
- [ ] Update content as needed

#### Quarterly
- [ ] Security audit
- [ ] Performance optimization review
- [ ] Content strategy review
- [ ] SEO analysis

### Backup Strategy

1. **Automated Backups**
   - Enable Hostinger automatic backups
   - Consider additional backup plugin
   - Store backups off-site (cloud storage)

2. **Backup Testing**
   - Regularly test backup restoration
   - Verify backup integrity
   - Document restoration process

### Update Process

1. **Staging Environment** (Recommended)
   - Test updates on staging site first
   - Verify compatibility
   - Deploy to production

2. **Update Order**
   1. WordPress core
   2. Parent theme (Astra)
   3. Child theme (if needed)
   4. Plugins
   5. Test functionality

### Performance Monitoring

1. **Key Metrics**
   - Page load speed
   - Server response time
   - Error rates
   - Uptime percentage

2. **Tools**
   - Google Analytics
   - Search Console
   - Hostinger built-in analytics
   - Third-party monitoring tools

---

## Support and Resources

### Documentation
- [WordPress Codex](https://codex.wordpress.org/)
- [Astra Theme Documentation](https://wpastra.com/docs/)
- [Hostinger Knowledge Base](https://support.hostinger.com/)

### Contact Information
- **Template Support**: [Your Support Contact]
- **Hostinger Support**: Live chat via hPanel
- **Emergency Contact**: [Emergency Contact Information]

---

**Last Updated**: [Current Date]
**Version**: 1.0.0

---

> **Note**: This guide is specifically tailored for Hostinger hosting. Some steps may vary for other hosting providers. Always refer to Hostinger's official documentation for the most current information.

> **Important**: Always backup your site before making any changes. Test changes on a staging environment when possible.