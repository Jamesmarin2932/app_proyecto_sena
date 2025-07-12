# Usa una imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crea y configura el directorio de trabajo
WORKDIR /var/www/html

# Copia todos los archivos al contenedor
COPY . .

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Genera clave de la app y cachea config y rutas
RUN php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache

# Exponer puerto para el servidor embebido
EXPOSE 8000

# Comando por defecto al iniciar
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
