<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage hal
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

/**
 * Interface for HAL document renderers
 */
interface FOFHalRenderInterface
{
	/**
	 * Render a HAL document into a representation suitable for consumption.
	 *
	 * @param   array  $options  Renderer-specific options
	 */
	public function render($options = array());
}