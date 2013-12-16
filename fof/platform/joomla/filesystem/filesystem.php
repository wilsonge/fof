<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  platformFilesystem
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die;

class FOFPlatformJoomlaFilesystem extends FOFPlatformFilesystem implements FOFPlatformFilesystemInterface
{
    /**
     * Is this platform enabled?
     *
     * @see FOFPlatformInterface::isEnabled()
     *
     * @return  boolean
     */
    public function isEnabled()
    {
        if (is_null($this->isEnabled))
        {
            $this->isEnabled = true;

            // Make sure _JEXEC is defined
            if (!defined('_JEXEC'))
            {
                $this->isEnabled = false;
            }

            // We need JVERSION to be defined
            if ($this->isEnabled)
            {
                if (!defined('JVERSION'))
                {
                    $this->isEnabled = false;
                }
            }

            // Check if JFactory exists
            if ($this->isEnabled)
            {
                if (!class_exists('JFactory'))
                {
                    $this->isEnabled = false;
                }
            }

            // Check if JApplication exists
            if ($this->isEnabled)
            {
                $appExists = class_exists('JApplication');
                $appExists = $appExists || class_exists('JCli');
                $appExists = $appExists || class_exists('JApplicationCli');

                if (!$appExists)
                {
                    $this->isEnabled = false;
                }
            }

            // If it's enabled, let's import the library
            if($this->isEnabled)
            {
                JLoader::import('joomla.filesystem.file');
            }
        }

        return $this->isEnabled;
    }

    public function fileExists($path)
    {
        return JFile::exists($path);
    }

    public function fileDelete($file)
    {
        return JFile::delete($file);
    }

    public function fileCopy($src, $dest, $path = null, $use_streams = false)
    {
        return JFile::copy($src, $dest, $path, $use_streams);
    }
}