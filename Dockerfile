# ============================================
# Dockerfile برای فروشگاه آنلاین PHP
# ============================================

# مرحله 1: Build stage
FROM php:8.2-apache AS builder

# نصب پیش‌نیازها
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# نصب اکستنشن‌های PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# فعال‌سازی mod_rewrite
RUN a2enmod rewrite

# تنظیم مالکیت فایل‌ها
RUN chown -R www-data:www-data /var/www/html

# مرحله 2: Production stage
FROM php:8.2-apache

# نصب پیش‌نیازها و اکستنشن‌ها
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# فعال‌سازی mod_rewrite
RUN a2enmod rewrite

# کپی فایل‌های پروژه
COPY . /var/www/html/

# تنظیم دسترسی‌ها
RUN chown -R www-data:www-data /var/www/html

# تنظیم Document Root
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# پورت خروجی
EXPOSE 80

# شروع Apache
CMD ["apache2-foreground"]
