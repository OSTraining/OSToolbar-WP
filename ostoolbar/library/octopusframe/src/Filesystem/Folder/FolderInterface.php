<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Filesystem\Folder;

defined('OCTOPUSFRAME_LOADED') or die();


interface FolderInterface
{
    /**
     * Creates a new folder
     *
     * @param  string $path      The folder path
     * @param  int    $mode      The folder mode
     * @param  bool   $recursive Create folder recursively
     *
     * @return bool         True if success
     */
    public function create($path, $mode = 0755, $recursive = true);

    /**
     * Deletes a folder
     *
     * @param  string $path The folder path
     *
     * @return bool         True if success
     */
    public function delete($path);

    /**
     * Checks if a folder exists
     *
     * @param  string $path The folder path
     *
     * @return bool         True if success
     */
    public function exists($path);

    /**
     * Copy a folder, recursively
     *
     * @param  string $source        The source folder path
     * @param  string $destination   The destination folder path
     * @param  bool   $includeParent True, if should create the parent folder too
     *
     * @return bool         True if success
     */
    public function copy($source, $destination, $includeParent = true);
}
