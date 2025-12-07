#!/bin/bash

##
# Import Database
#
# Import an SQL file into the WordPress database
# Usage: ./import-database.sh <filename>
##

set -e

# Color output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if filename is provided
if [ -z "$1" ]; then
    echo -e "${RED}No database file specified.${NC}"
    echo "Usage: ./import-database.sh <filename>"
    exit 1
fi

FILENAME="$1"

# Check if file exists
if [ ! -f "$FILENAME" ]; then
    echo -e "${RED}File not found: $FILENAME${NC}"
    exit 1
fi

# Get environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '#' | xargs)
fi

# Default to app/bedrock path
DB_PATH="${DB_PATH:-.}"

echo -e "${YELLOW}Importing database from: $FILENAME${NC}"

# Import database using WP-CLI
if command -v wp &> /dev/null; then
    echo -e "${GREEN}Using WP-CLI to import database...${NC}"

    # Change to WordPress root
    cd "$DB_PATH"

    # Backup current database before importing
    echo "Creating backup of current database..."
    wp db export "database-backup-before-import-$(date +%Y%m%d-%H%M%S).sql" --allow-root

    # Import the database
    wp db import "$FILENAME" --allow-root

    echo -e "${GREEN}Database imported successfully!${NC}"

    # Show options
    echo ""
    echo "Next steps:"
    echo "  - Verify the database import in WordPress admin"
    echo "  - Run: wp search-replace [old-url] [new-url] --allow-root (if needed)"
    echo "  - Check for media library references if necessary"
else
    echo -e "${RED}WP-CLI not found. Please install it first.${NC}"
    echo "Visit: https://wp-cli.org/docs/installing/"
    exit 1
fi
