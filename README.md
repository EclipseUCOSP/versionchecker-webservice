versionchecker-webservice
=========================

The framework's database settings are already set up,
I figured it makes sense that it is developed on a common
test database so we don't have to set up the config files 
each time we push/pull.



The database files are handled in cakephp-2.2.7/app/config/database.php
It should look like this:

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost:3306',
		'login' => 'root',
		'password' => 'root',
		'database' => 'versiondb',
		'prefix' => '',
		'encoding' => 'utf8',
	);
  
  
To work with W/M/L-AMP the password and username are just root.

Make sure your AMP stack setup is on port 3306.

and make a db called version db.

To set up your table, use the SQL commands in the db repository.
