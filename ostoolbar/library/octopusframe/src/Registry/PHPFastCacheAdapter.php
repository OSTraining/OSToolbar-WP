<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Registry;

defined('OCTOPUSFRAME_LOADED') or die();

use OctopusFrame\Registry\RegistryInterface;
use phpfastcache;


/**
 * Class for the File cache
 */
class PHPFastCacheAdapter extends AbstractRegistryAdapter implements RegistrableInterface
{
    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        if (empty($storage)) {
            $storage = phpfastcache();
        }

        parent::__construct($storage);
    }

    /**
     * Sets a value to a key. Can set the lifetime of this cached information.
     *
     * @param string $key      The cache key
     * @param mixed  $value    The value to be cached
     * @param int    $lifetime The lifetime in seconds
     */
    public function set($key, $value, $lifetime = null)
    {
        $this->storage->set($key, $value, $lifetime);
    }

    /**
     * Gets a cached value specified by the given key
     *
     * @param  string $key The cache key
     * @param  mixed  $default The default value
     *
     * @return mixed       The cached value, if found
     */
    public function get($key, $default = null)
    {
        $value = $this->storage->get($key);

        if ($value === null) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Deletes a specific cache key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key)
    {
        return $this->storage->delete($key);
    }

    /**
     * Cleans the whole cache
     */
    public function clean()
    {
        return $this->storage->clean();
    }

    /**
     * Returns true if the registry key is set
     *
     * @param string $key The key you are looking for
     *
     * @return bool True, if is set
     */
    public function exists($key)
    {
        return $this->storage->isExisting($key);
    }
}
