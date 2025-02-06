# Usar a imagem oficial PHP 8.1 com Apache
FROM php:8.1-apache

# Instalar dependências necessárias para o PHP (se precisar de alguma extensão como GD, PDO, etc.)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    redis-server \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalar a extensão do Redis para o PHP
RUN pecl install redis && docker-php-ext-enable redis

# Habilitar o módulo mod_rewrite do Apache
RUN a2enmod rewrite

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar todos os arquivos do projeto para o contêiner
COPY . /var/www/html/

# Configurar o Apache para escutar na porta 80
EXPOSE 80

# Iniciar o servidor Apache
CMD ["apache2-foreground"]
