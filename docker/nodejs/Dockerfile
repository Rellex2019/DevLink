FROM node:20-bullseye-slim

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    vim \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Создаем рабочую директорию
WORKDIR /var/www

# Копируем только package файлы (для кеширования слоев)
COPY package.json package-lock.json ./

# Устанавливаем зависимости от имени root
RUN npm ci --no-audit --progress=false

# Копируем остальные файлы проекта
COPY . .

# Фиксим права для node_modules (без рекурсии для всего /var/www)
RUN chown -R node:node /var/www/node_modules

# Создаем необходимые директории Laravel
RUN mkdir -p /var/www/storage/framework/{cache,sessions,views} \
    && chown -R node:node /var/www/storage

# Переключаемся на пользователя node
USER node

EXPOSE 5173
CMD ["npm", "run", "dev", "--", "--host", "--port", "5173"]