FROM composer:2.4.2

ENV COMPOSERUSER=laravel
ENV COMPOSERGROUP=laravel

#php extension
RUN docker-php-ext-install pdo pdo_mysql exif

RUN adduser -g ${COMPOSERGROUP} -s /bin/sh -D ${COMPOSERUSER}
