FROM php:7.2-apache
COPY apache-config/000-default.conf /etc/apache2/sites-available/
RUN a2enmod rewrite
COPY supporting/ /var/www/
COPY app/ /var/www/html/
