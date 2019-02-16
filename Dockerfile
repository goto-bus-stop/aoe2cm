FROM php:7-fpm

RUN apt-get update && \
    apt-get install -y gettext locales && \
    locale-gen en_US.UTF-8 en_us && \
    docker-php-ext-install gettext pdo pdo_mysql bcmath
