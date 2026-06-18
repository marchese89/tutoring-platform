FROM php:8.3-apache-bookworm

ARG COMPOSER_NO_DEV=0

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        git \
        gosu \
        libcurl4-openssl-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libsqlite3-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        curl \
        dom \
        gd \
        mbstring \
        pcntl \
        pdo_mysql \
        pdo_sqlite \
        zip \
    && a2enmod headers rewrite \
    && printf 'ServerName localhost\n' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN if [ "$COMPOSER_NO_DEV" = "1" ]; then \
        composer install \
            --no-dev \
            --no-interaction \
            --no-progress \
            --no-scripts \
            --prefer-dist; \
    else \
        composer install \
            --no-interaction \
            --no-progress \
            --no-scripts \
            --prefer-dist; \
    fi

COPY . .
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint

RUN composer dump-autoload --no-interaction --optimize \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
    && chmod +x /usr/local/bin/docker-entrypoint \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80 8080

ENTRYPOINT ["docker-entrypoint"]
CMD ["apache2-foreground"]
