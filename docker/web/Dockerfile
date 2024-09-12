FROM josefjebavy/debian-apache-php8.3-nette

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENTRYPOINT ["sh", "-c", "composer install --no-interaction --prefer-dist --optimize-autoloader && mkdir temp log && chmod -R a+w temp log && /usr/sbin/apache2ctl -D FOREGROUND"]