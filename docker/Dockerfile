FROM php:7.4-apache
LABEL maintainer="Intergo Telecom Ltd"

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install the PHP extensions
RUN set -ex; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	\
	apt-get update; \
	apt-get install -y --no-install-recommends \		
		libbz2-dev \
		libgmp-dev \
		libjpeg-dev \
		libldap2-dev \
		libmcrypt-dev \
		libpng-dev \
		libpq-dev \
		libzip-dev \
		libxml2-dev \
		openssl \
	; \
	\
	docker-php-ext-configure gd --with-jpeg; \
	debMultiarch="$(dpkg-architecture --query DEB_BUILD_MULTIARCH)"; \
	docker-php-ext-configure ldap --with-libdir="lib/$debMultiarch"; \
	docker-php-ext-install -j "$(nproc)" \
		bz2 \
		gd \
		gmp \
		ldap \
		mysqli \
		pdo_mysql \
		zip \
		soap \
		bcmath \
		iconv \
		intl \
	; \
	\
# pecl will claim success even if one install fails, so we need to perform each install separately
	pecl install APCu-5.1.20; \
	pecl install redis-5.3.4; \
	\
	docker-php-ext-enable \
		apcu \
		redis \
	; \
	rm -r /tmp/pear;

VOLUME /var/www/html

# Composer.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./apache/apache2.conf /etc/apache2/apache2.conf
COPY ./apache/headers.load /etc/apache2/mods-enabled/headers.load
COPY ./php.ini /usr/local/etc/php/php.ini

# Start web server
CMD ["apache2-foreground"]

EXPOSE 80 2222
