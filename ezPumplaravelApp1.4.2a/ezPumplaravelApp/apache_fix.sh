#!/bin/bash

# Path to the Apache configuration file
APACHE_CONF="/etc/apache2/apache2.conf"

# Directory configuration to add
DIRECTORY_CONF="<Directory /var/www/html>
    AllowOverride All
</Directory>"

# Check if the directory configuration already exists
if grep -q "<Directory /var/www/html>" "$APACHE_CONF"; then
    echo "Directory configuration already exists in $APACHE_CONF"
else
    # Append the directory configuration to the Apache configuration file
    echo "$DIRECTORY_CONF" | sudo tee -a "$APACHE_CONF"
    echo "Directory configuration added to $APACHE_CONF"

    # Restart Apache to apply changes
    sudo systemctl restart apache2
    echo "Apache restarted"
fi
