FROM php:8.3-fpm AS build

COPY --from=composer:2.8.1 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update
RUN apt-get install -y nano wget iputils-ping git unzip zip

RUN echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-errors.ini
RUN echo "display_startup_errors=Off" >> /usr/local/etc/php/conf.d/docker-php-ext-errors.ini
RUN echo "display_errors=Off" >> /usr/local/etc/php/conf.d/docker-php-ext-errors.ini
RUN echo "log_error=On" >> /usr/local/etc/php/conf.d/docker-php-ext-errors.ini
RUN echo "expose_php=Off" >> /usr/local/etc/php/conf.d/docker-php-ext-config.ini

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.idekey = \"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.mode = debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port = 9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.discover_client_host = false" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log_level = 0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini