FROM php:8.1-apache

# Install system dependencies, Node.js, NPM & PostgreSQL extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache Mod_Rewrite
RUN a2enmod rewrite

# Set Working Directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Build Vite Frontend Assets
RUN npm install && npm run build

# Set Apache Document Root to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x /var/www/html/entrypoint.sh

EXPOSE 80

CMD ["/var/www/html/entrypoint.sh"]
