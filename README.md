Project Deployent Steps: 
========================
1. cd /var/www/html
2. git clone git URL
3. git checkout required branch 
4. Check .gitignore and put the .gitignore files & directories from backup etc
5. 


Project Objectve: Trainings for Installation & cofiguration codeigniter version-4.2.10
================

Trancate Talble: 
booked_doctor_appointment
patients
revisit_patients
login_activity
patients_login
patient_discharge


Refrences: 
=========
1. Download: https://codeigniter.com/download
2. Tutorial: https://codeigniter4.github.io/userguide/tutorial/news_section.html

Codeigniter (version 4.3.3) Installation Steps on Ubuntu (22.04 LTS):
=======================================================================
1. Download [recommended latest version]: https://codeigniter.com/download 
2. Alt+Ctl+T                                                          [Open Terminal]
3. sudo cd ~/Downloads                                                [Go to Downloads]
4. sudo mv codeigniter4-framework-v4.3.3-0-ge3821f9.zip /var/www/html/   [Moved downloaded codeigniter into documentRoot path]
5. cd /var/www/html                                                   [Go to documentRoot path ]
6. sudo unzip codeigniter4-framework-v4.3.3-0-ge3821f9.zip           [Unzip codeigniter]
7. sudo mv codeigniter4-framework-v4.3.3-0-ge3821f9 miracle_hospitals    [Renamed TO codeigniterv4 (my project name)]
8. cd /var/www/html/miracle_hospitals                                     [Go to my project directory]


Settings & Confurations:
=======================
1. sudo cp env TO .env                                      [Rename env to .env] 
2. sudo gedit .env                                          [Open .env file]
a. Comment CI_ENVIRONMENT = production & add CI_ENVIRONMENT = development
b. app.baseURL = 'http://localhost:8080/miracle_hospitals/public/'
c. sudo gedit /app/Config/App.php                         [Open App.php file]
d. public $baseURL = 'http://localhost:8080/miracle_hospitals/public/';   [Set $baseURL ]
e. sudo mkdir /var/www/html/miraclehospitals_test/writable/cache

3. Permissions:
a. sudo chmod 777 -R writeable/cache
b. sudo chmod 777 -R writeable/debugbar
c. sudo chmod 777 -R writeable/logs
d. sudo chmod 777 -R writeable/session
e. sudo chmod 777 -R writeable/uploads

Database Settins:
=================
1. sudo gedit app/Config/Database.php           [Open database file & enter database credentials]
2. <pre>public $default = [                          [Set database - hostname, username, password ]
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => '',
        'password' => '',
        'database' => '',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],IA5ISDvF2ZQBJljp
        'port'     => 3306,
    ];
</pre>

3. Permissions:
==============
sudo chown neoarks:neoarks -R /var/www/html/miracle_hospitals/
sudo chown www-data:www-data -R /var/www/html/miracle_hospitals/public
sudo chown www-data:www-data -R /var/www/html/miracle_hospitals/writable/
 

 4. Defined Custome Constats: Change below defined constants 
 ============================
  sudo gedit app/Config/Constants.php
  define('ADMIN_EMAIL', 'terminalstack@gmail.com');
  define('DEV_AUTHOR', 'Neoarks');
  define('ISU_SUPPORT', 'Please contact admin for support ');

5. Routing Settings:
================
  1. app/Config/Routes.php
    $routes->setAutoRoute(false);

2. app/Config/Feature.php
  $autoRoutesImproved = true;
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

6. Run Project/Spark
====================
  1. php spark serve                      [Run server and show browser url likewise: http://localhost:8080 OR http://localhost:8081]
  2. http://localhost:8080 OR             [Run at browser (note: port will be shown by php spark serve command)]
   http://localhost/codeigniterv4/public/


1. Credentials: 
Dr. credentials: 
Login name: demodoc44@gmail.com 
Password: demodoc44

Login name: newdoclogin@gmail.com 
Password: newdoclogin


7. Optional Settings:
==================== 
I. Default Defined constants: Just for using wherever needed
  1. FCPATH /var/www/html/miracle_hospital/public PATH
  2. WRITEPATH /var/www/html/miracle_hospital/writable PATH

II. .htaccess Settings 
  1. Changed 
    Options All -Indexes 
    To
    Options -Indexes
    
    Ref: https://stackoverflow.com/questions/21158375/htaccess-file-gives-500-internal-server-error
        https://coolajax.net/codeigniter-show-500-server-error-on-htaccess-issue-in-bluehost
        https://stackoverflow.com/questions/19071324/request-exceeded-the-limit-of-10-internal-redirects
        https://stackoverflow.com/questions/15850845/request-exceeded-the-limit-of-10-internal-redirects-due-to-probable-configuratio
        https://help.dreamhost.com/hc/en-us/articles/215747748-How-can-I-redirect-and-rewrite-my-URLs-with-an-htaccess-file-

-------------------------------------------------------------------------------------------

How does work Codeigniter 4.2.10:
================================
Follow tutorial: https://codeigniter4.github.io/userguide/tutorial/news_section.html

It is not on the production server, set CI_ENVIRONMENT to development in .env file to take advantage of the debugging tools     provided.
2. Set baseUrl
3. Set Database.php
4. Local Development Server- CodeIgniter 4 comes with a local development server, leveraging PHP's built-in web server with CodeIgniter routing. You can use the serve script to launch it, with the following command line in the main directory:
"php spark serve"
This will launch the server and you can now view your application in your browser at http://localhost:8080.

Build Your First Application:
============================= 
1. Static pages-  Create a file at app/Controllers/Pages.php
================  Adding Logic to the Controller-
                ----------------------------------
                  "The static page bodies will be located in the app/Views/pages/ directory.
                  In that directory, create two files named home.php and about.php. Within those files, type some text - anything you'd like - and save them. If you like to be particularly un-original, try "Hello World!".
                  app/Views/pages/home.php
                  app/Views/pages/about.php
                  Create the header at app/Views/templates/header.php
                  Create the header at app/Views/templates/footer.php
                  
                  Routing-
                 ----------
                 Open the routing file located at app/Config/Routes.php and look for the "Route Definitions" section of the configuration file.
                 Add the following lines, after the route directive for '/'.

                 $routes->get('pages', 'Pages::index');
                 $routes->get('(:any)', 'Pages::view/$1');

                Ready to test-
                --------------
                From the command line, at the root of your project:

                > php spark serve
                will start a web server, accessible on port 8080. If you set the location field in your browser to localhost:8080, you should see the CodeIgniter welcome page.

                Now visit localhost:8080/home. 
                Congrats you Did!!!!!




=======================================================================================================
# CodeIgniter 4 Framework

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](http://codeigniter.com).

This repository holds the distributable version of the framework,
including the user guide. It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [the announcement](http://forum.codeigniter.com/thread-62615.html) on the forums.

The user guide corresponding to this version of the framework can be found
[here](https://codeigniter4.github.io/userguide/).


## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Contributing

We welcome contributions from the community.

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/CONTRIBUTING.md) section in the development repository.

## Server Requirements

PHP version 7.4 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
