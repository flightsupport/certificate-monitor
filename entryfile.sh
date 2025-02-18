#!/bin/bash

set -e

IMPORT_FILE="/app/import.json"
# Navigate to the Laravel project root
dir="/var/www/html"
cd "$dir"

# Check if .env file exists, if not, copy from .env.example
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example"
    cp .env.example .env
fi

# Generate application key
php artisan key:generate --force

# Ensure the database file exists (for SQLite) or create an empty file
if [ -n "$DB_DATABASE" ]; then
    if [[ "$DB_CONNECTION" == "sqlite" ]]; then
        if [ ! -f "$DB_DATABASE" ]; then
            echo "Creating SQLite database file: $DB_DATABASE"
            touch "$DB_DATABASE"
            # Run migrations
            php artisan migrate --force

            # Check if import.json exists, then run the monitor sync and certificate check
            if [ -f "$IMPORT_FILE" ]; then
                echo "Importing monitors from $IMPORT_FILE..."
                php artisan monitor:sync-file "$IMPORT_FILE" --delete-missing
                echo "Checking certificates..."
                php artisan monitor:check-certificate
            else
                echo "No $IMPORT_FILE found, skipping monitor sync."
            fi
        else
            echo "Found database: $DB_DATABASE - skipping import tasks."
        fi
    fi
fi

# Check if secret file exists, otherwise use the environment variable (docker swarm)
if [ -f /run/secrets/MAIL_USERNAME ]; then
    export MAIL_USERNAME=$(cat /run/secrets/MAIL_USERNAME)
else
    export MAIL_USERNAME=${MAIL_USERNAME:-""}
fi

if [ -f /run/secrets/MAIL_PASSWORD ]; then
    export MAIL_PASSWORD=$(cat /run/secrets/MAIL_PASSWORD)
else
    export MAIL_PASSWORD=${MAIL_PASSWORD:-""}
fi

chown -R www-data:www-data /var/www/

# Execute the CMD from the Dockerfile (if any)
exec "$@"
