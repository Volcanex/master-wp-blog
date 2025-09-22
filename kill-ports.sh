#!/bin/bash

# WordPress Business Template - Port Killer Script
# ================================================
# This script kills processes using the ports needed by the Docker setup
# Useful for clearing port conflicts before starting the development environment

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
    echo "  WordPress Template - Port Killer"
    echo "=========================================="
    echo ""
}

# Ports used by the WordPress Business Template
declare -A PORTS=(
    [8000]="WordPress"
    [8080]="phpMyAdmin"
    [8025]="MailHog Web UI"
    [1025]="MailHog SMTP"
    [3306]="MySQL Database"
    [6379]="Redis Cache"
    [3000]="Webpack Dev Server (optional)"
)

# Function to check if a port is in use
check_port() {
    local port=$1
    if command -v lsof >/dev/null 2>&1; then
        lsof -ti:$port 2>/dev/null
    elif command -v netstat >/dev/null 2>&1; then
        netstat -tlnp 2>/dev/null | grep ":$port " | awk '{print $7}' | cut -d'/' -f1
    else
        print_error "Neither lsof nor netstat found. Cannot check ports."
        return 1
    fi
}

# Function to kill processes on a specific port
kill_port() {
    local port=$1
    local service=$2

    print_status "Checking port $port ($service)..."

    local pids=$(check_port $port)

    if [ -z "$pids" ]; then
        print_success "Port $port is free"
        return 0
    fi

    print_warning "Port $port is in use by PID(s): $pids"

    # Show what's using the port
    if command -v lsof >/dev/null 2>&1; then
        echo "Process details:"
        lsof -i :$port 2>/dev/null || true
        echo ""
    fi

    # Ask for confirmation unless force flag is used
    if [ "$FORCE_KILL" != "true" ]; then
        echo -n "Kill process(es) on port $port? (y/N): "
        read -r response
        if [[ ! $response =~ ^[Yy]$ ]]; then
            print_warning "Skipping port $port"
            return 0
        fi
    fi

    # Kill the processes
    for pid in $pids; do
        if [ -n "$pid" ] && [ "$pid" != "-" ]; then
            print_status "Killing PID $pid on port $port..."
            if kill -9 "$pid" 2>/dev/null; then
                print_success "Killed PID $pid"
            else
                print_error "Failed to kill PID $pid (may require sudo)"
            fi
        fi
    done

    # Verify port is now free
    sleep 1
    local remaining_pids=$(check_port $port)
    if [ -z "$remaining_pids" ]; then
        print_success "Port $port is now free"
    else
        print_error "Port $port still in use by: $remaining_pids"
    fi
}

# Function to kill Docker containers using these ports
kill_docker_containers() {
    print_status "Checking for Docker containers..."

    # Check if Docker is available
    if ! command -v docker >/dev/null 2>&1; then
        print_warning "Docker not found, skipping container cleanup"
        return 0
    fi

    # Check if Docker is running
    if ! docker info >/dev/null 2>&1; then
        print_warning "Docker not running, skipping container cleanup"
        return 0
    fi

    # Get Docker Compose command
    local docker_compose_cmd
    if command -v docker-compose >/dev/null 2>&1; then
        docker_compose_cmd="docker-compose"
    elif docker compose version >/dev/null 2>&1; then
        docker_compose_cmd="docker compose"
    else
        print_warning "Docker Compose not found, checking individual containers"
        docker_compose_cmd=""
    fi

    # Stop Docker Compose services if available
    if [ -n "$docker_compose_cmd" ] && [ -f "docker-compose.yml" ]; then
        print_status "Stopping Docker Compose services..."
        if $docker_compose_cmd ps -q 2>/dev/null | grep -q .; then
            $docker_compose_cmd down --remove-orphans 2>/dev/null || true
            print_success "Docker Compose services stopped"
        else
            print_status "No Docker Compose services running"
        fi
    fi

    # Check for individual containers that might be using our ports
    print_status "Checking for containers using our ports..."

    local containers_found=false
    for port in "${!PORTS[@]}"; do
        local containers=$(docker ps --filter "publish=$port" --format "{{.Names}}" 2>/dev/null || true)
        if [ -n "$containers" ]; then
            containers_found=true
            print_warning "Containers using port $port: $containers"

            if [ "$FORCE_KILL" = "true" ]; then
                for container in $containers; do
                    print_status "Stopping container: $container"
                    docker stop "$container" >/dev/null 2>&1 || true
                    docker rm "$container" >/dev/null 2>&1 || true
                done
            else
                echo -n "Stop these containers? (y/N): "
                read -r response
                if [[ $response =~ ^[Yy]$ ]]; then
                    for container in $containers; do
                        print_status "Stopping container: $container"
                        docker stop "$container" >/dev/null 2>&1 || true
                        docker rm "$container" >/dev/null 2>&1 || true
                    done
                fi
            fi
        fi
    done

    if [ "$containers_found" = false ]; then
        print_success "No containers found using our ports"
    fi
}

