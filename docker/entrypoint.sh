#!/bin/sh

set -eu

cd /var/www/html

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

key_file="storage/framework/docker-app-key"

if [ -z "${APP_KEY:-}" ]; then
    if [ ! -s "$key_file" ]; then
        generated_key="$(gosu www-data php artisan key:generate --show --no-ansi)"
        printf '%s\n' "$generated_key" > "$key_file"
        chown www-data:www-data "$key_file"
        chmod 600 "$key_file"
    fi

    APP_KEY="$(cat "$key_file")"
    export APP_KEY
fi

printf 'APP_KEY=%s\n' "$APP_KEY" > .env
chown www-data:www-data .env
chmod 600 .env

if [ "${1:-}" = "apache2-foreground" ]; then
    if [ "${DOCKER_RUN_MIGRATIONS:-true}" = "true" ]; then
        gosu www-data php artisan migrate --force
    fi

    if [ "${DOCKER_SEED_DATABASE:-true}" = "true" ]; then
        if gosu www-data php -r '
            require "vendor/autoload.php";
            $app = require "bootstrap/app.php";
            $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
            exit(App\Models\User::query()->exists() ? 0 : 1);
        '; then
            echo "Database already contains users; demo seeding skipped."
        else
            gosu www-data php artisan db:seed --force
        fi
    fi

    exec apache2-foreground
fi

if [ "$(id -u)" = "0" ]; then
    exec gosu www-data "$@"
fi

exec "$@"
