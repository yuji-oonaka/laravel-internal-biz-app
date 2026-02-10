FROM php:8.3-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 一般ユーザー作成
RUN groupadd -g $GROUP_ID developer && \
    useradd -u $USER_ID -g developer -m developer

WORKDIR /var/www/html
USER developer