FROM php:8.2-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git curl \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece directorio de trabajo
WORKDIR /var/www

# Copia archivos del proyecto
COPY . .

# Instala dependencias y prepara Laravel
RUN composer install --no-dev --optimize-autoloader \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan config:cache \
    && php artisan route:cache

# Comando para iniciar Laravel en Render
CMD php artisan serve --host=0.0.0.0 --port=$PORT
