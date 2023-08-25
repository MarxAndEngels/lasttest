FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ENV USERNAME=www-data
ENV APP_HOME=/var/www/html
ARG HOST_UID
ARG HOST_GID

# Install dependencies
RUN apt-get update && apt-get install -y \
      procps \
      nano \
      git \
      unzip \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      supervisor \
      sudo \
      libpng-dev \
      libjpeg-dev \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libmcrypt-dev \
      libgd-dev \
      libwebp-dev \
      jpegoptim optipng pngquant gifsicle \
      libzip-dev && \
      docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd && \
      docker-php-ext-configure intl && \
      docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
      docker-php-ext-configure pcntl --enable-pcntl && \
      docker-php-ext-install \
        pdo_mysql \
        exif \
        intl \
        zip \
        gd \
        opcache \
        pcntl && \
        rm -rf /tmp/* && \
            rm -rf /var/list/apt/* &&\
            rm -rf /var/lib/apt/lists/* &&\
            apt-get clean

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
   docker-php-ext-enable redis

COPY ./_docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

COPY --chown=root:root ./_docker/supervisor/supervisor.conf /etc/supervisor/supervisor.conf


# Create system user to run Composer and Artisan Commands

RUN mkdir -p $APP_HOME/public && \
    mkdir -p /home/$USERNAME && chown $USERNAME:$USERNAME /home/$USERNAME \
    && usermod -o -u $HOST_UID $USERNAME -d /home/$USERNAME \
    && groupmod -o -g $HOST_GID $USERNAME \
    && chown -R ${USERNAME}:${USERNAME} $APP_HOME

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# set working directory
WORKDIR $APP_HOME

USER ${USERNAME}

COPY --chown=${USERNAME}:${USERNAME} . $APP_HOME/

#RUN  chmod -R 755 /var/www/html
# Copy existing application directory permissions
#RUN php artisan config:cache && \


# Expose port 9000 and start php-fpm server
#EXPOSE 9000
#
#CMD ["php-fpm"]
USER root
#Supervisor
#COPY ./_docker/supervisor/supervisor.conf /etc/supervisor/supervisor.conf

