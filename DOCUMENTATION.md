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
6. Ensure file uploads are enabled and configured in `php.ini`.
    - Set `file_uploads` to `on`.
    - Set `upload_max_filesize` and `post_max_size` to the maximum file size you expect to process.
7. Navigate to Galaxy in your browser.
    - Example: `http://localhost/galaxy/`


## Configuration

1. Click the 'Configure' button.
2. Adjust values as desired.
    - 'Theme' specifies the aesthetic theme used by Galaxy.
    - 'Storage Location' specifies where Galaxy will store uploaded files.
        - This directory should be inaccessible directly over the internet, and should be writable to PHP.
    - 'Credit Level' determines how much credit will be given to V0LT on the main page.
    - 'Admin User' specifies the administrative user of the instance.
        - The admin is the only user who can modify the configuration.
        - Make sure to set this value before publishing your Galaxy instance.
            - If this setting is left blank, then anyone with access to Galaxy can change the configuration.
    - 'Login Page' specifies the authentication login page.
        - This can be a DropAuth installation, or a custom authentication system.
    - 'Logout Page' specifies the authentication logout page.
    - 'Allowed Extensions' specifies which file extensions are permitted to be uploaded.
        - If this is left blank, all extensions will be blocked.
        - Use a comma to separate multiple file extensions.
    - 'Allowed Users' specifies which users are permitted to access Galaxy.
        - If this is left blank, all users will be allowed.
        - Use a comma to separate multiple file extensions.
    - 'Max File Size' determines the maxmimum individual allowed file size.
3. Submit the configuration changes.


## Usage

### Uploading

1. Sign in if you haven't already.
2. Click the 'Upload' button on the main page.
3. Select a file to upload.
4. Optionally, specify a description.
5. Optionally, list users that are permitted to download this file.
6. Press the 'Upload' button to submit the file.


### Viewing

1. Sign in if you haven't already.
2. Click the 'View' button on the main page.
3. All files will be listed.
    - Note that files that are shared with you will not automatically appear.
    - Each file will have the following information.
        - The 'Title' is the name of the file as it was uploaded.
        - The 'Description' is the file description entered when the file was uploaded.
        - The 'Shared' is the list of users who can download this file, given the link.
        - The 'Size' is the size of the file.


### Removing

1. From the 'View' page, press 'Remove' next to a file.
2. On the following page, verify the file information, then press 'Confirm' to confirm the deletion.


### Download

1. From the 'View' page, press 'Download' next to a file.
2. Provided you have permission to download this file, the download will begin.


### Sharing

1. From the 'View' page, copy the 'Download' link.
2. Send this link to the user you wish to share the file with.
3. Provided the recipient is in the list of 'authorized user', they will be able to download the file by logging in, and opening the link.
