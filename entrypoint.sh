#!/bin/bash
set -e

# Installation check file
INSTALL_CHECK_FILE="/var/www/html/installation_status/installed.txt"

# Function to ensure directories exist
create_directories() {
  echo "Creating necessary directories..."
  mkdir -p /var/www/html/themes
  mkdir -p /var/www/html/modules
  mkdir -p /var/www/html/override
  mkdir -p /var/www/html/config
  mkdir -p /var/www/html/mails
  mkdir -p /var/www/html/app/config
}

# Function to apply custom files
apply_custom_files() {
  echo "Applying custom files..."

  # First ensure directories exist
  create_directories

  # Debug: List contents of temporary custom directories
  echo "Contents of /tmp/custom:"
  ls -la /tmp/custom/

  # Copy custom files from temporary location with verbose output
  echo "Copying themes..."
  cp -rfv /tmp/custom/themes/* /var/www/html/themes/ 2>/dev/null || true

  echo "Copying modules..."
  cp -rfv /tmp/custom/modules/* /var/www/html/modules/ 2>/dev/null || true

  echo "Copying override..."
  cp -rfv /tmp/custom/override/* /var/www/html/override/ 2>/dev/null || true

  echo "Copying config..."
  cp -rfv /tmp/custom/config/* /var/www/html/config/ 2>/dev/null || true

  echo "Copying mails..."
  cp -rfv /tmp/custom/mails/* /var/www/html/mails/ 2>/dev/null || true

  echo "Copying app/config..."
  cp -rfv /tmp/custom/app/config/* /var/www/html/app/config/ 2>/dev/null || true

  # Debug: List contents after copying
  echo "Contents of PrestaShop directories after copying:"
  echo "Themes:"
  ls -la /var/www/html/themes/
  echo "Modules:"
  ls -la /var/www/html/modules/
  echo "Override:"
  ls -la /var/www/html/override/

  # Set proper permissions
  echo "Setting permissions..."
  chown -R www-data:www-data /var/www/html/
  chmod -R 755 /var/www/html/

  # PrestaShop specific permissions
  chmod -R 777 /var/www/html/var || true
  chmod -R 777 /var/www/html/app/config || true
  chmod -R 777 /var/www/html/img || true

  echo "Custom files application completed."
}

# Check if PrestaShop is already installed
if [ ! -f "$INSTALL_CHECK_FILE" ]; then
  echo "Fresh installation detected..."

  # Run the original PrestaShop installation
  /tmp/docker_run.sh &

  # Wait for the installation to complete (you might need to adjust the sleep time)
  sleep 30

  # Create installation check file
  mkdir -p "$(dirname "$INSTALL_CHECK_FILE")"
  touch "$INSTALL_CHECK_FILE"

  # Apply custom files after installation
  apply_custom_files
else
  echo "Existing installation detected..."
  # Only apply custom files
  apply_custom_files
fi

# Execute the passed command (usually apache2-foreground)
exec "$@"

#Testinggggg