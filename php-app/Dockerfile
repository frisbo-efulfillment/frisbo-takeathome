FROM composer:2 AS dependencies

RUN rm -rf /var/www && mkdir -p /var/www/html
WORKDIR /var/www/html

COPY src/composer.* ./

RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-autoloader
RUN composer dump-autoload

FROM php:8.3-apache

ARG user
ARG uid

RUN apt-get update && apt-get install git libonig-dev unzip --yes

WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql mbstring mysqli

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# normally wouldn't do this, but people need to bring in deps
COPY --from=dependencies /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=dependencies --chown=$user:www-data /var/www/html /var/www/html

COPY --chown=$user:www-data src /var/www/html

RUN a2enmod rewrite

USER $user
