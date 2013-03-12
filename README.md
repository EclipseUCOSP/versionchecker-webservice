Version-Checker Webservice
=======

This is the repo for the Version-Checker Webservice. It is to be used in conjuction with the [Version-Checker Eclipse Plugin](https://github.com/EclipseUCOSP/org.eclipse.cbi.versionchecker). The goal of the Version Checker is to have the ability to quickly find and downloads the most up-to-date version, of the various components in an Eclipse build. WHen the plugin sends a JSON request to the webservice, the webservice parses the JSON and extracts the approproate information from the Eclipse CBI database and returns them to the plugin.

The webservice will return JSON with the form:
 ```JSON
{
      {
      "component":"com.example",
      "state":"available",
      "version":"123",
      "repoinfo":{
            "repo":"https://github.com/EclipseUCOSP/org.eclipse.cbi.versionchecker",
            "commit":"7c1b9502ff7f5204030ec046283b525c428be530",
            "branch":"master"
            }
      }
}

 ``` 


The webservice will interpret JSON with the form:
 ```JSON
{
    {
    "component":"org.eclipse.jdt.junit",
    "version":"3.7.100.v20120523-1543"
    },
    {
    "component":"org.junit",
    "version":"3.8.2.v3_8_2_v20120427-1100"
    }
}
 ``` 

The version checker is currently running [on the Eclipse servers](http://build.eclipse.org/cbi/vc/)

Quick Installation
----------------

Deployment is fairly straight forward. 
- Copy the repo to the the directory you wish to run the webservice at
- Enter the database credentials in /app/Config/dbinfo.php
- The webservice should be good to go now. 

Note: it is important to only point your browser at the folder in which the webservice resides, ie http://build.eclipse.org/cbi/vc/. Adding index.html or index.php will cause the webservice to malfunction. (the webservice takes care of all URL routing.


In-Depth Installation
------------


This information is provided to help get CakePHP applications running on the web server. The setup I have suggested is Apache 2 with mod_php, which is the standard PHP setup.

**Installation and Setup**

First of course, you need the relevant software installed:
sudo apt-get install libapache2-mod-php5 php5-mysql cakephp
sudo a2enmod rewrite
If you haven't already, you will need to install the MySQL server as well: sudo apt-get install mysql-server
I'm going to assume a Cake app already checked-out in the directory /home/userid/app/ and that you want to access that project at http://server/mysite/.
To alias this directory to the URL, edit /etc/apache2/sites-enabled/000-default and at the bottom of the <Virtualhost> section (right before the “</Virtualhost>”), add this:

    Alias /mysite "/home/userid/app/webroot"
    <Directory /home/userid/app/webroot>
      Options ExecCGI FollowSymLinks
      AllowOverride all
      Allow from all
      Order allow,deny
    </Directory>

Restart the Apache server so it recognizes the config changes:
sudo /etc/init.d/apache2 restart

**Settings**

You will likely have to modify your config/database.php file so that your app uses a MySQL database that you create. See the MySQL database instructions for info on creating a database for your app. As is the style with Cake, you will have to create your database tables manually so they can be discovered by Cake.
You will also likely have to change the ownership of your app's temporary directory so it can be written by the web server process:
sudo chown -R www-data app/tmp
Finally, since your site will be deployed in a directory (i.e. within /mysite/ instead of at the server root), edit the webroot/.htaccess file and add a RewriteBase line so it looks like this:

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase /mysite/
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
    </IfModule>

**Redirects and Links**

Of course, any links within your system will have to take the new URL into account. For example, a link like 
 ```html
<a href="/object/edit">
 ``` 
will have to become:
 ```html
<a href="/mysite/object/edit">
 ``` 
Hopefully, you have used link or other URL-building functions from the HtmlHelper class or the Controller class in your app. If so, the changes should be automatic.
If you haven't, the easiest fix might be to go back to your code and use them wherever you need a URL.


**Static Media**

For all but the simplest sites, you will have some static files that accompany the dynamically-generated code (stylesheets, images, Javascript code, etc.).
With Cake, you can simply place these files within the webroot directory. It would likely to be easiest to create a directory named something like webroot/static and put files in there. They can then be accessed at a URL like http://server/mysite/static/style.css .
