<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * FrameworkOnFramework model behavior class
 *
 * @package  FrameworkOnFramework.Model
 * @since    2.2
 */
abstract class FOFModelBehavior extends JEvent
{
	public function onBeforeSave(&$model, &$data) {}

	public function onBeforeDelete(&$model) {}

	public function onBeforeCopy(&$model) {}

	public function onBeforePublish(&$model) {}

	public function onBeforeHit(&$model) {}

	public function onBeforeMove(&$model) {}

	public function onBeforeReorder(&$model) {}

	public function onBeforeBuildQuery(&$model, &$query) {}

	public function onAfterSave(&$model) {}

	public function onAfterDelete(&$model) {}

	public function onAfterCopy(&$model) {}

	public function onAfterPublish(&$model) {}

	public function onAfterHit(&$model) {}

	public function onAfterMove(&$model) {}

	public function onAfterReorder(&$model) {}

	public function onAfterBuildQuery(&$model, &$query) {}
}