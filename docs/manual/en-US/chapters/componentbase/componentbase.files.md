2.1 Basic Files
==========================================

2.1.1 reviews.php
==========================================

```<?php
/**
 * @copyright (C) 2013 JoomJunk. All rights reserved.
 * @package    JJ Blog
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

2.1.2 dispatcher.php
==========================================