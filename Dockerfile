# Use PrestaShop 8.2 as base image
FROM prestashop/prestashop:8.2

# Set working directory
WORKDIR /var/www/html

# Install additional dependencies
RUN apt-get update && apt-get install -y \
    rsync \
    git \
    unzip && \
    rm -rf /var/lib/apt/lists/*

# Copy only the changed files from the repository to PrestaShop
COPY ./ /tmp/repo/
RUN rsync -a --ignore-errors /tmp/repo/ /var/www/html/ && \
    rm -rf /tmp/repo/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Ensure writable directories have correct permissions
RUN chmod -R 775 /var/www/html/var /var/www/html/app/config /var/www/html/img

# Expose port 80
EXPOSE 80

# Use the default PrestaShop entrypoint
CMD ["apache2-foreground"]
