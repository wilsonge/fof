2.1 Basic Files
==========================================

2.1.1 reviews.php
==========================================

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

2.1.2 dispatcher.php
==========================================
This file simply specifies the default view for the component. In this case we are calling it **restaurants**

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