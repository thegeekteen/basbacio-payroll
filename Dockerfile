FROM php:7.4-apache

# Install required extensions and enable mod_rewrite
RUN apt-get update \
    && apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy PHAR file to the container
COPY ./ /var/www/html

RUN  mkdir -p /var/www/html/sessions && chmod 777 /var/www/html/sessions && \
    echo "session.save_path = \"/var/www/html/sessions\"" > /usr/local/etc/php/conf.d/session.ini && \
    chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]