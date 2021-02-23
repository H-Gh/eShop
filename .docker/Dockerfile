FROM php:8.0-fpm

COPY composer.lock composer.json /var/www/

WORKDIR /var/www

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y wget curl gnupg2 lsb-release ca-certificates apt-transport-https software-properties-common

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www

COPY --chown=www:www . /var/www

USER www

EXPOSE 9000

RUN mkdir vendor
RUN chmod 0775 vendor

RUN composer install
COPY .env.example .env
COPY ./.docker/run.sh /tmp
ENTRYPOINT ["/tmp/run.sh"]

