#Testing FrameworkOnFramework

Testing will require a "special" environment and you should take care of some conventions.
This document will help you on setting up everything in order to be ready to test.

##Setting up
You have to follow these steps:
+ Create a new Joomla! installation: it will be our guinea pig
+ Download and install PHPUnit 3.7
+ Install and configure Composer
+ Configure your environment
+ Start testing!
   + What should I test?
      + Testing private methods
   + Creating new objects and avoid problems with static instances
   + Normal tests vs Database tests
   + How to speed up tests

###Joomla! installation
We need a Joomla! installation in order to interact with database and use Joomla! libraries. At the moment, tests are performed against Joomla! 3.x
When you install your Joomla! site your db prefix **must be _jos__**. We have to do that because PHPUnit can import data before every test, but we must use fixed table names, ie `jos_assets`.

You should use **MySQLi** as database driver; if you're getting any problems, try using `127.0.0.1` instead of `localhost` as your server name.

###Download and install PHPUnit 3.7
First of all you have to install PHPUnit on your machine, we're doing this using `PEAR`.
You simply have to run the following commands:
```
pear config-set auto_discover 1
pear install pear.phpunit.de/PHPUnit
pear install phpunit/DbUnit
```

###Install and configure Composer
We need some packages that are available through Composer only, so you have to install it inside FOF root.  
Here you can find some useful info on how you can do that: http://getcomposer.org/download/

After downloading, you have to run the installer:  
`php composer.phar install`

Great, now you're ready to go!

###Configure your environment
Inside the tests folder, you'll find a file named `config.dist.php`; you have to rename it to `config.php` and put the _absolute_ path to your Joomla! guinea pig.

###Start testing!

####What should I test?

#####Testing private methods

####Creating new objects and avoid problems with static instances

####Normal tests vs Database tests

####How to speed up tests
