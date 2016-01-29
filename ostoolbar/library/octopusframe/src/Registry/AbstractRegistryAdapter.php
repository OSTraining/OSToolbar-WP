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
abstract class AbstractRegistryAdapter implements RegistrableInterface
{
    /**
     * The parameters storage
     *
     * @var mixed
     */
    protected $storage;

    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        $this->storage = $storage;
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

    }

    /**
     * Deletes a specific registred key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key)
    {

    }

    /**
     * Cleans the whole registry
     */
    public function clean()
    {

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
        return false;
    }
}
