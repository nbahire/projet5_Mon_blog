FROM php:8.1-fpm-alpine

COPY . /web

WORKDIR /web

RUN apt-get update \
&& apt-get install -y zip unzip libzip-dev default-mysql-client \
&& docker-php-ext-install zip pdo_mysql

# Install composer for the application dependencies
RUN curl -sS https://getcomposer.org/installer | php \
&& mv composer.phar /bin/composer

# Install project dependencies
RUN composer install

FROM mysql:latest

COPY mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.conf

FROM php:8.1-fpm-alpine as base

# -----------------------------------------------------------
FROM nginx

ENV UNAME=web

RUN apt-get update \
&& apt-get install -y curl vim mc tree jq \
# Cleanup
&& apt-get clean \
&& apt-get autoremove -y --purge

# copy nginx virtual host configuration for the prokect
COPY ./etc/nginx/site.conf /etc/nginx/conf.d/default.conf

# copy public directory from the php-fpm docker image loaded here as base
COPY --from=base /web/public /web/public