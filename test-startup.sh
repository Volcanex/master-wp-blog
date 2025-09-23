#!/bin/bash

# WordPress Business Template - Test Startup Script
# ================================================
# This script tests the Docker setup with the master template
# It will use .env.local.example if no .env.local exists and start all services

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo ""
    echo "=========================================="
    echo "  WordPress Business Template Test"
    echo "=========================================="
    echo ""
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to get the correct docker compose command
get_docker_compose_cmd() {
    if command_exists docker-compose; then
        echo "docker-compose"
    elif docker compose version >/dev/null 2>&1; then
        echo "docker compose"
    else
        return 1
    fi
}

# Function to check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."

    # Check Docker
    if ! command_exists docker; then
        print_error "Docker is not installed. Please install Docker Desktop first."
        echo "Download: https://www.docker.com/products/docker-desktop/"
        exit 1
    fi

    # Check Docker Compose and set the command
    DOCKER_COMPOSE_CMD=$(get_docker_compose_cmd)
    if [ $? -ne 0 ]; then
        print_error "Docker Compose is not available. Please install Docker Compose."
        exit 1
    fi
    print_status "Using Docker Compose command: $DOCKER_COMPOSE_CMD"

    # Check if Docker is running
    if ! docker info >/dev/null 2>&1; then
        print_error "Docker is not running. Please start Docker Desktop."
        exit 1
    fi

    # Check Node.js (optional for this test)
    if command_exists node; then
        NODE_VERSION=$(node --version)
        print_status "Node.js found: $NODE_VERSION"
    else
        print_warning "Node.js not found. Asset building will not be available."
    fi

    print_success "Prerequisites check passed!"
}

# Function to setup environment
setup_environment() {
    print_status "Setting up environment..."

    # Use .env.local.example if .env.local doesn't exist
    if [ ! -f ".env.local" ]; then
        print_status "No .env.local file found. Using .env.local.example for testing..."
        cp .env.local.example .env.local
        print_success "Created .env.local from .env.local.example"
    else
        print_status ".env.local file already exists. Using existing configuration."
    fi

    # Check if docker-compose.yml exists
    if [ ! -f "docker-compose.yml" ]; then
        print_error "docker-compose.yml not found. Are you in the right directory?"
        exit 1
    fi

    print_success "Environment setup complete!"
}

# Function to setup wp-config for Docker
setup_wp_config() {
    print_status "Setting up WordPress configuration for Docker..."

    # Use Docker-specific wp-config if it doesn't exist
    if [ ! -f "wp-config.php" ]; then
        if [ -f "wp-config-docker.php" ]; then
            cp wp-config-docker.php wp-config.php
            print_success "Created wp-config.php from wp-config-docker.php"
        else
            print_warning "wp-config-docker.php not found. WordPress will use default configuration."
        fi
    else
        print_status "wp-config.php already exists. Using existing configuration."
    fi
}


# Function to stop any existing containers
cleanup_existing() {
    print_status "Cleaning up any existing containers..."

    # Stop and remove containers if they exist
    if $DOCKER_COMPOSE_CMD ps -q | grep -q .; then
        print_status "Stopping existing containers..."
        $DOCKER_COMPOSE_CMD down --remove-orphans
    fi

    print_success "Cleanup complete!"
}

# Function to start Docker services
start_docker_services() {
    print_status "Starting Docker services..."

    # Pull latest images
    print_status "Pulling Docker images..."
    $DOCKER_COMPOSE_CMD pull

    # Start services in detached mode
    print_status "Starting containers..."
    $DOCKER_COMPOSE_CMD up -d

    # Wait for services to start with better timing
    print_status "Waiting for services to initialize..."
    sleep 5

    # Wait specifically for database to be ready
    print_status "Waiting for database to be ready..."
    for i in {1..60}; do
        if $DOCKER_COMPOSE_CMD exec -T db mysqladmin ping -h localhost --silent 2>/dev/null; then
            print_success "Database is ready!"
            break
        fi
        if [ $i -eq 60 ]; then
            print_warning "Database took longer than expected to start, continuing anyway..."
            break
        fi
        echo -n "."
        sleep 2
    done

    print_success "Docker services started!"
}

# Function to wait for database to be fully ready
wait_for_database() {
    print_status "Waiting for MySQL database to be fully ready..."

    # Wait for MySQL to accept connections
    for i in {1..60}; do
        if $DOCKER_COMPOSE_CMD exec -T db mysqladmin ping -h localhost --silent 2>/dev/null; then
            break
        fi
        if [ $i -eq 60 ]; then
            print_error "MySQL failed to start after 2 minutes"
            return 1
        fi
        echo -n "."
        sleep 2
    done

    echo ""
    print_status "MySQL is accepting connections, waiting for database initialization..."

    # Wait for the specific database and user to be ready
    for i in {1..30}; do
        if $DOCKER_COMPOSE_CMD exec -T db mysql -u wordpress -pwordpress_password -e "SELECT 1;" >/dev/null 2>&1; then
            print_success "Database is fully ready!"
            return 0
        fi
        if [ $i -eq 30 ]; then
            print_warning "Database authentication not ready, but MySQL is running. WordPress should handle the connection."
            return 0
        fi
        echo -n "."
        sleep 2
    done
}


