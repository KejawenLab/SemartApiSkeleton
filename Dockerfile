FROM php:cli-alpine

MAINTAINER Muhammad Surya Ihsanuddin<surya.iksanudin@gmail.com>

# Install Dependencies
RUN apk update
RUN apk add --no-cache supervisor vim autoconf gcc make g++ inotify-tools bash git
RUN apk add --no-cache libzip-dev curl-dev icu-dev oniguruma-dev imap-dev postgresql-dev
RUN apk add --no-cache libpng-dev openssl-dev nghttp2-dev hiredis-dev rabbitmq-c-dev

## Install Pecl Extension
RUN pecl channel-update pecl.php.net
RUN pecl install igbinary inotify
RUN pecl install https://github.com/0x450x6c/php-amqp/raw/7323b3c9cc2bcb8343de9bb3c2f31f6efbc8894b/amqp-1.10.3.tgz
RUN pecl install swoole --enable-sockets --enable-openssl --enable-async-redis --enable-http2 --enable-mysqlnd
RUN pecl bundle redis && cd redis && phpize && ./configure --enable-redis-igbinary && make && make install
RUN docker-php-ext-enable igbinary redis swoole inotify amqp

# Install PHP Core Extensions
RUN docker-php-ext-install curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl iconv sockets
RUN docker-php-ext-enable curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl iconv sockets

# Install Composer
ADD docker/composer.sh /composer.sh
RUN chmod a+x /composer.sh
RUN /composer.sh && mv composer.phar /usr/local/bin/composer && chmod a+x /usr/local/bin/composer
RUN rm -f /composer.sh

# Cleaning
RUN docker-php-source delete
RUN rm -r /tmp/* /var/cache/*

# Here we go
ADD docker/supervisord.conf /etc/supervisord.conf
ADD docker/start.sh /start.sh
ADD docker/opcache.ini /etc/opcache.ini
RUN chmod +x /start.sh

WORKDIR /semart

EXPOSE 9501

CMD ["/start.sh"]
