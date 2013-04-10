Version-Checker Webservice
=======

This is the repo for the Version-Checker Webservice. It is to be used in conjuction with the [Version-Checker Eclipse Plugin](https://github.com/EclipseUCOSP/org.eclipse.cbi.versionchecker). The goal of the Version Checker is to have the ability to quickly find and downloads the most up-to-date version, of the various components in an Eclipse build. When the plugin sends a JSON request to the webservice, the webservice parses the JSON and extracts the approproate information from the Eclipse CBI database and returns them to the plugin.

The version checker is currently running [on the Eclipse servers](http://build.eclipse.org/cbi/vc/)


The webservice will interpret JSON with the form:
```JSON
{
    "component":"org.eclipse.jdt.junit",
    "version":"3.7.100.v20120523-1543"
} 
```
and
```JSON
{
    "component":"org.junit",    
}
``` 

Each object passed into the webservice on a POST request will illicit a response object. Each response object will contain: the component, the version, it's state (more info below), the repository utl, the commit for that version, and the repository branch. The response object can have one of 3 states: available, unavailable, and alternative. There are different factors which determine the state of a response. They are: the type of JSON passed it(with or without a version), the number of matching entries for the component in the database, and the presence of a matching database entry version. Here is a break down of possible states grouped by the number of entries found in the database.

A Single Matching Entry in the Database is Found
-------------

**Case 1: Matching version numbers**


**Input:**
```JSON
{
    "component": "org.eclipse.pde.api.tools",
    "version":"1.0.500.v20130225-1820"
}
```

If there entry in the database for this component whose matches the JSON then we will get a response in the 'available' state as shown below

**Output:**

```JSON
{
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git',
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'available',
    'version': '1.0.500.v20130225-1820', 
    'component': 'org.eclipse.pde.api.tools'
}
```

**Case 2: Different Version Numbers**

**Input:**
```JSON
{
    "component": "org.eclipse.pde.api.tools",
    "version":"0.0.0.0.0.0.test"
}
```
If there is only a single matching entry in the database for the requested component, and the versions do not match, then the entry will be returned in the 'alternative' state.

**Output:**

```JSON
{
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git',
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'alternative',
    'version': '1.0.500.v20130225-1820', 
    'component': 'org.eclipse.pde.api.tools'
}
```

**Case 3: No Version Number in input**

**Input:**

```JSON
{
    "component": "org.eclipse.pde.api.tools"
}
```


If There is no version specified in the JSON request, then the returned object will be in an available state.

**Output:**
```JSON
{
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git',
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'available',
    'version': '1.0.500.v20130225-1820', 
    'component': 'org.eclipse.pde.api.tools'
}
```



Multiple Entries are Found in the Database
---------------

**Case 1: One of the entries found matches the version in the request**

**Input:**
```JSON
{
    "component": "org.eclipse.pde",
    "version":"3.9.0.v20130318-0959"
}
```

If there are multiple entries for a component, and one of them has the same version as the requested version, return only that entry as 'available'

**Output:**

```JSON
{
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git', 
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'available', 
    'version': '3.9.0.v20130318-0959', 
    'component': 'org.eclipse.pde'
}
```

**Case 2: No entries match the version**

**Input:**
```JSON
{
    "component": "org.eclipse.pde",
    "version":"3.9.0.v20130318-0959"
}
```

If there are multiple entries in the database for the component, but none of them have a mactching version, all the entries will be returned in the 'alternative' state.

**Output:**
```JSON
{
    {
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git', 
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'alternative', 
    'version': '3.9.0.v20130318-0959', 
    'component': 'org.eclipse.pde'
    },
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git', 
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'alternative', 
    'version': '3.8.100.v20130318-0959', 
    'component': 'org.eclipse.pde'
    }
}
```



**Case 3: No Version Number in input**

**Input:**
```JSON
{
    "component": "org.eclipse.pde"
}
```

If no version number is passed in with the object in the request, then the webservice will send ONLY the lastest version of the component back in the 'available' state.

**Output:**
```JSON
{
    'repoinfo': {'repo': '/gitroot/platform/eclipse.platform.releng.aggregator.git', 
                 'commit': '0b7139aef0ec26a536ab1557fa2c153c30abda42', 
                 'branch': 'master'
                 }, 
    'state': 'available', 
    'version': '3.9.0.v20130318-0959', 
    'component': 'org.eclipse.pde'
}
```

No Entries are Found
------------------

Thie is by far the most simple one, if no entries are found the state is simply unavailable, but it is handled in the plugin, on the client side. It is the same for both types of requests.

**Input:**

```JSON
{
    {
    "component":"im.not.a.real.component",
    "version":"1.2.3.4.5.6.7.8.9"
    }
} 
```
or
```JSON
{
    {
    "component":"im.not.a.real.component"   
    }
}
``` 

Both of these request will not yield a response, and will be seen as unavailable by the client.




Quick Installation
----------------

*Requirements*

-Apache2 webserver:
```
    <sudo apt-get install apache2>
```
-PHP5:
```
    <sudo apt-get install libapache2-mod-php5 php5-mysql cakephp>
    <sudo a2enmod rewrite>
```
-MySQL server:
```
    <sudo apt-get install mysql-server>
```

In general all these three packages are included in a LAMP (Linux-Apache-MySQL-PHP) package in Ubuntu.

Deployment is fairly straight forward. 
- Copy the repo to the the directory you wish to run the webservice at
- Enter the database credentials in /app/Config/database.php
- The webservice should be good to go now. 

Note: it is important to only point your browser at the folder in which the webservice resides, ie http://build.eclipse.org/cbi/vc/. Adding index.html or index.php will cause the webservice to malfunction (the webservice takes care of all URL routing).


In-Depth Installation
------------

This information is provided to help get CakePHP applications running on the web server. The setup I have suggested is Apache 2 with mod_php, which is the standard PHP setup.

**Installation and Setup**

It is assumed a Cake app already checked-out in the directory /home/app (or anywhere) and that you want to access that project at http://server/mysite/.
To alias this directory to the URL, edit /etc/apache2/sites-enabled/000-default and at the bottom of the <Virtualhost> section (right before the “</Virtualhost>”), to directive for the desired URL, add this:

    Alias /mysite "/home/app"
    <Directory /home/app/webroot>
      Options ExecCGI FollowSymLinks
      AllowOverride all
      Allow from all
      Order allow,deny
    </Directory>

Restart the Apache server so it recognizes the config changes:

    <sudo /etc/init.d/apache2 restart>

For more settings on Apache server please see [this](https://help.ubuntu.com/10.04/serverguide/httpd.html).

**Settings**

You will likely have to modify your config/database.php file so that your app uses a MySQL database that you create. As is the style with Cake, you will have to create your database tables manually so they can be discovered by Cake.
You will also likely have to change the ownership of your app's temporary directory so it can be written by the web server process:
sudo chown -R www-data app/tmp

**Redirects and Links**

Hopefully, you have used link or other URL-building functions from the HtmlHelper class or the Controller class in your app. If so, the changes should be automatic.
If you haven't, the easiest fix might be to go back to your code and use them wherever you need a URL.

For more information on URL-writing please see [CakePHP documentation](http://book.cakephp.org/2.0/en/installation/url-rewriting.html).

**Static Media**

For all but the simplest sites, you will have some static files that accompany the dynamically-generated code (stylesheets, images, Javascript code, etc.).
With Cake, you can simply place these files within the webroot directory. It would likely to be easiest to create a directory named something like webroot/static and put files in there. They can then be accessed at a URL like http://server/mysite/static/style.css .
