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

# Копируем package файлы
COPY package.json package-lock.json ./

# Устанавливаем зависимости
RUN npm ci --no-audit --progress=false

# Копируем остальные файлы
COPY . .

# Фиксим права (лучше только для node_modules)
RUN chown -R node:node /var/www/node_modules

# Переключаемся на пользователя node
USER node

EXPOSE 5173
CMD ["npm", "run", "dev", "--", "--host", "--port", "5173"]