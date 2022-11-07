# Documentation

Here you can learn how to install and use Galaxy.


## Installation

1. Download Galaxy from the V0LT website, or another source.
2. Install Apache, or another web server host.
    - Example: `sudo apt install apache2`
3. Install and enable PHP for your webserver.
    - Example: `sudo apt install php7.4; sudo a2enmod php7.4`
4. Move the Galaxy directory to a location on your webserver.
    - Example: `sudo mv galaxy/ /var/www/html/`
5. Grant PHP write permissions to the Galaxy directory.
    - Example: `sudo chmod 777 /var/www/html/galaxy/`
6. Ensure file uploads are enabled in `php.ini`.
7. Navigate to Galaxy in your browser.
    - Example: `http://localhost/galaxy/`
