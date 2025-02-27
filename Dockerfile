FROM php:8.2-apache

# Installer les extensions PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Enable Apache modules
RUN a2enmod rewrite

# Set the correct DocumentRoot
RUN sed -i "s|/var/www/html|/var/www/html/public|g" /etc/apache2/sites-available/000-default.conf

# Restart Apache when the container starts
CMD ["apache2-foreground"]
