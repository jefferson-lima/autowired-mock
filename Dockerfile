FROM php:7.4-cli
RUN apt-get update && apt-get install -y git zip unzip
RUN pecl install && pecl install xdebug-2.8.1 && docker-php-ext-enable xdebug
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    echo "include=/usr/local/etc/php/conf.d/*.conf" >> /usr/local/etc/php/php.ini
COPY . /app
WORKDIR /app
