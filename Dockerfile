# Barangay Tigbao Water District - runs on any Docker host (Render, Railway, Fly.io, a VPS ...).
FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends libpng-dev libjpeg-dev libwebp-dev libzip-dev \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" gd mysqli zip \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*

# .htaccess files must work; the host's proxy tells us when the visitor is on https
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
 && printf 'ServerName localhost\nSetEnvIf X-Forwarded-Proto "https" HTTPS=on\n' > /etc/apache2/conf-available/tigbao.conf \
 && a2enconf tigbao
RUN printf 'upload_max_filesize=8M\npost_max_size=10M\ndate.timezone=Asia/Manila\nexpose_php=Off\nsession.cookie_httponly=1\n' > /usr/local/etc/php/conf.d/tigbao.ini

COPY . /var/www/html/
# storage/ holds the consumers' payment screenshots: mount a persistent disk here on the host
RUN mkdir -p /var/www/html/storage/proofs /var/www/html/assets/uploads/profile \
 && chown -R www-data:www-data /var/www/html/storage /var/www/html/assets/uploads \
 && chmod +x /var/www/html/docker/entrypoint.sh

ENV TIGBAO_SYNC_MODE=online
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=60s CMD curl -fsS -o /dev/null "http://127.0.0.1:${PORT:-80}/health.php" || exit 1
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
