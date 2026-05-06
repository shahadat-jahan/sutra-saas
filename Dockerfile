FROM php:8.5-fpm-alpine

RUN apk add --no-cache \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    zlib-dev \
    postgresql-dev \
    pkgconf \
    curl \
    build-base \
    supervisor \
    libxml2-dev \
    git \
    oniguruma-dev \
    gmp-dev \
    gettext-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    exif \
    bcmath \
    intl \
    opcache \
    calendar \
    gettext

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir -p /var/log/supervisor

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
