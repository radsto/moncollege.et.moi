FROM php:8.2-fpm

RUN apt update  \
    && apt install -y libicu-dev libonig-dev

RUN docker-php-ext-install intl mbstring pdo pdo_mysql
