FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg="$(printf '%s' $(apk info -e libjpeg-turbo|head -n1))" \
    && docker-php-ext-install gd zip

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html/

COPY ./vendor ./vendor

COPY ./app .

VOLUME [ "/var/www/html/" ]

EXPOSE 80

CMD ["php-fpm"]