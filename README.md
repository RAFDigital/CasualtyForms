# Install October CMS

First run the php installer.

    curl -s https://octobercms.com/api/installer | php

Set the correct file and directory owner to the apache user (www-data).

    chown -R www-data:root *

Next you'll have to create an empty database, you will have to enter it's name
in the next stage.

Then run the set up command.

    php artisan october:install

Install the following plugins at '/backend/system/updates/install':

* User
* Builder