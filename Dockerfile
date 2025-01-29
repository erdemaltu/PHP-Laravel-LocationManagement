# Resmi Laravel PHP imajını kullanarak başlıyoruz
FROM php:8.2-fpm

# Çalışma dizinini belirleyelim
WORKDIR /var/www

# Sistem bağımlılıklarını yükleyelim
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Composer'ı yükleyelim
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Projeyi kopyalayalım
COPY . .

# Composer bağımlılıklarını yükleyelim
RUN composer install --no-dev --optimize-autoloader

# Laravel için gerekli izinleri verelim
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Laravel için .env dosyası oluşturup ayarlayalım
COPY .env.example .env
RUN php artisan key:generate

# Çalıştırılacak komut
CMD ["php-fpm"]
