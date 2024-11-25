# Use PrestaShop 8.2 as base image
FROM prestashop/prestashop:8.2

# Set working directory
WORKDIR /var/www/html

# Install additional dependencies if needed
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  && rm -rf /var/lib/apt/lists/*

# Create a directory for your custom files
RUN mkdir -p /var/www/html/custom_files

# Copy only your custom files
COPY ./themes/ /var/www/html/themes/
COPY ./modules/ /var/www/html/modules/
COPY ./override/ /var/www/html/override/
COPY ./config/ /var/www/html/config/
COPY ./app/config/ /var/www/html/app/config/
COPY ./mails/ /var/www/html/mails/
# Add other directories you need to update

# Set proper permissions for copied files
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