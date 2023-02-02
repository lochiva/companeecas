FROM php:7.4.2-apache-buster

ARG DEBIAN_FRONTEND=noninteractive
# An IDE key has to be set, but anything works, at least for PhpStorm and VS Code...
ENV XDEBUG_CONFIG="xdebug.idekey='VSCODE'"

# Update
RUN apt-get -y update --fix-missing && \
    apt-get upgrade -y && \
    apt-get --no-install-recommends install -y \
    nano \
    wget \
    dialog \
    libsqlite3-dev \
    libsqlite3-0 \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    libicu-dev \
    apt-utils \
    build-essential \
    git \
    curl \
    libonig-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    zip \
    openssl \
    libmagickwand-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    unzip \
    libxml2-dev \
    libxslt1-dev \
    libsodium-dev \
    ssl-cert && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer  --version=1.10.21 && \
    curl -sL https://deb.nodesource.com/setup_14.x | bash && \
    apt-get --no-install-recommends install -y nodejs && \
    npm install workbox-cli --global
    

# Install redis
RUN pecl install xdebug-2.9.7 imagick redis-5.1.1 && \
    docker-php-ext-enable xdebug imagick redis && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

RUN echo "xdebug.remote_enable=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    # host.docker.internal does not work on Linux yet: https://github.com/docker/for-linux/issues/264
    # Workaround:
    # ip -4 route list match 0/0 | awk '{print $3 " host.docker.internal"}' >> /etc/hosts \
    && echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#RUN wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.3/wkhtmltox-0.12.3_linux-generic-amd64.tar.xz
#RUN tar vxf wkhtmltox-0.12.3_linux-generic-amd64.tar.xz
#RUN cp wkhtmltox/bin/wk* /usr/local/bin/
#RUN cp wkhtmltox/bin/wk* /usr/bin/

# Other PHP7 Extensions
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install pdo_sqlite && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install curl && \
    docker-php-ext-install tokenizer && \
    docker-php-ext-install json && \
    docker-php-ext-install zip && \
    docker-php-ext-install -j$(nproc) intl && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install exif && \
    docker-php-ext-install soap && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install xsl && \
    docker-php-ext-install sockets && \
    docker-php-ext-install gettext && \
    docker-php-ext-install sodium && \
    docker-php-ext-install xmlrpc

# Enable apache modules
RUN a2enmod rewrite headers ssl

# Cleanup
RUN rm -rf /usr/src/*
