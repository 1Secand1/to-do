FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./src /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

EXPOSE 80
