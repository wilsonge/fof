<?php
/**
 *  @package     AkeebaStrapper
 *  @copyright   Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

define('AKEEBASTRAPPER_VERSION', '##VERSION##');
define('AKEEBASTRAPPER_DATE', '##DATE##');
define('AKEEBASTRAPPER_MEDIATAG', md5(AKEEBASTRAPPER_VERSION . AKEEBASTRAPPER_DATE));