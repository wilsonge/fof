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
     * @see FOFPlatformFilesystemInterface::isEnabled()
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
                JLoader::import('joomla.filesystem.path');
                JLoader::import('joomla.filesystem.folder');
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

    public function fileWrite($file, &$buffer, $use_streams = false)
    {
        return JFile::write($file, $buffer, $use_streams);
    }

    public function pathCheck($path)
    {
        return JPath::check($path);
    }

    public function pathClean($path, $ds = DIRECTORY_SEPARATOR)
    {
        return JPath::clean($path, $ds);
    }

    public function pathFind($paths, $file)
    {
        return JPath::find($paths, $file);
    }

    public function folderExists($path)
    {
        return JFolder::exists($path);
    }

    public function folderFiles($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
                                $excludefilter = array('^\..*', '.*~'), $naturalSort = false)
    {
        return JFolder::files($path, $filter, $recurse, $full, $exclude, $excludefilter, $naturalSort);
    }

    public function folderFolders($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
                                  $excludefilter = array('^\..*'))
    {
        return JFolder::folders($path, $filter, $recurse, $full, $exclude, $excludefilter);
    }

    public function folderCreate($path = '', $mode = 0755)
    {
        return JFolder::create($path, $mode);
    }
}