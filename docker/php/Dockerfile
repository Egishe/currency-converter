ARG IMAGE_PHP="php:8.2-fpm-buster"
FROM ${IMAGE_PHP}


# Install composer
ARG COMPOSER_CACHE_DIR="/opt/volumes/composer"
COPY --from=composer:2.4.2 /usr/bin/composer /usr/bin/composer
RUN mkdir -p -m 0777 ${COMPOSER_CACHE_DIR}
VOLUME ${COMPOSER_CACHE_DIR}
ENV COMPOSER_CACHE_DIR=${COMPOSER_CACHE_DIR}
