#!/bin/sh
# NOTE: this script is sourced by the base image's entrypoint using POSIX `sh`
# (dash), not bash, so avoid bash-only syntax such as `[[ ]]`.

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
    if [ "$DB_CONNECTION" = "sqlite" ]; then
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

# This script runs as one of the base image's entrypoint.d scripts, which are
# sourced inside a subshell. `export`ing variables here would NOT propagate to
# the php-fpm/nginx process started afterward, so persist secrets to .env
# instead (Laravel reads .env directly, and 50-laravel-automations.sh picks
# these up when it runs `php artisan config:cache` after this script).
set_env_value() {
    key="$1"
    value="$2"
    escaped_value=$(printf '%s' "$value" | sed -e 's/[\/&]/\\&/g')
    if grep -q "^${key}=" .env; then
        sed -i "s/^${key}=.*/${key}=${escaped_value}/" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

# Check if secret file exists, otherwise fall back to the environment variable
if [ -f /run/secrets/MAIL_USERNAME ]; then
    set_env_value "MAIL_USERNAME" "$(cat /run/secrets/MAIL_USERNAME)"
elif [ -n "$MAIL_USERNAME" ]; then
    set_env_value "MAIL_USERNAME" "$MAIL_USERNAME"
fi

if [ -f /run/secrets/MAIL_PASSWORD ]; then
    set_env_value "MAIL_PASSWORD" "$(cat /run/secrets/MAIL_PASSWORD)"
elif [ -n "$MAIL_PASSWORD" ]; then
    set_env_value "MAIL_PASSWORD" "$MAIL_PASSWORD"
fi

exit 0
