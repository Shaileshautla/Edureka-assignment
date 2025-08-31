# Simple PHP + Apache container
FROM php:8.2-apache
WORKDIR /var/www/html
COPY app/ /var/www/html/
EXPOSE 80
CMD ["apache2-foreground"]
