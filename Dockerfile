FROM dunglas/frankenphp:1.1-builder-php8.2.16

# Set Caddy server name to "http://" to serve on 80 and not 443
# Read more: https://frankenphp.dev/docs/config/#environment-variables

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    git \
    unzip \
    librabbitmq-dev \
    libpq-dev \
    supervisor \
    cron \
    rsyslog

RUN install-php-extensions \
    gd \
    pcntl \
    intl \
    opcache \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    redis \
    sodium \
    zip \
    sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the Laravel application files into the container.
COPY . .

# Start with base PHP config, then add extensions.
COPY ./.docker/php/php.ini /usr/local/etc/php/
COPY ./.docker/etc/supervisor.d/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./.docker/etc/cron.d/laravel-cron /etc/cron.d/laravel-cron

# Set permission cron file
RUN chmod 0644 /etc/cron.d/laravel-cron

# Create log directory and files
RUN mkdir -p /var/log/laravel
RUN touch /var/log/cron.log
RUN touch /var/log/laravel/scheduler.log
RUN chown -R www-data:www-data /var/log/laravel

# Configure rsyslog untuk cron logging
RUN echo 'cron.*                          /var/log/cron.log' >> /etc/rsyslog.conf

# Install PHP extensions
RUN pecl install xdebug

# Install Laravel dependencies using Composer.
RUN composer install --ignore-platform-req=ext-intl

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Run refresh
#RUN php artisan cache:clear
RUN php artisan config:clear

# Enable PHP extensions
RUN docker-php-ext-enable xdebug

# Set permissions for Laravel.
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80 443

# Apply the cron job
RUN crontab /etc/cron.d/laravel-cron

# Start Supervisor.
CMD ["/usr/bin/supervisord", "-c",  "/etc/supervisor/conf.d/supervisord.conf"]