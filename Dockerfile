# Use PrestaShop 8.2 as base image
FROM prestashop/prestashop:8.2

# Set working directory
WORKDIR /var/www/html

# Install additional dependencies if needed
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  && rm -rf /var/lib/apt/lists/*

# Copy your repository files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/ && \
  chmod -R 755 /var/www/html/

# PrestaShop specific permissions
RUN if [ -d /var/www/html/var ]; then chmod -R 777 /var/www/html/var; fi && \
  if [ -d /var/www/html/app/config ]; then chmod -R 777 /var/www/html/app/config; fi && \
  if [ -d /var/www/html/img ]; then chmod -R 777 /var/www/html/img; fi

# Expose port 80
EXPOSE 80

# Use the default PrestaShop entrypoint
CMD ["apache2-foreground"]