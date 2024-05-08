# Use the official PHP image as the base image
FROM php:7.4.3-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y git zip unzip


# Set the working directory
WORKDIR /var/www/html/