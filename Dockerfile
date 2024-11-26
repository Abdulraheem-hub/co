# Use PrestaShop 8.2 as base image
FROM prestashop/prestashop:8.2

# Set working directory
WORKDIR /var/www/html

# Install necessary packages
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  && rm -rf /var/lib/apt/lists/*

# Create a directory for checking installation status
RUN mkdir -p /var/www/html/installation_status

# Copy your custom files from the repository
COPY ./themes/ /tmp/custom/themes/
COPY ./modules/ /tmp/custom/modules/
COPY ./override/ /tmp/custom/override/
COPY ./config/ /tmp/custom/config/
COPY ./mails/ /tmp/custom/mails/
COPY ./app/config/ /tmp/custom/app/config/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/ /tmp/custom/ && \
  chmod -R 755 /var/www/html/ /tmp/custom/

# PrestaShop specific permissions
RUN chmod -R 777 /var/www/html/var || true && \
  chmod -R 777 /var/www/html/app/config || true && \
  chmod -R 777 /var/www/html/img || true

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

# Expose port 80
EXPOSE 80

# Use custom entrypoint
ENTRYPOINT ["custom-entrypoint.sh"]
CMD ["apache2-foreground"]