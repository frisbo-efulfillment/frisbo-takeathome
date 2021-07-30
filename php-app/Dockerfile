FROM php:7.4-apache

RUN apt-get update && apt-get install git libonig-dev --yes

WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql mbstring mysqli

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite