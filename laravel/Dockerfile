# Base image
FROM php:8.2-fpm as base

# Install dependencies
RUN apt-get update \
    && apt-get install -y libzip-dev zip libicu-dev libpq-dev \
    && docker-php-ext-install pcntl intl zip pdo pdo_mysql pdo_pgsql \
    && pecl install -f pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.3.5 /usr/bin/composer /usr/local/bin/composer

# Set up application directory
WORKDIR /var/www/app
RUN chown www-data:www-data /var/www/app

# Local development stage
FROM base as local
COPY entrypoint.dev.sh /tmp/entrypoint.dev.sh
RUN chmod +x /tmp/entrypoint.dev.sh
USER www-data
CMD ["/tmp/entrypoint.dev.sh"]

# Production stage
FROM base as production

# Copy application source code
COPY ../src/ .

# Set ownership of application directory
RUN chown -R www-data:www-data /var/www/app
USER www-data

# Install project dependencies
RUN composer install --no-dev --prefer-dist --no-interaction --no-plugins --no-scripts \
    && rm -f composer.json composer.lock

# Copy production entrypoint script
COPY entrypoint.prd.sh /usr/local/bin/
ENTRYPOINT ["entrypoint.prd.sh"]

CMD ["php-fpm"]
