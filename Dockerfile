# Use official PHP 8.3 image with Alpine for a lightweight base
FROM php:8.3-cli-alpine

# Set working directory
WORKDIR /app

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    unzip \
    bash \
    && docker-php-ext-install opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . /app

# Install project dependencies
RUN composer install --no-interaction --prefer-dist --no-progress

# Set up environment for testing
ENV COMPOSER_ALLOW_SUPERUSER=1

# Run tests
CMD ["composer", "test"]