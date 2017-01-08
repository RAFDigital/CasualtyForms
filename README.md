# RAF Museum's Casualty Forms

## Installation

First clone the repo.

    git clone git@gitlab.com:DannyEdwards/casualty-forms.git

Now cd into the project's directory and install October CMS, then run the php
installer.

    cd casualty-forms/
    curl -s https://octobercms.com/api/installer | php

Next you'll have to create an empty database, you will have to enter it's name
in the next stage. Run the set up command, then update and migrate database.

    php artisan october:install
    php artisan october:update

Set the correct file and directory owner to the apache user (www-data) if
required.

    sudo chown -R www-data:[your user] *

If you want to develop using a code editor, enable write permissions for your
system's group.

    sudo chmod -R g+w *

For the scheduled tasks to work you will need to add one entry into your crontab
file. Edit your crontab file by executing `crontab -e` then add the following
line to the end of the file.

    * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
