# fallr
A blogging website for falling into deep holes. Built with MySQL, PHP and JavaScript.

## Requirements

1. Apache2
2. PHP
3. MySQL

## Mild Explenation of credentials.php file
I know there are credentials in here, they are not actual credentials and **MUST** be updated before launching.

## Setup
Essentially this has to be run in 2 parts: The API and the regular server. I have my system set up to serve these files up in the following way:

localhost/fallrAPI/
localhost/fallr/

This can be achieved by running apache2 and creating links from the `api` folder and the `src` folder to 
the `/var/www/html/` folder. The following commands should give you a decent idea of how to do this:

```
ln -s ~/Documents/fallr/API/api /var/www/html/fallrAPI
ln -s ~/Documents/fallr/src /var/www/html/fallr
```

You will likely also have to configure apache2 to use the .htaccess files. To do this, open the `/etc/apache2/sites-enabled/<config-file-name>.conf` and add the following lines to the bottom.

```
<Directory /var/www/html/fallrAPI>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
<Directory /var/www/html/fallr>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

You will also need to create the database using the `createMySQL.SQL` script and update the username/password
in the `credentials.php` file (I have left phony information in there for now, as it becomes a pain to explain
how to properly create this file). You should also update the secret key as it is public on git.

This should be enough to get basic functionality.