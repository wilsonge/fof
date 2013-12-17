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

    /**
     * Does the file exists?
     *
     * @param   $path  string   Path to the file to test
     *
     * @return  bool
     */
    public function fileExists($path);

    /**
     * Delete a file or array of files
     *
     * @param   mixed  $file  The file name or an array of file names
     *
     * @return  boolean  True on success
     *
     */
    public function fileDelete($file);

    /**
     * Copies a file
     *
     * @param   string   $src          The path to the source file
     * @param   string   $dest         The path to the destination file
     *
     * @return  boolean  True on success
     */
    public function fileCopy($src, $dest);

    /**
     * Write contents to a file
     *
     * @param   string   $file         The full file path
     * @param   string   &$buffer      The buffer to write
     *
     * @return  boolean  True on success
     */
    public function fileWrite($file, &$buffer);
}