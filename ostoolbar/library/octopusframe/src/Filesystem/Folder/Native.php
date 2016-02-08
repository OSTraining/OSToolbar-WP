<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Filesystem\Folder;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

defined('OCTOPUSFRAME_LOADED') or die();


class Native implements FolderInterface
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
        return mkdir($path, $mode, $recursive);
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
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        if (!empty($files)) {
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
        }

        if (is_dir($path) && !is_link($path)) {
            return rmdir($path);
        } else {
            return unlink($path);
        }
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
        return file_exists($path);
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
        $return = false;

        if (!file_exists($source)) {
            return false;
        }

        if ($includeSelf) {
            $destination .= '/' . basename($source);
        }

        if (file_exists($destination)) {
            $this->delete($destination);
        }

        $return = mkdir($destination, 0755, true);

        $iterator    = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $this->create($destination . '/' . $iterator->getSubPathName());
            } else {
                $return = $return && copy($item, $destination . '/' . $iterator->getSubPathName());
            }
        }

        return $return;
    }

    /**
     * Clean a folder, without remove it. Allow to specify a list of
     * files to ignore (specified with relative paths).
     *
     * @param  string $path   The folder's path
     * @param  array  $ignore A list of items to ignore
     * @return bool           True, if success
     */
    public function clean($path, array $ignore = array())
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        if (!empty($files)) {
            foreach ($files as $fileinfo) {
                $ignoreFile = false;
                if (!empty($ignore)) {
                    foreach ($ignore as $item) {
                        if ($fileinfo->getRealPath() === $path . '/' . $item) {
                            // Do not remove the current item. Go to the next one.
                            $ignoreFile = true;
                            break;
                        }
                    }
                }

                if (!$ignoreFile) {
                    $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                    $todo($fileinfo->getRealPath());
                }
            }
        }

        // @todo: make it return false, in case of any error
        return true;
    }
}
