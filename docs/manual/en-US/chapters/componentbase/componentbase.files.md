2.1 Basic Files
==========================================
2.1.1 com_reviews.xml
------------------------------------------
```xml
<?xml version="1.0" encoding="utf-8"?>
<extension type="component" version="2.5" method="upgrade">
	<name>COM_REVIEWS</name>
	<author>JoomJunk</author>
	<creationDate>27th Jul 2013</creationDate>
	<copyright>Copyright (C) 2013 JoomJunk</copyright>
	<license>http://www.gnu.org/licenses/gpl-3.0.html</license>
	<authorEmail>admin@joomjunk.co.uk</authorEmail>
	<authorUrl>http://www.joomjunk.co.uk</authorUrl>
	<version>1.0.0</version>

	<install> <!-- Runs on install -->
		<sql>
			<file driver="mysql" charset="utf8">sql/install/mysql/install.mysql.utf8.sql</file>
		</sql>
	</install>
	<uninstall> <!-- Runs on uninstall -->
		<sql>
			<file driver="mysql" charset="utf8">sql/install/mysql/uninstall.mysql.utf8.sql</file>
		</sql>
	</uninstall>
	<update> <!-- Runs on update -->
		<schemas>
			<schemapath type="mysql">sql/updates/mysql</schemapath>
		</schemas>
	</update>

	<administration>
		<menu>COM_REVIEWS</menu>
		<files folder="admin">
			<!-- Admin Main Files -->
			<folder>sql</folder>
			<folder>views</folder>
			<filename>index.html</filename>
			<filename>controller.php</filename>
		</files>
		<languages folder="admin">
				<language tag="en-GB">language/en-GB/en-GB.com_reviews.ini</language>
				<language tag="en-GB">language/en-GB/en-GB.com_reviews.sys.ini</language>
		</languages>
	</administration>
</extension>
```

Here we create a standard Joomla XML file simply for the backend of Joomla. If you are unfamiliar with any of the contents of this file we recommend that you look at the <a href="http://docs.joomla.org/Manifest_files">Manifest Files</a> section on the Joomla Documentation Site.

2.1.2 install.sql
------------------------------------------
To start with we need a restaurant to review. So to do this we create a install.sql file in the location specified in the XML file:

```sql
CREATE TABLE IF NOT EXISTS `#__reviews_restaurants` (
  `reviews_restaurants_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` TEXT NOT NULL,
  `address` TEXT,
  `suburb` varchar(100),
  `state` varchar(100),
  `country` varchar(100),
  `postcode` varchar(100),
  `telephone` varchar(255),
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `hits` int(11) DEFAULT  '0',
  `staffrating` int(1),
  `foodrating` int(1),
  `servicerating` int(1),
  `atmosphererating` int(1),
  `pricerating` int(1),
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
```

Database tables are named as #__component_view and the auto increment field is named component_view_id. Furthermore other fields have some special names. The published field is called `enabled` and the check in fields are called `locked_by` and `locked_on`. Note that all these conventions are overridable in a fof.xml config file. Read more about this here if desired: https://www.akeebabackup.com/documentation/fof/features-reference.html#fofxml-file

2.1.3 reviews.php
------------------------------------------

This file is required in all Joomla components - however with FOF it requires substantially less code:

```php
<?php
/**
 * @copyright (C) 2013 JoomJunk. All rights reserved.
 * @package    Restaurant Reviews
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 **/
// no direct access
defined('_JEXEC') or die();

// Load FOF
include_once JPATH_LIBRARIES.'/fof/include.php';
if(!defined('FOF_INCLUDED')) {
	JError::raiseError ('500', 'FOF is not installed');
}

FOFDispatcher::getTmpInstance('com_reviews')->dispatch();
```

This code here contains the usual `defined('_JEXEC') or die();` statement simply checks whether we are accessing the file from within Joomla or if it's trying to be externally accessed.

We then check to see if FOF library exists. If it doesn't then we throw an error.

Finally we call the dispatcher on the component to load the core view.

2.1.4 dispatcher.php
------------------------------------------

This file simply specifies the default view for the component. In this case we are calling it **restaurants** as we defined earlier in our install.sql file (by calling the table #__reviews_restaurants)

```php
<?php
/**
 * @copyright (C) 2013 JoomJunk. All rights reserved.
 * @package    Restaurant Reviews
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die();

class ReviewsDispatcher extends FOFDispatcher
{
	public $defaultView = 'restaurants';
}
```

