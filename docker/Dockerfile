FROM php:8.0-buster

RUN apt-get update \
    && apt-get install -y zip

RUN curl --silent --show-error https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer --version=2.1.14

RUN yes | pecl install xdebug-3.1.1

WORKDIR /app
