<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Filesystem\File;

defined('OCTOPUSFRAME_LOADED') or die();


class Native implements FileInterface
{
    /**
     * Writes to the specified file
     *
     * @param  string $path    The file path
     * @param  string $content The content to write
     * @param  bool   $append  If true, append the content. If false, rewrite the file
     *
     * @return bool            True if success
     */
    public function write($path, $content, $append = false)
    {
        $flags = 0;

        if ($append) {
            $flags = FILE_APPEND;
        }

        return file_put_contents($path, $content, $flags);
    }

    /**
     * Reads the specified file
     *
     * @param  string $path The file path
     * @return string       The file's content
     */
    public function read($path)
    {
        return file_get_contents($path);
    }

    /**
     * Checks if a file exists
     *
     * @param  string $path The file path
     *
     * @return bool         True if exists
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Copy a file and support destination as folder
     *
     * @param  string $source      The source path
     * @param  string $destination The destination path
     *
     * @return bool         True if copied
     */
    public function copy($source, $destination)
    {
        if (is_dir($destination)) {
            $destination .= '/' . basename($source);
        }

        return copy($source, $destination);
    }

    /**
     * Deletes the specified file
     *
     * @param  string $path The file path
     * @return bool  True if deleted
     */
    public function delete($path)
    {
        return unlink($path);
    }
}
