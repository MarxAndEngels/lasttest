FROM php:8.1-fpm

RUN apt-get update && apt-get upgrade -y && apt-get install -y supervisor

COPY ./_docker/supervisor/supervisor.conf /etc/supervisor/supervisor.conf

RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
    pcntl

