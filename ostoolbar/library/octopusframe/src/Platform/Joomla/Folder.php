<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\Joomla;

use OctopusFrame\Filesystem\Folder\FolderInterface;
use JFolder;

defined('OCTOPUSFRAME_LOADED') or die();

jimport('joomla.filesystem.folder');


class Folder implements FolderInterface
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
    public function create($path, $mode = 0755, $recursive = true)
    {
        return JFolder::create($path, $mode);
    }

    /**
     * Deletes a folder
     *
     * @param  string $path The folder path
     *
     * @return bool         True if success
     */
    public function delete($path)
    {
        return JFolder::delete($path);
    }

    /**
     * Checks if a folder exists
     *
     * @param  string $path The folder path
     *
     * @return bool         True if success
     */
    public function exists($path)
    {
        return JFolder::exists($path);
    }

    /**
     * Copy a folder, recursively. If destination exists, delete and replace.
     *
     * @param  string $source      The source folder path
     * @param  string $destination The destination folder path
     * @param  bool   $includeSelf True, if should create the parent folder too
     *
     * @return bool         True if success
     */
    public function copy($source, $destination, $includeSelf = true)
    {
        JFolder::copy($source, $destination);
    }
}
