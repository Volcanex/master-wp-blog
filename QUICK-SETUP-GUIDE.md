# WordPress Business Template - Quick Setup Guide

This is a condensed setup guide for experienced users. For detailed instructions, see [HOSTINGER-DEPLOYMENT-GUIDE.md](HOSTINGER-DEPLOYMENT-GUIDE.md).

## 🚀 Quick Deployment Steps

### 1. Database Setup (2 minutes)
```bash
# In hPanel → Databases → MySQL Databases
1. Create database: your_site_wp
2. Create user with strong password
3. Note credentials
```

### 2. File Upload (5 minutes)
```bash
# Via hPanel File Manager or FTP
1. Upload all files to public_html/
2. Ensure proper directory structure
3. Set permissions: 755 (folders), 644 (files), 600 (wp-config.php)
```

### 3. Configuration (3 minutes)
```php
# Edit wp-config.php
define( 'DB_NAME', 'your_database_name' );
define( 'DB_USER', 'your_database_user' );
define( 'DB_PASSWORD', 'your_database_password' );
define( 'DB_HOST', 'localhost' );

# Generate new security keys from:
# https://api.wordpress.org/secret-key/1.1/salt/
```

### 4. WordPress Installation (3 minutes)
```bash
1. Visit your domain
2. Complete WordPress installation
3. Create admin account
4. Login to WordPress admin
```

### 5. Theme Activation (2 minutes)
```bash
# In WordPress Admin
1. Appearance → Themes
2. Activate "Astra Business Child"
3. Verify parent theme (Astra) is installed
```

### 6. SSL & Security (5 minutes)
```bash
# In hPanel
1. Security → SSL → Install Free SSL
2. Wait 5-15 minutes for activation

# In .htaccess (uncomment HTTPS redirect)
# In WordPress Admin → Settings → General
# Change URLs to https://
```

## ✅ Essential Checklist

### Pre-Launch
- [ ] Database connected successfully
- [ ] Child theme active
- [ ] SSL certificate installed
- [ ] HTTPS redirect enabled
- [ ] Business info configured (Customize → Business Information)
- [ ] Test contact forms
- [ ] Create essential pages (About, Services, Contact)
- [ ] Set up main navigation menu

### Post-Launch
- [ ] Install LiteSpeed Cache plugin
- [ ] Enable Hostinger Object Cache
- [ ] Set up Google Analytics
- [ ] Configure SEO plugin (Yoast/RankMath)
- [ ] Test site on mobile devices
- [ ] Enable automatic backups

## 🔧 Quick Customization

### Business Information
```php
# Appearance → Customize → Business Information
Phone: +1 (555) 123-4567
Email: hello@yourbusiness.com
Address: 123 Business St, City, State 12345
Hours: Mon-Fri 9AM-6PM
```

### Essential Pages
```bash
1. Create "About" page → Select "Business About Page" template
2. Create "Services" page → Select "Business Services Page" template
3. Create "Contact" page → Select "Business Contact Page" template
4. Set front page to static page if desired
```

### Navigation Menu
```bash
# Appearance → Menus
1. Create "Main Menu"
2. Add: Home, About, Services, Contact
3. Assign to "Primary Menu" location
```

## 🎨 Customization Options

### Colors (CSS Variables)
```css
/* In child theme style.css or Customizer */
:root {
    --business-primary: #2563eb;    /* Main brand color */
    --business-secondary: #f59e0b;  /* Accent color */
    --business-dark: #1f2937;       /* Dark text */
    --business-light: #f8fafc;      /* Light backgrounds */
}
```

### Business Contact Shortcodes
```php
[business_contact]                    # All contact info
[business_contact show="phone"]       # Phone only
[business_contact show="email"]       # Email only
[business_contact show="address"]     # Address only
[business_contact show="hours"]       # Hours only
```

## 🚨 Troubleshooting Quick Fixes

### Database Connection Error
```bash
1. Verify wp-config.php database credentials
2. Check database exists in hPanel
3. Ensure database user has permissions
```

### Plugin/Theme Errors
```bash
1. Deactivate all plugins via FTP (rename plugins folder)
2. Switch to default theme
3. Reactivate one by one to find conflict
```

### SSL Issues
```bash
1. Wait 15 minutes after SSL installation
2. Clear browser cache
3. Check .htaccess HTTPS redirect
4. Update WordPress URLs to HTTPS
```

### Performance Issues
```bash
1. Install LiteSpeed Cache plugin
2. Enable Object Cache in hPanel
3. Optimize images (install Smush plugin)
4. Minimize plugins
```

## 📞 Support Resources

- **Hostinger Support**: Live chat via hPanel (24/7)
- **WordPress Support**: [WordPress.org forums](https://wordpress.org/support/)
- **Astra Documentation**: [WPAstra.com/docs](https://wpastra.com/docs/)
- **Template Files**: All custom templates in `/wp-content/themes/astra-child/`

## 📋 File Structure Reference

```
public_html/
├── wp-config.php                 # Database & settings
├── .htaccess                     # LiteSpeed optimized
├── wp-content/
│   └── themes/
│       ├── astra/                # Parent theme
│       └── astra-child/          # Your custom theme
│           ├── style.css         # Business styles
│           ├── functions.php     # Business features
│           ├── page-about.php    # About page template
│           ├── page-services.php # Services page template
│           ├── page-contact.php  # Contact page template
│           └── assets/           # JS, CSS, images
└── [WordPress core files]
```

---

**Need help?** Check the detailed [Hostinger Deployment Guide](HOSTINGER-DEPLOYMENT-GUIDE.md) or contact support.

**Estimated Total Setup Time**: 20-30 minutes

---

> **Quick Tip**: Save this checklist and use it every time you deploy a new site with this template!