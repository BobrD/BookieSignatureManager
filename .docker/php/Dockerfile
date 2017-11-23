FROM php:7.0.25-cli

RUN \
    apt-get update && \
    apt-get install -y --no-install-recommends git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN mv composer.phar /usr/local/bin/composer

RUN groupadd -g 1000 user
RUN useradd -lm -u 1000 -g 1000 user

USER 1000

COPY php.ini /usr/local/etc/php

WORKDIR /var/www/signature.dev