# Imagen base de PHP con Composer
FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Establecer directorio de la aplicación
WORKDIR /var/www/html

# Copiar proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias Node y compilar assets
RUN apt-get install -y nodejs npm
RUN npm install && npm run build

# Generar APP_KEY
RUN php artisan key:generate --force

# Exponer puerto
EXPOSE 8000

# Start Laravel
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]

