FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar todos los archivos al contenedor
COPY . .

# Copiar un archivo .env por defecto
COPY .env.example .env

# Instalar dependencias y preparar Laravel
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan config:clear && \
    php artisan key:generate && \
    php artisan storage:link

EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
