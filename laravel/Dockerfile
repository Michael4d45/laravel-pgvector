# Stage 1: Base image
FROM php:8.2-fpm as base

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
FROM base as local

# Copy entrypoint script
COPY entrypoint.dev.sh /usr/local/bin/entrypoint

# Set execution permissions
RUN chmod +x /usr/local/bin/entrypoint

# Set user and entrypoint
USER www-data
ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["/usr/local/sbin/php-fpm"]


# Build production assets
FROM node:lts-alpine as frontend_assets

WORKDIR /app

# Copy application source code
COPY src/ .

RUN npm install && npm run build


# Stage 3: Production
FROM base as production

# Copy application source code
COPY src/ .

COPY --from=frontend_assets /app/public /app/public

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
RUN rm -f composer.json composer.lock



# Copy entrypoint script
COPY entrypoint.prd.sh /usr/local/bin/entrypoint

# Set execution permissions
RUN chmod +x /usr/local/bin/entrypoint

USER www-data
ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["/usr/local/sbin/php-fpm"]
