FROM php:8.1-cli

# Install system dependencies, Node.js, NPM & PostgreSQL extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /app

COPY . .

# Install Composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Build Vite Frontend Assets
RUN npm install && npm run build

# Make entrypoint executable
RUN chmod +x /app/entrypoint.sh

ENV PORT 8000
EXPOSE 8000

CMD ["sh", "/app/entrypoint.sh"]
