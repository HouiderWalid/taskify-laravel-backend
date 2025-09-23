FROM php:8.4-fpm

RUN usermod -u 1000 www-data

COPY docker/prod/system/cron.d /etc/cron.d
# cron must have this permission or it won't work because of security issue
RUN chmod -R 0644 /etc/cron.d
RUN chown -R root:root /etc/cron.d

#RUN sed -i 's/^deb http:/deb https:/g' /etc/apt/sources.list
RUN apt-get update
RUN apt-get install -y unzip libzip-dev nginx sudo cron zlib1g-dev nano supervisor git libmagickwand-dev --no-install-recommends
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/prod/nginx/sites-enabled /etc/nginx/sites-enabled
COPY docker/prod/nginx/nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html
COPY . .

#COPY docker/prod/php/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/prod/supervisor /etc/supervisor
COPY docker/prod/fpm/php-fpm.d /usr/local/etc/php-fpm.d

#RUN chmod +x /usr/local/bin/entrypoint.sh

#ENTRYPOINT ["entrypoint.sh"]

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
