# 1️⃣ Imagen base PHP-FPM con extensiones necesarias
FROM php:8.2-fpm

# 2️⃣ Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    zip unzip git curl \
    nginx \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql mbstring bcmath

# 3️⃣ Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# 4️⃣ Establecer directorio de la app
WORKDIR /var/www/html

# 5️⃣ Copiar proyecto
COPY . .

# 6️⃣ Instalar dependencias PHP y Node
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 7️⃣ Generar APP_KEY
RUN php artisan key:generate --force

# 8️⃣ Configurar Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY ./nginx.conf /etc/nginx/sites-available/laravel.conf
RUN ln -s /etc/nginx/sites-available/laravel.conf /etc/nginx/sites-enabled/

# 9️⃣ Exponer el puerto
EXPOSE 8080

# 10️⃣ Start PHP-FPM y Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
