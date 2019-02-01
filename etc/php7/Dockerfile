FROM php:7.1-cli
ARG TIMEZONE

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer --version

RUN apt-get update && apt-get install -y \
    git \
    wget

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
RUN "date"

RUN echo 'alias pu="php bin/phpunit"' | tee ~/.bashrc

ENTRYPOINT ["docker-php-entrypoint"]
CMD ["php", "-a"]

WORKDIR /code