# task

Under /config/config.php:

	set $config['base_url'];
	
	
Under /config/database.php
	
	set $config['hostname'] to your db's hostname	
	set $config['username'] and $config['password'] to a user authorized to access db listed above
	set $config['database'] to the name of the database the app will use 
	
	
Mod rewrite is used in order to remove index from the url as well as ensure codeignier can distinguish asset requests(css. js etc...) from route requests:

      RewriteEngine On
      RewriteBase /
      RewriteCond %{REQUEST_FILENAME} -s [OR]
      RewriteCond %{REQUEST_FILENAME} -l [OR]
      RewriteCond %{REQUEST_FILENAME} -d
      RewriteRule ^.*$ - [NC,L]
      RewriteRule ^.*$ index.php [NC,L]

						
Document root must be set to index.php located at root/www/index.php


Navigate browser to whatever was set at $config['base_url']

Click "install requires tables"

username to login: administrator
password: password
