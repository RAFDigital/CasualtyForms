# Install October CMS

First move into the project's directory or create one, then run the php installer.

    cd /project/directory
    curl -s https://octobercms.com/api/installer | php

Next you'll have to create an empty database, you will have to enter it's name
in the next stage.

Then run the set up command, then update and migrate database.

    php artisan october:install
    php artisan october:update

Set the correct file and directory owner to the apache user (www-data) if required.

    chown -R www-data:root *


Install the following plugins at '/backend/system/updates/install':

* User
* Builder
