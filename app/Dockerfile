FROM php:8.2-fpm-alpine

RUN apk update \
    && apk add --no-cache --update \
    supervisor \ 
    wget \
    libpq-dev \
    icu-dev \
    libxml2-dev \
    linux-headers \
    && docker-php-ext-install \
    intl \ 
    pdo \ 
    pdo_mysql \
    sockets \
    && rm -rf \
    /var/cache/* \
    /usr/share/apk \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i -e 's/^variables_order = "GPCS"/variables_order = "EGPCS"/g' "$PHP_INI_DIR/php.ini" \
    && mkdir /var/www/app

ENV APP_DIR=/var/www/app
ENV PATH=${APP_DIR}/src/bin:${APP_DIR}/vendor/bin:/$PATH

COPY ./ ${APP_DIR}

RUN wget --progress=dot:gig -O - https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR ${APP_DIR}

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    && composer clearcache \
    && rm -rf /var/www/html

ENTRYPOINT ["supervisord", "--configuration", "/etc/supervisor.conf"]
