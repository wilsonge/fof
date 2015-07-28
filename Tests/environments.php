<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Configuration for running the unit tests
 */

$environments = array(
	// The paths to Joomla cloned repo
	'3.4'     => realpath(__DIR__.'/environments/3.4'),
	'3.5-dev' => realpath(__DIR__.'/environments/3.5-dev'),
);
