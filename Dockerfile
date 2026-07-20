FROM php:8.3-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mbstring

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/data /var/www/html/assets/media/projects 2>/dev/null; true
RUN chmod -R 775 /var/www/html/data /var/www/html/assets/media/projects 2>/dev/null; true

EXPOSE 80