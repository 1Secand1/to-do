FROM php:8.1-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev zip unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && docker-php-ext-enable mysqli pdo pdo_mysql \
    && a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY ./src /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /var/www/html

EXPOSE 80

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get clean && rm -rf /tmp/* /var/tmp/*

CMD ["apache2-foreground"]
