FROM php:8.0-apache

# Update and install necessary packages
RUN apt-get update -y && \
  apt-get install -y sendmail libpng-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  gnupg2 \
  libcurl4-openssl-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
  docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install \
    mysqli \
    pdo_mysql \
    curl \
    && a2enmod \
    rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js 20.13.1
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
  apt-get install -y nodejs

# Copy custom php.ini file
COPY php.ini /usr/local/etc/php/conf.d/

# Copy .htaccess file
COPY .htaccess /var/www/html/.htaccess

# Allow .htaccess with RewriteEngine
RUN { \
    echo '<Directory /var/www/html/>'; \
    echo '    AllowOverride All'; \
    echo '</Directory>'; \
} > /etc/apache2/conf-available/htaccess.conf && a2enconf htaccess