# Function to check service health
check_service_health() {
    print_status "Checking service health..."

    # Check if containers are running
    if ! $DOCKER_COMPOSE_CMD ps | grep -q "Up"; then
        print_error "Some containers failed to start!"
        $DOCKER_COMPOSE_CMD ps
        exit 1
    fi

    # Wait for database to be ready first
    wait_for_database
    echo ""

    # Test WordPress connection
    print_status "Testing WordPress connection..."
    for i in {1..30}; do
        if curl -s http://localhost:8000 >/dev/null 2>&1; then
            print_success "WordPress is responding!"
            break
        fi
        if [ $i -eq 30 ]; then
            print_error "WordPress failed to start after 30 attempts"
            print_status "WordPress logs:"
            $DOCKER_COMPOSE_CMD logs --tail=20 wordpress
            print_status "Database logs:"
            $DOCKER_COMPOSE_CMD logs --tail=10 db
            exit 1
        fi
        echo -n "."
        sleep 2
    done

    print_success "All services are healthy!"
}

# Function to install Node dependencies (if Node.js is available)
install_node_dependencies() {
    if command_exists npm; then
        print_status "Installing Node.js dependencies..."

        if [ -f "package.json" ]; then
            npm install
            print_success "Node.js dependencies installed!"

            # Ask if user wants to start asset watching
            echo ""
            read -p "Do you want to start asset watching for development? (y/N): " -n 1 -r
            echo
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                print_status "Starting asset watching in background..."
                npm run dev > /dev/null 2>&1 &
                print_success "Asset watching started! (running in background)"
            fi
        else
            print_warning "package.json not found. Skipping Node.js setup."
        fi
    else
        print_warning "Node.js not available. Skipping dependency installation."
    fi
}

# Function to display access information
display_access_info() {
    print_success "WordPress Business Template is now running!"
    echo ""
    echo "=========================================="
    echo "  Access Information"
    echo "=========================================="
    echo ""
    echo "🌐 WordPress Site:"
    echo "   http://localhost:8000"
    echo ""
    echo "🗄️  phpMyAdmin (Database Management):"
    echo "   http://localhost:8080"
    echo "   Username: wordpress"
    echo "   Password: wordpress_password"
    echo ""
    echo "🔍 Database Details:"
    echo "   Host: localhost:3306"
    echo "   Database: wordpress_db"
    echo "   Username: wordpress"
    echo "   Password: wordpress_password"
    echo ""
    echo "=========================================="
    echo "  Next Steps"
    echo "=========================================="
    echo ""
    echo "1. Visit http://localhost:8000 to complete WordPress setup"
    echo "2. Activate the 'Astra Business Child' theme (if available)"
    echo "3. Configure business information in Appearance → Customize"
    echo "4. Create pages using the provided templates"
    echo ""
    echo "To stop the services, run:"
    echo "   $DOCKER_COMPOSE_CMD down"
    echo ""
}

# Function to display troubleshooting info
display_troubleshooting() {
    echo "=========================================="
    echo "  Troubleshooting"
    echo "=========================================="
    echo ""
    echo "If you encounter issues:"
    echo ""
    echo "📋 View logs:"
    echo "   $DOCKER_COMPOSE_CMD logs wordpress"
    echo "   $DOCKER_COMPOSE_CMD logs db"
    echo ""
    echo "🔄 Restart services:"
    echo "   $DOCKER_COMPOSE_CMD restart"
    echo ""
    echo "🛑 Stop services:"
    echo "   $DOCKER_COMPOSE_CMD down"
    echo ""
    echo "🗑️  Complete reset (removes data!):"
    echo "   $DOCKER_COMPOSE_CMD down -v"
    echo "   $DOCKER_COMPOSE_CMD up -d"
    echo ""
    echo "📁 Check container status:"
    echo "   $DOCKER_COMPOSE_CMD ps"
    echo ""
}

# Function to handle script interruption
cleanup_on_exit() {
    echo ""
    print_warning "Script interrupted. Docker services are still running."
    echo "To stop services, run: $DOCKER_COMPOSE_CMD down"
    exit 0
}

# Main execution
main() {
    # Set up signal handlers
    trap cleanup_on_exit INT TERM

    print_header

    print_status "Starting WordPress Business Template test setup..."
    echo ""

    # Run setup steps
    check_prerequisites
    echo ""

    setup_environment
    echo ""

    setup_wp_config
    echo ""

    cleanup_existing
    echo ""

    start_docker_services
    echo ""

    check_service_health
    echo ""


    install_node_dependencies
    echo ""

    display_access_info

    display_troubleshooting

    print_success "Test setup complete! Your WordPress Business Template is ready for development."
    echo ""
    print_status "Press Ctrl+C to exit this script (services will continue running)"

    # Keep script running to show it's active
    while true; do
        sleep 60
        if ! $DOCKER_COMPOSE_CMD ps -q | grep -q .; then
            print_warning "Docker services have stopped."
            break
        fi
    done
}

# Run main function
main "$@"