<?php
/**
* @package     FrameworkOnFramework
* @subpackage  platform
* @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/
// Protect from unauthorized access
defined('_JEXEC') or die;

abstract class FOFPlatformFilesystem implements FOFPlatformFilesystemInterface
{
    /**
     * The ordering for this platform filesystem class. The lower this number is, the more
     * important this class becomes. Most important enabled class ends up being
     * used.
     *
     * @var  integer
     */
    public $ordering = 100;

    /**
     * Caches the enabled status of this platform class.
     *
     * @var  boolean
     */
    protected $isEnabled = null;

    /**
     * The list of paths where platform class files will be looked for
     *
     * @var  array
     */
    protected static $paths = array();

    /**
     * The platform class instance which will be returned by getInstance
     *
     * @var  FOFPlatformFilesystem
     */
    protected static $instance = null;

    /**
     * Force a specific platform object to be used. If null, nukes the cache
     *
     * @param   FOFPlatformInterface|null  $instance  The Platform object to be used
     *
     * @return  void
     */
    public static function forceInstance($instance)
    {
        if ($instance instanceof FOFPlatformFilesystemInterface || is_null($instance))
        {
            self::$instance = $instance;
        }
    }

    /**
     * Find and return the most relevant platform object
     *
     * @return  FOFPlatformInterface
     */
    public static function getInstance()
    {
        if (!is_object(self::$instance))
        {
            // Get the paths to look into
            $paths = array(__DIR__);

            if (is_array(self::$paths))
            {
                $paths = array_merge(array(__DIR__), self::$paths);
            }

            $paths = array_unique($paths);

            foreach ($paths as $path)
            {
                // Get the .php files containing platform classes
                $files = self::getFiles($path);

                if (!empty($files))
                {
                    foreach ($files as $file)
                    {
                        // Get the class name for this platform class
                        $base_name = basename($file, '.php');
                        $class_name = 'FOFPlatformFilesystem' . ucfirst($base_name);

                        // Load the file if the class doesn't exist
                        if (!class_exists($class_name))
                        {
                            @include_once $file;
                        }

                        // If the class still doesn't exist this file didn't
                        // actually contain a platform class; skip it
                        if (!class_exists($class_name))
                        {
                            continue;
                        }

                        // If it doesn't implement FOFPlatformInterface, skip it
                        if (!class_implements($class_name, 'FOFPlatformFilesystemInterface'))
                        {
                            continue;
                        }

                        // Get an object of this platform
                        $o = new $class_name;

                        // If it's not enabled, skip it
                        if (!$o->isEnabled())
                        {
                            continue;
                        }

                        if (is_object(self::$instance))
                        {
                            // Replace self::$instance if this object has a
                            // lower order number
                            $current_order = self::$instance->getOrdering();
                            $new_order = $o->getOrdering();

                            if ($new_order < $current_order)
                            {
                                self::$instance = null;
                                self::$instance = $o;
                            }
                        }
                        else
                        {
                            // There is no self::$instance already, so use the
                            // object we just created.
                            self::$instance = $o;
                        }
                    }
                }
            }
        }

        return self::$instance;
    }

    /**
     * Returns the ordering of the platform class.
     *
     * @see FOFPlatformFilesystemInterface::getOrdering()
     *
     * @return  integer
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

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
            $this->isEnabled = false;
        }

        return $this->isEnabled;
    }

    protected static function getFiles($path)
    {
        $return = array();
        $handle = @opendir($path);

        if(!$handle)
        {
            return $return;
        }

        while (($file = readdir($handle)) !== false)
        {
            if($file == '.' || $file == '..' || in_array($file, array('interface.php', 'filesystem.php')))
            {
                continue;
            }

            $return[] = $path . '/' . $file;
        }

        return $return;
    }
}