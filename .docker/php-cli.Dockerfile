FROM php:8.1-cli

# Install XDebug
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
