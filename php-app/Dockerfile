FROM composer:2 as dependencies

RUN rm -rf /var/www && mkdir -p /var/www/html
WORKDIR /var/www/html

COPY src/composer.* ./

RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-dev --no-autoloader
RUN composer dump-autoload --optimize --apcu --no-dev

FROM php:7.4-apache

RUN apt-get update && apt-get install git libonig-dev --yes

WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql mbstring mysqli

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=dependencies /var/www/html /var/www/html

COPY --chown=www-data:www-data src/ /var/www/html

RUN a2enmod rewrite