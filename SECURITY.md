# Security

Here you can learn about Galaxy's security measures.


## Disclaimer

While Galaxy is designed to be as secure as possible, there's no such thing as perfection. Allowing file uploads to your server is inherently dangerous, and you should be aware of the risks before installing and using Galaxy. It is highly recommended that you only allow trusted users to access your Galaxy instance.


## Overview

Below is an overview of the security features included with Galaxy.

1. File uploading
    - The maximum allowable file size for uploads can be configured by the administrator of a Galaxy instance.
    - An extension whitelist can be configured by the administrator of a Galaxy instance.
    - File names are limited to a certain number of characters.
    - File names are limited to alphanumeric characters.
2. File downloading
    - File download requests are limited to alphanumeric characters and periods.
    - File names are not permitted to have multiple sequential periods.
    - The existence of a file is checked before a download starts.
    - If the requested file is actually a directory, the download will be cancelled.
3. File deleting
    - File deletion requests are limited to alphanumeric characters and periods.
    - File names are not permitted to have multiple sequential periods.
    - The existence of a file is checked before it is deleted.
    - If the requested file is actually a directory, the download will be cancelled.
    - After pressing the 'Remove' button, a confirmation timestamp is generated, if the file is not confirmed for deletion within a certain time frame, the code becomes invalided.
        - This makes it much more difficult for a threat actor to trick a user into deleting a file, since they would have to guess the exact time they open the link.
        - If the confirmation timestamp is in the future, then a notice will be displayed to the user indicating that someone might be trying to manipulate them.
