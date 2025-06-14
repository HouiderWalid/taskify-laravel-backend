#!/bin/bash

if [ ! -d "/var/www/html/vendor" ]; then
    composer install

fi

chown -R www-data:www-data /var/www/html
php artisan optimize

# Run the command passed as arguments to the entrypoint
exec "$@"
