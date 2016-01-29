<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\WordPress;

use OctopusFrame\Registry\ArrayRegistry;

defined('OCTOPUSFRAME_LOADED') or die();


class Configuration extends ArrayRegistry
{
    const OPTION_NAME = 'octp_settings';

    protected $options;

    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        $this->options = new Options;

        // Load the current options from the database
        $this->storage = $this->options->get(static::OPTION_NAME, array());

        // Check if we need to set any configuration
        if (is_array($storage)) {
            if (!empty($storage)) {
                foreach ($storage as $key => $value) {
                    parent::set($key, $value);
                }
            } else {
                $this->storage = array();
            }

            $this->update();
        }
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
        parent::set($key, $value, $lifetime);
        $this->update();
    }

    /**
     * Deletes a specific registred key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key)
    {
        parent::delete($key);
        $this->update();
    }

    /**
     * Cleans the whole registry
     */
    public function clean()
    {
        parent::clean();
        $this->update();
    }

    protected function update()
    {
        $this->options->set(static::OPTION_NAME, $this->storage);
    }
}
