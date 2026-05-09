# Multi-stage build for faster subsequent builds
FROM php:8.4-fpm-alpine AS php-ext-builder

# Install build dependencies
RUN apk add --no-cache \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    zlib-dev \
    postgresql-dev \
    build-base \
    libxml2-dev \
    icu-dev

# Configure and install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    exif \
    bcmath \
    intl \
    opcache \
    calendar

# Production stage
FROM php:8.4-fpm-alpine

# Install only runtime dependencies (not dev packages)
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    libzip \
    zlib \
    postgresql-libs \
    supervisor \
    libxml2 \
    icu-libs \
    nodejs \
    npm

# Copy pre-compiled extensions from builder stage
COPY --from=php-ext-builder /usr/local/lib/php/extensions/no-debug-non-zts-20240924/ /usr/local/lib/php/extensions/no-debug-non-zts-20240924/
COPY --from=php-ext-builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy package files first for better npm caching
COPY package.json package-lock.json ./
RUN npm ci && npm cache clean --force

# Copy composer files
COPY composer.json composer.lock ./
# Copy application code (including artisan) before composer install
COPY . .
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Build assets
RUN npm run build
RUN npm prune --production

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache public/build

# Copy supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir -p /var/log/supervisor

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
