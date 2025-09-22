# WordPress Business Template - Local Development Guide

This guide will help you set up and develop the WordPress Business Template locally using various development environments.

## 🚀 Quick Start Options

Choose your preferred development environment:

1. **[Docker (Recommended)](#docker-development)** - Consistent environment across all systems
2. **[XAMPP](#xampp-development)** - Popular Windows/Mac/Linux solution
3. **[MAMP](#mamp-development)** - Mac and Windows local server
4. **[Local by Flywheel](#local-by-flywheel)** - Modern WordPress development tool

## 🐳 Docker Development (Recommended)

Docker provides a consistent development environment that matches production servers.

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed
- [Node.js 16+](https://nodejs.org/) installed
- [Git](https://git-scm.com/) installed

### Setup Steps

1. **Clone and Navigate**
   ```bash
   git clone <repository-url> wordpress-business-template
   cd wordpress-business-template
   ```

2. **Configure Environment**
   ```bash
   # Copy environment file
   cp .env.example .env

   # Edit .env with your settings (optional for Docker)
   # Default Docker settings work out of the box
   ```

3. **Start Docker Services**
   ```bash
   # Start all services
   docker-compose up -d

   # Check service status
   docker-compose ps
   ```

4. **Install Node Dependencies**
   ```bash
   # Install development tools
   npm install

   # Start asset watching
   npm run dev
   ```

5. **Access Your Site**
   - **WordPress**: http://localhost:8000
   - **phpMyAdmin**: http://localhost:8080
   - **MailHog** (email testing): http://localhost:8025

6. **Complete WordPress Setup**
   - Visit http://localhost:8000
   - Follow WordPress installation wizard
   - Use these database settings (if prompted):
     - **Database**: wordpress_db
     - **Username**: wordpress
     - **Password**: wordpress_password
     - **Host**: db

### Docker Development Commands

```bash
# Start services
npm run docker:up
# or
docker-compose up -d

# Stop services
npm run docker:down
# or
docker-compose down

# View logs
npm run docker:logs
# or
docker-compose logs -f wordpress

# Access WordPress container shell
npm run docker:shell
# or
docker-compose exec wordpress bash

# Access database
npm run docker:db
# or
docker-compose exec db mysql -u wordpress -p

# Restart specific service
docker-compose restart wordpress
```

### Docker File Structure
```
wordpress-business-template/
├── docker-compose.yml          # Docker services configuration
├── docker/                     # Docker configuration files
│   ├── php/php.ini             # PHP settings
│   └── mysql/my.cnf            # MySQL settings
├── .env                        # Environment variables
└── wp-config-docker.php        # Docker-optimized wp-config
```

## 💻 XAMPP Development

XAMPP is a popular local development environment for Windows, Mac, and Linux.

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed
- [Node.js 16+](https://nodejs.org/) installed

### Setup Steps

1. **Start XAMPP Services**
   - Start Apache and MySQL in XAMPP Control Panel

2. **Clone to htdocs**
   ```bash
   # Navigate to XAMPP htdocs folder
   cd /Applications/XAMPP/htdocs  # Mac
   cd C:\xampp\htdocs             # Windows
   cd /opt/lampp/htdocs           # Linux

   # Clone the repository
   git clone <repository-url> master-wp-blog
   ```

3. **Create Database**
   - Visit http://localhost/phpmyadmin
   - Create database: `master_wp_blog_local`

4. **Configure WordPress**
   ```bash
   # Copy local configuration
   cp wp-config-local.php wp-config.php

   # Edit wp-config.php with XAMPP settings:
   # DB_NAME: master_wp_blog_local
   # DB_USER: root
   # DB_PASSWORD: (leave empty)
   # DB_HOST: localhost
   ```

5. **Update URLs in wp-config.php**
   ```php
   define( 'WP_HOME', 'http://localhost/master-wp-blog' );
   define( 'WP_SITEURL', 'http://localhost/master-wp-blog' );
   ```

6. **Install Dependencies and Start Development**
   ```bash
   npm install
   npm run dev
   ```

7. **Access Your Site**
   - Visit: http://localhost/master-wp-blog

## 🍎 MAMP Development

MAMP provides an easy local server environment for Mac and Windows.

### Prerequisites
- [MAMP](https://www.mamp.info/) installed
- [Node.js 16+](https://nodejs.org/) installed

### Setup Steps

1. **Start MAMP**
   - Launch MAMP and start servers
   - Note the port numbers (usually 8888 for Apache, 8889 for MySQL)

2. **Clone to htdocs**
   ```bash
   # Navigate to MAMP htdocs
   cd /Applications/MAMP/htdocs  # Mac
   cd C:\MAMP\htdocs             # Windows

   # Clone repository
   git clone <repository-url> master-wp-blog
   ```

3. **Create Database**
   - Visit http://localhost:8888/phpMyAdmin
   - Create database: `master_wp_blog_local`

4. **Configure WordPress**
   ```bash
   # Copy and edit configuration
   cp wp-config-local.php wp-config.php

   # Update settings in wp-config.php:
   # DB_NAME: master_wp_blog_local
   # DB_USER: root
   # DB_PASSWORD: root
   # DB_HOST: localhost:8889
   ```

5. **Update URLs**
   ```php
   define( 'WP_HOME', 'http://localhost:8888/master-wp-blog' );
   define( 'WP_SITEURL', 'http://localhost:8888/master-wp-blog' );
   ```

6. **Install Dependencies**
   ```bash
   npm install
   npm run dev
   ```

## 🌟 Local by Flywheel

Local by Flywheel is a modern WordPress development tool with advanced features.

### Prerequisites
- [Local by Flywheel](https://localwp.com/) installed
- [Node.js 16+](https://nodejs.org/) installed

### Setup Steps

1. **Create New Site in Local**
   - Open Local by Flywheel
   - Click "Create a new site"
   - Choose site name: `master-wp-blog`
   - Select environment (Preferred or Custom)

2. **Replace Site Files**
   ```bash
   # Navigate to Local site folder
   cd ~/Local\ Sites/master-wp-blog/app/public  # Mac
   cd C:\Users\[username]\Local Sites\master-wp-blog\app\public  # Windows

   # Remove default WordPress files
   rm -rf *

   # Clone template files
   git clone <repository-url> .
   ```

3. **Configure WordPress**
   ```bash
   # Copy local configuration
   cp wp-config-local.php wp-config.php

   # Update with Local's database settings (check Local's database tab)
   ```

4. **Update URLs**
   - Use the local URL provided by Local (e.g., https://master-wp-blog.local)

5. **Install Dependencies**
   ```bash
   npm install
   npm run dev
   ```

## 🛠️ Development Workflow

### Asset Building

The template includes a modern build system using Webpack and Sass:

```bash
# Development (watch for changes)
npm run dev
npm run watch

# Production build
npm run build

# CSS only
npm run build:css
npm run watch:css

# JavaScript only
npm run build:js
npm run watch:js
```

### Code Quality

```bash
# Lint code
npm run lint
npm run lint:css
npm run lint:js

# Fix linting issues
npm run fix
npm run fix:css
npm run fix:js
```

### Testing

```bash
# Run tests (when implemented)
npm test

# Security audit
npm run security:check
```

## 📁 Development File Structure

```
wp-content/themes/astra-child/
├── assets/
│   ├── src/                    # Source files (edit these)
│   │   ├── js/
│   │   │   ├── business-main.js
│   │   │   ├── business-admin.js
│   │   │   ├── modules/
│   │   │   └── pages/
│   │   ├── scss/
│   │   │   ├── main.scss
│   │   │   └── components/
│   │   └── images/
│   ├── dist/                   # Compiled files (don't edit)
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── css/                    # Legacy CSS files
│   └── js/                     # Legacy JS files
├── style.css                   # Main theme stylesheet
├── functions.php               # Theme functions
├── page-*.php                  # Custom page templates
└── README.txt                  # Theme documentation
```

## 🔧 Configuration Files

### Environment Variables (.env)
```bash
# Copy example and customize
cp .env.example .env

# Key variables to update:
WP_HOME=http://localhost:8000
WP_SITEURL=http://localhost:8000
DB_NAME=your_database_name
DB_USER=your_username
DB_PASSWORD=your_password
```

### WordPress Configuration
- **Docker**: Use `wp-config-docker.php`
- **XAMPP/MAMP/Local**: Use `wp-config-local.php`

### Package.json Scripts
- `npm run dev` - Start development with asset watching
- `npm run build` - Build production assets
- `npm run docker:up` - Start Docker services
- `npm run docker:down` - Stop Docker services

## 🐛 Troubleshooting

### Common Issues

#### Port Conflicts
```bash
# If port 8000 is in use, update docker-compose.yml:
ports:
  - "8001:80"  # Change 8000 to 8001

# Or kill process using port:
# Mac/Linux:
lsof -ti:8000 | xargs kill -9

# Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F
```

#### Permission Issues
```bash
# Fix file permissions (Mac/Linux):
chmod -R 755 wp-content/
chmod -R 644 wp-content/themes/astra-child/*.php

# Docker permission issues:
docker-compose exec wordpress chown -R www-data:www-data /var/www/html
```

#### Database Connection Errors
```bash
# Check database settings in wp-config.php
# Verify database exists
# For Docker: Use 'db' as host, not 'localhost'
```

#### Asset Building Issues
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Check Node.js version (requires 16+)
node --version
```

### Getting Help

1. **Check Logs**
   ```bash
   # Docker logs
   docker-compose logs wordpress

   # WordPress debug log
   tail -f wp-content/debug.log

   # Development console
   # Open browser dev tools and check console
   ```

2. **Reset Environment**
   ```bash
   # Docker reset
   docker-compose down -v
   docker-compose up -d

   # Database reset (will lose data!)
   # Recreate database and run WordPress installer again
   ```

## 🚀 Deployment from Local

### Staging Deployment
```bash
# Build production assets
npm run build

# Deploy to staging (configure in package.json)
npm run deploy:staging
```

### Production Deployment
```bash
# Final production build
npm run build

# Deploy to production
npm run deploy:production
```

## 🔄 Sync with Production

### Download Production Database
```bash
# Export from production (via cPanel/hPanel)
# Import to local development database

# Update URLs for local development
# Use Search Replace plugin or WP-CLI
```

### Upload Local Changes
```bash
# Build production assets
npm run build

# Upload via FTP/SFTP:
# - wp-content/themes/astra-child/
# - Any custom plugins
# - wp-config.php (update for production)
```

## 📚 Additional Resources

### Documentation
- [WordPress Developer Handbook](https://developer.wordpress.org/)
- [Astra Theme Documentation](https://wpastra.com/docs/)
- [Docker Documentation](https://docs.docker.com/)

### Tools
- [WP-CLI](https://wp-cli.org/) - WordPress command line tool
- [Query Monitor](https://wordpress.org/plugins/query-monitor/) - Debugging plugin
- [Debug Bar](https://wordpress.org/plugins/debug-bar/) - Development plugin

### VS Code Extensions
- PHP Intelephense
- WordPress Snippets
- Docker
- ESLint
- Prettier

---

## ✅ Development Checklist

### Initial Setup
- [ ] Development environment running
- [ ] Database created and connected
- [ ] WordPress installed
- [ ] Astra parent theme activated
- [ ] Astra Business Child theme activated
- [ ] Node.js dependencies installed
- [ ] Asset building working

### Development Workflow
- [ ] Code linting configured
- [ ] Git repository initialized
- [ ] Development tools working
- [ ] Browser dev tools setup
- [ ] Local testing completed

### Pre-Deployment
- [ ] Production assets built
- [ ] Code linted and tested
- [ ] Database changes documented
- [ ] Security review completed
- [ ] Performance testing done

---

**Happy developing!** 🎉

For questions or issues, check the [troubleshooting section](#troubleshooting) or refer to the main project documentation.