FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Copia Composer desde otra imagen
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el código de tu aplicación al contenedor
COPY . .

# Instala dependencias PHP sin scripts ni paquetes de desarrollo
RUN composer install --no-dev --optimize-autoloader --no-scripts && \
    php artisan config:clear && \
    php artisan key:generate

# Expone el puerto
EXPOSE 10000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
