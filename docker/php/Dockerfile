FROM php:7.2-fpm-alpine

RUN addgroup -S -g 1000 iceberg && adduser -S -D -H -u 1000 iceberg -G iceberg && \
    apk upgrade --update && apk add \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include --with-jpeg-dir=/usr/include && \
    docker-php-ext-install -j$(nproc) pdo_mysql bcmath gd exif
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER iceberg
