@echo off
REM WordPress Business Template - Test Startup Script (Windows)
REM ============================================================
REM This script tests the Docker setup with the master template
REM It will use .env.example if no .env exists and start all services

setlocal enabledelayedexpansion

REM Colors for output (Windows)
set "RED=[91m"
set "GREEN=[92m"
set "YELLOW=[93m"
set "BLUE=[94m"
set "NC=[0m"

echo.
echo ==========================================
echo   WordPress Business Template Test
echo ==========================================
echo.

echo %BLUE%[INFO]%NC% Starting WordPress Business Template test setup...
echo.

REM Check Docker
echo %BLUE%[INFO]%NC% Checking prerequisites...
docker --version >nul 2>&1
if errorlevel 1 (
    echo %RED%[ERROR]%NC% Docker is not installed. Please install Docker Desktop first.
    echo Download: https://www.docker.com/products/docker-desktop/
    pause
    exit /b 1
)

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo %RED%[ERROR]%NC% Docker is not running. Please start Docker Desktop.
    pause
    exit /b 1
)

echo %GREEN%[SUCCESS]%NC% Prerequisites check passed!
echo.

REM Setup environment
echo %BLUE%[INFO]%NC% Setting up environment...

if not exist ".env" (
    echo %BLUE%[INFO]%NC% No .env file found. Using .env.example for testing...
    copy ".env.example" ".env" >nul
    echo %GREEN%[SUCCESS]%NC% Created .env from .env.example
) else (
    echo %BLUE%[INFO]%NC% .env file already exists. Using existing configuration.
)

if not exist "docker-compose.yml" (
    echo %RED%[ERROR]%NC% docker-compose.yml not found. Are you in the right directory?
    pause
    exit /b 1
)

echo %GREEN%[SUCCESS]%NC% Environment setup complete!
echo.

REM Setup wp-config for Docker
echo %BLUE%[INFO]%NC% Setting up WordPress configuration for Docker...

if not exist "wp-config.php" (
    if exist "wp-config-docker.php" (
        copy "wp-config-docker.php" "wp-config.php" >nul
        echo %GREEN%[SUCCESS]%NC% Created wp-config.php from wp-config-docker.php
    ) else (
        echo %YELLOW%[WARNING]%NC% wp-config-docker.php not found. WordPress will use default configuration.
    )
) else (
    echo %BLUE%[INFO]%NC% wp-config.php already exists. Using existing configuration.
)
echo.

REM Cleanup existing containers
echo %BLUE%[INFO]%NC% Cleaning up any existing containers...
docker-compose down --remove-orphans >nul 2>&1
echo %GREEN%[SUCCESS]%NC% Cleanup complete!
echo.

REM Start Docker services
echo %BLUE%[INFO]%NC% Starting Docker services...

echo %BLUE%[INFO]%NC% Pulling Docker images...
docker-compose pull

echo %BLUE%[INFO]%NC% Starting containers...
docker-compose up -d

echo %BLUE%[INFO]%NC% Waiting for services to initialize...
timeout /t 10 /nobreak >nul

echo %GREEN%[SUCCESS]%NC% Docker services started!
echo.

REM Check service health
echo %BLUE%[INFO]%NC% Checking service health...

REM Test WordPress connection
echo %BLUE%[INFO]%NC% Testing WordPress connection...
set /a attempts=0
:check_wordpress
set /a attempts+=1
powershell -Command "try { Invoke-WebRequest -Uri 'http://localhost:8000' -UseBasicParsing -TimeoutSec 5 | Out-Null; exit 0 } catch { exit 1 }" >nul 2>&1
if not errorlevel 1 (
    echo %GREEN%[SUCCESS]%NC% WordPress is responding!
    goto :wordpress_ok
)
if %attempts% geq 30 (
    echo %RED%[ERROR]%NC% WordPress failed to start after 30 attempts
    echo %BLUE%[INFO]%NC% Checking WordPress logs...
    docker-compose logs wordpress
    pause
    exit /b 1
)
echo | set /p="."
timeout /t 2 /nobreak >nul
goto :check_wordpress

:wordpress_ok
echo %GREEN%[SUCCESS]%NC% All services are healthy!
echo.

REM Check for Node.js
node --version >nul 2>&1
if not errorlevel 1 (
    echo %BLUE%[INFO]%NC% Node.js found. Installing dependencies...
    if exist "package.json" (
        call npm install
        echo %GREEN%[SUCCESS]%NC% Node.js dependencies installed!
        echo.
        set /p start_dev="Do you want to start asset watching for development? (y/N): "
        if /i "!start_dev!"=="y" (
            echo %BLUE%[INFO]%NC% Starting asset watching in background...
            start /b npm run dev >nul 2>&1
            echo %GREEN%[SUCCESS]%NC% Asset watching started! (running in background)
        )
    )
) else (
    echo %YELLOW%[WARNING]%NC% Node.js not available. Skipping dependency installation.
)

echo.
echo %GREEN%[SUCCESS]%NC% WordPress Business Template is now running!
echo.
echo ==========================================
echo   Access Information
echo ==========================================
echo.
echo 🌐 WordPress Site:
echo    http://localhost:8000
echo.
echo 🗄️  phpMyAdmin (Database Management):
echo    http://localhost:8080
echo    Username: wordpress
echo    Password: wordpress_password
echo.
echo 📧 MailHog (Email Testing):
echo    http://localhost:8025
echo.
echo 🔍 Database Details:
echo    Host: localhost:3306
echo    Database: wordpress_db
echo    Username: wordpress
echo    Password: wordpress_password
echo.
echo ==========================================
echo   Next Steps
echo ==========================================
echo.
echo 1. Visit http://localhost:8000 to complete WordPress setup
echo 2. Activate the 'Astra Business Child' theme
echo 3. Configure business information in Appearance → Customize
echo 4. Create pages using the provided templates
echo.
echo To stop the services, run:
echo    docker-compose down
echo.
echo ==========================================
echo   Troubleshooting
echo ==========================================
echo.
echo If you encounter issues:
echo.
echo 📋 View logs:
echo    docker-compose logs wordpress
echo    docker-compose logs db
echo.
echo 🔄 Restart services:
echo    docker-compose restart
echo.
echo 🛑 Stop services:
echo    docker-compose down
echo.
echo 🗑️  Complete reset (removes data!):
echo    docker-compose down -v
echo    docker-compose up -d
echo.
echo %GREEN%[SUCCESS]%NC% Test setup complete! Your WordPress Business Template is ready for development.
echo.
echo Press any key to exit this script (services will continue running)
pause >nul