#!/bin/bash
set -e

# Installation check file
INSTALL_CHECK_FILE="/var/www/html/installation_status/installed.txt"

# Function to apply custom files
apply_custom_files() {
  echo "Applying custom files..."

  # Copy custom files from temporary location
  cp -rf /tmp/custom/themes/* /var/www/html/themes/ 2>/dev/null || true
  cp -rf /tmp/custom/modules/* /var/www/html/modules/ 2>/dev/null || true
  cp -rf /tmp/custom/override/* /var/www/html/override/ 2>/dev/null || true
  cp -rf /tmp/custom/config/* /var/www/html/config/ 2>/dev/null || true
  cp -rf /tmp/custom/mails/* /var/www/html/mails/ 2>/dev/null || true
  cp -rf /tmp/custom/app/config/* /var/www/html/app/config/ 2>/dev/null || true

  # Set proper permissions
  chown -R www-data:www-data /var/www/html/
  chmod -R 755 /var/www/html/

  # PrestaShop specific permissions
  chmod -R 777 /var/www/html/var || true
  chmod -R 777 /var/www/html/app/config || true
  chmod -R 777 /var/www/html/img || true
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