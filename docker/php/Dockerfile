FROM php:8.3-fpm-alpine
RUN apk add --no-cache \
     curl \
     git

ENV UID=1000
ENV GUID=1000

RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer

RUN docker-php-ext-install pdo_mysql
WORKDIR /opt/app

USER $UID