FROM php:7.1.3-apache

RUN a2enmod rewrite
RUN a2enmod headers

COPY --from=composer:2.1.8 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        git

# RUN  docker-php-ext-configure gd
# RUN  docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-install zip

# RUN pecl install xdebug-3.1.0
# RUN docker-php-ext-enable xdebug


RUN mkdir -p "/.composer"
RUN chown -R 1000:1000 "/.composer"

RUN touch /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

