# RAF Museum's Casualty Forms

Casualty Forms is a volunteer transcription platform and consumer front-end. It
allows for volunteer users to engage in a transcription effort of over 30,000
medical forms from World War I.

The platform is build on [OctoberCMS](octobercms.com), all volunteer user
administration and other administrative activities are handled by the OctoberCMS
"back end". To access the back end, go to `/backend` from your application and
enter your admin username and password. Admin users are set up by the Superadmin,
owner, or developer.

## Installation

### Core

First clone the repo.

    git clone git@gitlab.com:DannyEdwards/casualty-forms.git

Now `cd` into the project's directory and install October CMS, then run the PHP
installer.

    cd casualty-forms/
    curl -s https://octobercms.com/api/installer | php

*Note: Here you may get an error about a ZipArchive extention missing. To install it, run `apt-get install php7.0-zip`*

Next you'll have to create an empty database, you will have to enter it's name
in the next stage. Run the set up command, then update and migrate database.

    php artisan october:install
    php artisan october:update

Check the site is up and running, and that links work. If you get errors you will probably have to install the mod_rewrite Apache module.

    a2enmod rewrite
    # Now restart Apache
    service apache2 restart

### Permissions

Set the correct file and directory owner to the apache user (`www-data`) if
required.

    sudo chown -R www-data:[your user] *

If you want to develop using a code editor, enable write permissions for your
system's group.

    sudo chmod -R g+w *

### Scheduled tasks

For the scheduled tasks to work you will need to add one entry into your crontab
file. Edit your crontab file by executing `crontab -e` then add the following
line to the end of the file.

    * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1

### Mail

The mail is currently being sent with `sendmail`. Install on the server using
your `apt-get` package manager.

    sudo apt-get install sendmail
