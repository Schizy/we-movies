FROM php:8.3-fpm-alpine

RUN apk add --update \
    $PHPIZE_DEPS \
    curl \
    git \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_mysql

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
     install-php-extensions @composer amqp xdebug xsl


# Tester les 2 lol
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


ADD php.ini /etc/php/conf.d/
ADD php.ini /etc/php/cli/conf.d/
ADD php.ini /usr/local/etc/php
ADD php-fpm.conf /etc/php/php-fpm.d/

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm", "-F"]
