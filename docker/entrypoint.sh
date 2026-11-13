#!/bin/sh
#
# MegaShop container entrypoint.
# Waits for MySQL, runs migrations (and demo seed on an empty database),
# then starts the given command (php-fpm by default).
#
set -eu

cd /var/www/html

log() {
    printf '[entrypoint] %s\n' "$*"
}

: "${DB_HOST:=db}"
: "${DB_PORT:=3306}"
: "${DB_DATABASE:=megashop}"
: "${DB_USERNAME:=megashop}"
: "${DB_PASSWORD:=megashop_secret}"

export DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD

if [ -z "${APP_KEY:-}" ]; then
    APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    export APP_KEY
    log "WARNING: APP_KEY was empty. Generated a temporary key for this container."
    log "         Set APP_KEY in .env.docker to keep sessions valid across restarts."
fi

db_connect() {
    php -r '
        try {
            new PDO(
                "mysql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT") . ";dbname=" . getenv("DB_DATABASE"),
                getenv("DB_USERNAME"),
                getenv("DB_PASSWORD"),
                [
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
            exit(0);
        } catch (Throwable $e) {
            fwrite(STDERR, $e->getMessage() . PHP_EOL);
            exit(1);
        }
    '
}

user_count() {
    php -r '
        try {
            $pdo = new PDO(
                "mysql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT") . ";dbname=" . getenv("DB_DATABASE"),
                getenv("DB_USERNAME"),
                getenv("DB_PASSWORD"),
                [
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
            echo (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        } catch (Throwable $e) {
            echo "0";
        }
    '
}

log "Waiting for MySQL at ${DB_HOST}:${DB_PORT}/${DB_DATABASE} ..."
attempt=0
until db_connect 2>/dev/null; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 60 ]; then
        log "ERROR: MySQL did not become ready after 120 seconds."
        db_connect || true
        exit 1
    fi
    sleep 2
done
log "MySQL is ready."

mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/debugbar \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    log "Running migrations ..."
    php artisan migrate --force --no-interaction

    if [ "${SEED_IF_EMPTY:-true}" = "true" ]; then
        users="$(user_count 2>/dev/null || echo 0)"
        if [ "$users" = "0" ]; then
            log "Empty database detected — seeding demo data (this can take a minute) ..."
            php artisan db:seed --force --no-interaction
            log "Demo data seeded."
        else
            log "Database already contains ${users} users — skipping seed."
        fi
    fi
fi

if [ "$#" -gt 0 ]; then
    log "Starting: $*"
    exec "$@"
fi

log "Starting php-fpm ..."
exec php-fpm -F
