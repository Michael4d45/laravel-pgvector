# Build production assets
FROM node:lts-alpine as frontend_assets

WORKDIR /app

# Copy application source code
COPY src/ .

RUN npm install && npm run build

# Stage 1: Base image
FROM php:8.2-fpm as php.base

# Install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev libicu-dev libpq-dev zip \
    && docker-php-ext-install -j$(nproc) pcntl intl zip pdo pdo_mysql pdo_pgsql \
    && pecl install -f pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/*

# Install PHPRedis
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.3.5 /usr/bin/composer /usr/local/bin/composer

# Set up application directory
WORKDIR /app
RUN chown www-data:www-data /app


# Stage 2: Local development
FROM php.base as php.local

# Copy entrypoint script
COPY entrypoint.dev.sh /usr/local/bin/entrypoint

# Set execution permissions
RUN chmod +x /usr/local/bin/entrypoint

# Set user and entrypoint
USER www-data
ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["/usr/local/sbin/php-fpm"]

# Stage 3: Production
FROM php.base as php.production

# Copy application source code
COPY src/ .

COPY --from=frontend_assets /app/public/build/manifest.json /app/public/build/manifest.json

# Set ownership of the application
RUN chown -R www-data:www-data /app \
    && chmod 755 /app \
    && chmod -R 775 /app/storage \
    && chmod -R 775 /app/bootstrap/cache

# Install project dependencies
RUN composer install \
    -v \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


# Copy entrypoint script
COPY entrypoint.prd.sh /usr/local/bin/entrypoint

# Set execution permissions
RUN chmod +x /usr/local/bin/entrypoint

USER www-data
ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["/usr/local/sbin/php-fpm"]


# Base image
FROM nginx:stable-alpine as nginx.base

# Local image
FROM nginx.base as nginx.local

# Production
FROM nginx.base as nginx.production

RUN rm -rf /app/public/build

COPY --from=frontend_assets /app/public/build /app/public/build
COPY src/public/favicon.ico /app/public/
COPY src/public/robots.txt /app/public/
