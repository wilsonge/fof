<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  platformFilesystem
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die;

interface FOFPlatformFilesystemInterface
{
    /**
     * Returns the ordering of the platform class. Files with a lower ordering
     * number will be loaded first.
     *
     * @return  integer
     */
    public function getOrdering();

    /**
     * Is this platform enabled? This is used for automatic platform detection.
     * If the environment we're currently running in doesn't seem to be your
     * platform return false. If many classes return true, the one with the
     * lowest order will be picked by FOFPlatformFilesystem.
     *
     * @return  boolean
     */
    public function isEnabled();
}