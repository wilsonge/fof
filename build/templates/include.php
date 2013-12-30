<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  include
 *  @copyright   Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 *
 *  Initializes FOF
 */

if (!defined('FOF_INCLUDED'))
{
    define('FOF_INCLUDED', '##VERSION##');

	// Register a debug log
	if (defined('JDEBUG') && JDEBUG)
	{
		JLog::addLogger(array('text_file' => 'fof.log.php'), JLog::ALL, array('fof'));
	}

	// Register the FOF autoloader
    require_once __DIR__ . '/autoloader/fof.php';
	FOFAutoloaderFof::init();
}