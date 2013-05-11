<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage hal
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

interface FOFHalRenderInterface
{
	public function render($options = array());
}