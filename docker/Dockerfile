# Usando a imagem PHP 8.3 FPM como base
FROM php:8.3-fpm

# Instalar dependências essenciais e ferramentas de compilação
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    libzip-dev \
#    libpng-dev \
#    libonig-dev \
#    libxml2-dev \
#    libicu-dev \
#    libcurl4-openssl-dev \
#    libssl-dev \
#    libjpeg-dev \
#    libfreetype6-dev \
#    pkg-config \
#    libjpeg62-turbo-dev \
#    libpng-dev \
#    libwebp-dev \
#    libxpm-dev \
#    build-essential \
#    autoconf \
#    gcc \
#    make \
#    re2c \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões do PHP
RUN docker-php-ext-configure zip
#    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
#    docker-php-ext-install \
#    bcmath \
#    ctype \
#    fileinfo \
#    mbstring \
#    openssl \
#    pdo \
#    pdo_mysql \
#    tokenizer \
#    xml \
#    zip \
#    intl \
#    gd


# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ajuste de permissões
RUN groupadd -g 1000 resoluto && \
    useradd -u 1000 -g resoluto -m -s /bin/bash resoluto && \
    chown -R resoluto:resoluto /var/www/html

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos para o container
COPY . /var/www/html

# Ajustar permissões dos arquivos copiados
RUN chown -R resoluto:resoluto /var/www/html

# Comando de entrada para PHP-FPM
CMD ["php-fpm"]
