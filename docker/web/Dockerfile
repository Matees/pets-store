FROM josefjebavy/debian-apache-php8.3-nette

RUN mkdir -p /var/www/html/app/data
RUN chmod a+w /var/www/html/app/data

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENTRYPOINT ["sh", "-c", "composer install --no-interaction --prefer-dist --optimize-autoloader && chmod -R a+w temp log && /usr/sbin/apache2ctl -D FOREGROUND"]