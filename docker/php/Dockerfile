FROM php:7.2-fpm
RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client \
    && apt-get install libmagickwand-dev -y --no-install-recommends \
    && pecl install mcrypt-1.0.1 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install fileinfo mbstring pdo_mysql mysqli \
    && apt-get install -y zip unzip \
    && apt-get install -y vim curl \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install exif

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod 777 /usr/local/bin/composer

RUN cd /var/www && \
    curl https://get.fuelphp.com/oil | sh

RUN echo "file_uploads = On\n" \
         "memory_limit = 128M\n" \
         "upload_max_filesize = 10M\n" \
         "post_max_size = 10M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www
