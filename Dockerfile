FROM php:8.3-fpm

RUN apt-get update && apt-get install -y git libzip-dev unzip p7zip-full

RUN apt-get update && apt-get install -y git libpq-dev librabbitmq-dev librabbitmq4


RUN docker-php-ext-install pdo_mysql pdo_pgsql


RUN pecl install amqp && docker-php-ext-enable amqp

RUN docker-php-ext-install bcmath \
    && docker-php-ext-install sockets


RUN apt-get update && apt-get install -y cron

COPY run_consumer.sh /var/www/run_consumer.sh

RUN chmod +x  /var/www/run_consumer.sh

RUN (crontab -l ; echo "*/15 * * * * /run_consumer.sh") | crontab

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www