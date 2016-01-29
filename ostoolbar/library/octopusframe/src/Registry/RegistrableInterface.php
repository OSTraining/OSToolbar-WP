<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Registry;

defined('OCTOPUSFRAME_LOADED') or die();


/**
 * Interface for the OSTeammate registries, cache
 */
interface RegistrableInterface
{
    /**
     * Sets a value to a key. Can set the lifetime of this cached information.
     *
     * @param string $key      The cache key
     * @param mixed  $value    The value to be cached
     * @param int    $lifetime The lifetime in seconds
     */
    public function set($key, $value, $lifetime = null);

    /**
     * Gets a cached value specified by the given key
     *
     * @param  string $key The cache key
     * @param  mixed  $default The default value
     *
     * @return mixed       The cached value, if found
     */
    public function get($key, $default = null);

    /**
     * Deletes a specific cache key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key);

    /**
     * Cleans the whole cache
     */
    public function clean();

    /**
     * Returns true if the registry key is set
     *
     * @param string $key The key you are looking for
     *
     * @return bool True, if is set
     */
    public function exists($key);
}
