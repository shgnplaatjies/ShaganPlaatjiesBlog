#!/bin/bash

##
# Export Database
#
# Export the WordPress database to an SQL file
# Usage: ./export-database.sh [filename]
##

set -e

# Color output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '#' | xargs)
fi

# Use provided filename or default to timestamp
FILENAME="${1:-database-backup-$(date +%Y%m%d-%H%M%S).sql}"

# Default to app/bedrock path
DB_PATH="${DB_PATH:-.}"

echo -e "${YELLOW}Exporting WordPress database...${NC}"
echo "Filename: $FILENAME"

# Export database using WP-CLI
if command -v wp &> /dev/null; then
    echo -e "${GREEN}Using WP-CLI to export database...${NC}"

    # Change to WordPress root
    cd "$DB_PATH"

    # Export database
    wp db export "$FILENAME" --allow-root

    echo -e "${GREEN}Database exported successfully!${NC}"
    echo -e "${GREEN}File: $(pwd)/$FILENAME${NC}"
    echo ""
    echo "To import this database on another environment:"
    echo "  wp db import $FILENAME"
else
    echo -e "${RED}WP-CLI not found. Please install it first.${NC}"
    echo "Visit: https://wp-cli.org/docs/installing/"
    exit 1
fi

# Show file info
if [ -f "$FILENAME" ]; then
    echo "File size: $(du -h "$FILENAME" | cut -f1)"
fi
