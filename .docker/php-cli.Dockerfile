FROM php:8.1-cli

# Install XDebug
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

