@echo off
REM WordPress Business Template - Port Killer Script (Windows)
REM ==========================================================
REM This script kills processes using the ports needed by the Docker setup
REM Useful for clearing port conflicts before starting the development environment

setlocal enabledelayedexpansion

REM Colors for output (Windows)
set "RED=[91m"
set "GREEN=[92m"
set "YELLOW=[93m"
set "BLUE=[94m"
set "NC=[0m"

REM Parse command line arguments
set "FORCE_KILL=false"
set "DOCKER_ONLY=false"
set "SUMMARY_ONLY=false"

:parse_args
if "%1"=="-f" set "FORCE_KILL=true" & shift & goto parse_args
if "%1"=="--force" set "FORCE_KILL=true" & shift & goto parse_args
if "%1"=="-d" set "DOCKER_ONLY=true" & shift & goto parse_args
if "%1"=="--docker" set "DOCKER_ONLY=true" & shift & goto parse_args
if "%1"=="-s" set "SUMMARY_ONLY=true" & shift & goto parse_args
if "%1"=="--summary" set "SUMMARY_ONLY=true" & shift & goto parse_args
if "%1"=="-h" goto show_help
if "%1"=="--help" goto show_help
if not "%1"=="" (
    echo %RED%[ERROR]%NC% Unknown option: %1
    goto show_help
)

echo.
echo ==========================================
echo   WordPress Template - Port Killer
echo ==========================================
echo.

REM Ports used by the WordPress Business Template
set "PORT_8000=WordPress"
set "PORT_8080=phpMyAdmin"
set "PORT_8025=MailHog Web UI"
set "PORT_1025=MailHog SMTP"
set "PORT_3306=MySQL Database"
set "PORT_6379=Redis Cache"
set "PORT_3000=Webpack Dev Server (optional)"

REM List of ports to check
set "PORTS=8000 8080 8025 1025 3306 6379 3000"

if "%SUMMARY_ONLY%"=="true" goto show_summary_only

echo %BLUE%[INFO]%NC% Checking ports used by WordPress Business Template...
echo.

REM Show current port usage
call :show_port_summary

REM Stop Docker containers first
call :kill_docker_containers
echo.

REM If docker-only mode, exit here
if "%DOCKER_ONLY%"=="true" (
    echo %GREEN%[SUCCESS]%NC% Docker container cleanup complete!
    echo.
    call :show_port_summary
    goto end
)

REM Kill processes on each port
echo %BLUE%[INFO]%NC% Processing individual ports...
echo.

for %%p in (%PORTS%) do (
    call :kill_port %%p
    echo.
)

echo %GREEN%[SUCCESS]%NC% Port cleanup complete!
echo.

REM Show final status
echo %BLUE%[INFO]%NC% Final port status:
call :show_port_summary

echo %GREEN%[SUCCESS]%NC% All WordPress template ports should now be free!
echo.
echo %BLUE%[INFO]%NC% You can now run: test-startup.bat
goto end

:show_summary_only
call :show_port_summary
goto end

:show_port_summary
echo %BLUE%[INFO]%NC% Port usage summary:
echo.

for %%p in (%PORTS%) do (
    call :check_port %%p
    if !PORT_IN_USE!==true (
        call :get_port_name %%p
        echo   Port %%p (!PORT_NAME!): %RED%IN USE%NC%
    ) else (
        call :get_port_name %%p
        echo   Port %%p (!PORT_NAME!): %GREEN%FREE%NC%
    )
)
echo.
goto :eof

:get_port_name
set "PORT_NAME=!PORT_%1!"
goto :eof

:check_port
set "PORT_IN_USE=false"
for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":%1 "') do (
    if not "%%a"=="" set "PORT_IN_USE=true"
)
goto :eof

:kill_port
set "current_port=%1"
call :get_port_name %current_port%
set "service_name=!PORT_NAME!"

echo %BLUE%[INFO]%NC% Checking port %current_port% (!service_name!)...

REM Get PIDs using the port
set "PIDS="
for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":%current_port% "') do (
    if not "%%a"=="" set "PIDS=!PIDS! %%a"
)

if "%PIDS%"=="" (
    echo %GREEN%[SUCCESS]%NC% Port %current_port% is free
    goto :eof
)

echo %YELLOW%[WARNING]%NC% Port %current_port% is in use by PID(s):%PIDS%

REM Show what's using the port
echo Process details:
netstat -ano | findstr ":%current_port% "
echo.

REM Ask for confirmation unless force flag is used
if not "%FORCE_KILL%"=="true" (
    set /p "response=Kill process(es) on port %current_port%? (y/N): "
    if /i not "!response!"=="y" (
        echo %YELLOW%[WARNING]%NC% Skipping port %current_port%
        goto :eof
    )
)

REM Kill the processes
for %%p in (%PIDS%) do (
    if not "%%p"=="" (
        echo %BLUE%[INFO]%NC% Killing PID %%p on port %current_port%...
        taskkill /PID %%p /F >nul 2>&1
        if !errorlevel!==0 (
            echo %GREEN%[SUCCESS]%NC% Killed PID %%p
        ) else (
            echo %RED%[ERROR]%NC% Failed to kill PID %%p
        )
    )
)

REM Wait a moment and verify
timeout /t 2 /nobreak >nul

REM Check if port is now free
call :check_port %current_port%
if !PORT_IN_USE!==false (
    echo %GREEN%[SUCCESS]%NC% Port %current_port% is now free
) else (
    echo %RED%[ERROR]%NC% Port %current_port% still in use
)

goto :eof

:kill_docker_containers
echo %BLUE%[INFO]%NC% Checking for Docker containers...

REM Check if Docker is available
docker --version >nul 2>&1
if errorlevel 1 (
    echo %YELLOW%[WARNING]%NC% Docker not found, skipping container cleanup
    goto :eof
)

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo %YELLOW%[WARNING]%NC% Docker not running, skipping container cleanup
    goto :eof
)

REM Stop Docker Compose services if available
if exist "docker-compose.yml" (
    echo %BLUE%[INFO]%NC% Stopping Docker Compose services...

    REM Try docker-compose first, then docker compose
    docker-compose ps >nul 2>&1
    if not errorlevel 1 (
        docker-compose down --remove-orphans >nul 2>&1
        echo %GREEN%[SUCCESS]%NC% Docker Compose services stopped
    ) else (
        docker compose ps >nul 2>&1
        if not errorlevel 1 (
            docker compose down --remove-orphans >nul 2>&1
            echo %GREEN%[SUCCESS]%NC% Docker Compose services stopped
        ) else (
            echo %BLUE%[INFO]%NC% No Docker Compose services running
        )
    )
) else (
    echo %BLUE%[INFO]%NC% No docker-compose.yml found, checking individual containers
)

REM Check for individual containers
echo %BLUE%[INFO]%NC% Checking for containers using our ports...

set "containers_found=false"
for %%p in (%PORTS%) do (
    for /f "delims=" %%c in ('docker ps --filter "publish=%%p" --format "{{.Names}}" 2^>nul') do (
        set "containers_found=true"
        echo %YELLOW%[WARNING]%NC% Container using port %%p: %%c

        if "%FORCE_KILL%"=="true" (
            echo %BLUE%[INFO]%NC% Stopping container: %%c
            docker stop "%%c" >nul 2>&1
            docker rm "%%c" >nul 2>&1
        ) else (
            set /p "response=Stop container %%c? (y/N): "
            if /i "!response!"=="y" (
                echo %BLUE%[INFO]%NC% Stopping container: %%c
                docker stop "%%c" >nul 2>&1
                docker rm "%%c" >nul 2>&1
            )
        )
    )
)

if "%containers_found%"=="false" (
    echo %GREEN%[SUCCESS]%NC% No containers found using our ports
)

goto :eof

:show_help
echo WordPress Business Template - Port Killer Script (Windows)
echo.
echo Usage: %0 [OPTIONS]
echo.
echo Options:
echo   -f, --force     Kill processes without asking for confirmation
echo   -d, --docker    Only stop Docker containers, don't kill other processes
echo   -s, --summary   Show port usage summary only
echo   -h, --help      Show this help message
echo.
echo Ports managed:
echo   8000 - WordPress
echo   8080 - phpMyAdmin
echo   8025 - MailHog Web UI
echo   1025 - MailHog SMTP
echo   3306 - MySQL Database
echo   6379 - Redis Cache
echo   3000 - Webpack Dev Server (optional)
echo.
echo Examples:
echo   %0              # Interactive mode - ask before killing
echo   %0 -f           # Force kill all processes on managed ports
echo   %0 -d           # Only stop Docker containers
echo   %0 -s           # Show port usage summary
echo.
goto end

:end
pause