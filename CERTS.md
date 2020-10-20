# SSL Certificate

The SSL certificate for casualtyforms.org is from GoDaddy.

## Renewal And Expiry

At time of writing it will expire on 21st November, 2021.

Renewal is managed by the RAF Museum IT team.

## Installing A New Certificate

Despite appearances to the contrary, the certificate is _not_ managed through the Digital Ocean console.

It must be installed manually as follows:

* The IT team will supply a zip file suitable for Apache.
* Upload the zip file to the server and unzip it
* There will be three files - a `something-bundle.crt`, a `foo.crt` and an identical `foo.pem`
* Check that the file with bundle in the name is identical to `/etc/apache2/ssl.crt/intermediate.crt` on the server.
* Replace `/etc/ssl/certs/www.casualtyforms.org.crt` with the contents of `foo.crt`
* Run `sudo apachectl -t` to check Apache is happy with the new configuration
* Run `sudo apachectl restart` to install the new certificate.
* Visit https://www.casualtyforms.org/ to check the new cert works.
