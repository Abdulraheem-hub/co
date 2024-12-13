# Use PrestaShop 8.2 as base image
FROM prestashop/prestashop:8.2

# Set working directory
WORKDIR /var/www/html

# Install necessary packages
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  && rm -rf /var/lib/apt/lists/*

# Create all necessary directories
RUN mkdir -p /var/www/html/installation_status \
  /tmp/custom/themes \
  /tmp/custom/modules \
  /tmp/custom/override \
  /tmp/custom/config \
  /tmp/custom/mails \
  /tmp/custom/app/config

# Copy your custom files from the repository
COPY ./themes/ /tmp/custom/themes/
COPY ./modules/ /tmp/custom/modules/
COPY ./override/ /tmp/custom/override/
COPY ./config/ /tmp/custom/config/
COPY ./mails/ /tmp/custom/mails/
COPY ./app/config/ /tmp/custom/app/config/

# Debug: List contents of copied files
RUN echo "Listing contents of /tmp/custom:" && \
  ls -la /tmp/custom/* && \
  echo "Listing contents of /tmp/custom/override:" && \
  ls -la /tmp/custom/override/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/ /tmp/custom/ && \
  chmod -R 755 /var/www/html/ /tmp/custom/

# Create and set permissions for PrestaShop directories
RUN mkdir -p /var/www/html/var \
  /var/www/html/app/config \
  /var/www/html/img \
  /var/www/html/override && \
  chmod -R 777 /var/www/html/var && \
  chmod -R 777 /var/www/html/app/config && \
  chmod -R 777 /var/www/html/img && \
  chmod -R 777 /var/www/html/override

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

# Expose port 80
EXPOSE 80

# Use custom entrypoint
ENTRYPOINT ["custom-entrypoint.sh"]
CMD ["apache2-foreground"]