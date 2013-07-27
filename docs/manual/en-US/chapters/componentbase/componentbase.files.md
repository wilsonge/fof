2.1 Basic Files
==========================================

2.1.1 reviews.php
==========================================

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

This code here contains the usual `defined('_JEXEC') or die();` statement simply checks whether we are accessing the file from within Joomla or if it's trying to be externally accessed. We then check to see if FOF library exists. If it doesn't then we throw an error. Finally we call the dispatcher on the component.

2.1.2 dispatcher.php
==========================================
