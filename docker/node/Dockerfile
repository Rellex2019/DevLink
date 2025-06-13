FROM node:20.13.1-bullseye-slim

# Установка зависимостей
RUN apt-get update && apt-get install -y curl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# Установка npm пакетов
COPY package.json package-lock.json ./
RUN npm ci --no-audit

# Копирование приложения
COPY . .

# Сборка production версии
RUN npm run build

# Права для Vite
RUN chown -R node:node /var/www/public/build \
    && chmod -R 775 /var/www/public/build

USER node