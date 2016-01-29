<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\WordPress;

use OctopusFrame\Registry\RegistrableInterface;

defined('OCTOPUSFRAME_LOADED') or die();


class Options implements RegistrableInterface
{
    protected $optionPrefix = '';

    /**
     * Constructor
     *
     * @param RegistrableInterface $options The options to the builder
     */
    public function __construct($optionPrefix = '')
    {
        $this->optionPrefix = $optionPrefix;
    }

    /**
     * Set a value to a config
     *
     * @param string $key   The register key
     * @param mixed  $value The value for the register
     * @param int    $lifetime The lifetime in seconds
     */
    public function set($key, $value, $lifetime = null)
    {
        update_option($this->optionPrefix . $key, $value);
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
        $value = get_option($this->optionPrefix . $key);

        return false !== $value ? $value : $default;
    }

    /**
     * Deletes a specific registred key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key)
    {
        delete_option($this->optionPrefix . $key);
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
        $value = get_option($this->optionPrefix . $key);

        return false !== $value;
    }
}