# Function to show port usage summary
show_port_summary() {
    print_status "Port usage summary:"
    echo ""

    for port in "${!PORTS[@]}"; do
        local service="${PORTS[$port]}"
        local pids=$(check_port $port)

        if [ -n "$pids" ]; then
            echo -e "  Port $port ($service): ${RED}IN USE${NC} (PID: $pids)"
        else
            echo -e "  Port $port ($service): ${GREEN}FREE${NC}"
        fi
    done
    echo ""
}

# Function to show help
show_help() {
    echo "WordPress Business Template - Port Killer Script"
    echo ""
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -f, --force     Kill processes without asking for confirmation"
    echo "  -d, --docker    Only stop Docker containers, don't kill other processes"
    echo "  -s, --summary   Show port usage summary only"
    echo "  -h, --help      Show this help message"
    echo ""
    echo "Ports managed:"
    for port in "${!PORTS[@]}"; do
        echo "  $port - ${PORTS[$port]}"
    done
    echo ""
    echo "Examples:"
    echo "  $0              # Interactive mode - ask before killing"
    echo "  $0 -f           # Force kill all processes on managed ports"
    echo "  $0 -d           # Only stop Docker containers"
    echo "  $0 -s           # Show port usage summary"
    echo ""
}

# Parse command line arguments
FORCE_KILL=false
DOCKER_ONLY=false
SUMMARY_ONLY=false

while [[ $# -gt 0 ]]; do
    case $1 in
        -f|--force)
            FORCE_KILL=true
            shift
            ;;
        -d|--docker)
            DOCKER_ONLY=true
            shift
            ;;
        -s|--summary)
            SUMMARY_ONLY=true
            shift
            ;;
        -h|--help)
            show_help
            exit 0
            ;;
        *)
            print_error "Unknown option: $1"
            show_help
            exit 1
            ;;
    esac
done

# Main execution
main() {
    print_header

    if [ "$SUMMARY_ONLY" = true ]; then
        show_port_summary
        exit 0
    fi

    print_status "Checking ports used by WordPress Business Template..."
    echo ""

    # Show current port usage
    show_port_summary

    # Stop Docker containers first
    kill_docker_containers
    echo ""

    # If docker-only mode, exit here
    if [ "$DOCKER_ONLY" = true ]; then
        print_success "Docker container cleanup complete!"
        echo ""
        show_port_summary
        exit 0
    fi

    # Kill processes on each port
    print_status "Processing individual ports..."
    echo ""

    # Sort ports numerically for consistent output
    for port in $(printf '%s\n' "${!PORTS[@]}" | sort -n); do
        kill_port "$port" "${PORTS[$port]}"
        echo ""
    done

    print_success "Port cleanup complete!"
    echo ""

    # Show final status
    print_status "Final port status:"
    show_port_summary

    # Check if any ports are still in use
    local ports_in_use=false
    for port in "${!PORTS[@]}"; do
        local pids=$(check_port $port)
        if [ -n "$pids" ]; then
            ports_in_use=true
            break
        fi
    done

    if [ "$ports_in_use" = true ]; then
        print_warning "Some ports are still in use. You may need to:"
        echo "  1. Run with sudo: sudo $0 -f"
        echo "  2. Manually investigate persistent processes"
        echo "  3. Restart your system if processes are stuck"
    else
        print_success "All WordPress template ports are now free!"
        echo ""
        print_status "You can now run: ./test-startup.sh"
    fi
}

# Handle script interruption
cleanup_on_exit() {
    echo ""
    print_warning "Script interrupted."
    exit 1
}

# Set up signal handlers
trap cleanup_on_exit INT TERM

# Run main function
main "$@"