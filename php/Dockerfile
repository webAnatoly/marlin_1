FROM php:7.3.8-apache
# ==================
# FROM php:7.3.9

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        libxml2-dev \
	&& docker-php-ext-install -j$(nproc) iconv \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install soap

COPY php.ini $PHP_INI_DIR/conf.d/
COPY apache2.conf /etc/apache2/apache2.conf

# You'll want to verify that the mod is enabled - just look for rewrite.load symlink in /etc/apache2/mods-enabled. If not, run sudo a2enmod rewrite to enable it. Restart apache using sudo service apache2 restart and provided your .htaccess syntax is correct this will work. https://askubuntu.com/questions/64454/how-do-i-enable-the-mod-rewrite-apache-module-for-ubuntu-11-04
RUN a2enmod rewrite && service apache2 restart

# Install xdebug and do some config
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini


# ==================
# COPY ./ /etc/apache2/
# COPY my-apache2.conf $APACHE_CONFDIR/

# RUN rm -f /etc/apache2/apache2.conf
# ADD ./my-apache2.conf /etc/apache2/apache2.conf

# RUN sed -ri -e '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf


# Другой образ
# FROM php:7.2.7-fpm-alpine3.7
# RUN apk update; \
#     apk upgrade;
# RUN docker-php-ext-install mysqli

# RUN apk update; \
#     apk upgrade;
