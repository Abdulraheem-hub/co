# Use PrestaShop 8.2 as base image
FROM prestashop/prestashop:8.2

# Set working directory
WORKDIR /var/www/html

# Install additional dependencies
RUN apt-get update && apt-get install -y \
    git \
    rsync \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Copy repository files to a temporary directory
COPY . /tmp/repo/

# Use rsync to override files without deleting others
RUN rsync -a /tmp/repo/ /var/www/html/ --exclude='node_modules/' && \
    rm -rf /tmp/repo/

# PrestaShop specific permissions
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/ && \
    if [ -d /var/www/html/var ]; then chmod -R 777 /var/www/html/var; fi && \
    if [ -d /var/www/html/app/config ]; then chmod -R 777 /var/www/html/app/config; fi && \
    if [ -d /var/www/html/img ]; then chmod -R 777 /var/www/html/img; fi

# Expose port 80
EXPOSE 80

# Use the default PrestaShop entrypoint
CMD ["apache2-foreground"]
