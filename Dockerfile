# Usa una imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones necesarias para PostgreSQL y ZIP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto al contenedor
COPY . .

# Instala las dependencias PHP de producción (sin las de desarrollo)
RUN composer install --no-dev --optimize-autoloader

# Elimina el archivo .env (Render usará variables de entorno inyectadas)
RUN rm -f .env

# Genera la clave de la aplicación y cachea configuración
RUN php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache

# Expone el puerto 8000 para php artisan serve (opcional en Render)
EXPOSE 8000

# Comando por defecto: aplicar migraciones y servir la app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
