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
 * Defines the abstract class for store of parameters
 */
class ArrayRegistry extends AbstractRegistryAdapter
{
    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        if (!is_array($storage)) {
            $storage = array();
        }

        parent::__construct($storage);
    }

    /**
     * Set a value to a register
     *
     * @param string $key   The register key
     * @param mixed  $value The value for the register
     * @param int    $lifetime The lifetime in seconds
     */
    public function set($key, $value, $lifetime = null)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Get the value of a register. If no value was found,
     * returns the value specified in the attribute $default.
     *
     * @param string $key     The register key
     * @param mixed  $default  The default value
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->storage[$key])) {
            return $this->storage[$key];
        }

        return $default;
    }

    /**
     * Deletes a specific registred key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key)
    {
        if (isset($this->storage[$key])) {
            unset($this->storage[$key]);
        }
    }

    /**
     * Cleans the whole registry
     */
    public function clean()
    {
        $this->storage = array();
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
        return array_key_exists($key, $this->storage);
    }
}
