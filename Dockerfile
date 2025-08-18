# -----------------------------
# Imagen base de PHP con FPM
# -----------------------------
FROM php:8.2-fpm

# -----------------------------
# Instalar dependencias del sistema y extensiones PHP
# -----------------------------
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql bcmath

# -----------------------------
# Instalar Composer
# -----------------------------
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# -----------------------------
# Directorio de trabajo
# -----------------------------
WORKDIR /var/www/html

# -----------------------------
# Copiar proyecto
# -----------------------------
COPY . .

# -----------------------------
# Instalar dependencias PHP
# -----------------------------
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# Instalar dependencias Node y compilar assets
# -----------------------------
RUN npm install && npm run build

# -----------------------------
# Generar APP_KEY si no existe
# -----------------------------
RUN php artisan key:generate --force

# -----------------------------
# Puerto dinámico para Railway
# -----------------------------
ENV PORT=${PORT}
EXPOSE ${PORT}

# -----------------------------
# Iniciar Laravel en el puerto dinámico
# -----------------------------
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
