<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\Joomla;

use OctopusFrame\Registry\AbstractRegistryAdapter;
use OctopusFrame\Registry\RegistrableInterface;
use JFactory;
use DateTime;
use DateInterval;

defined('OCTOPUSFRAME_LOADED') or die();


class Cache extends AbstractRegistryAdapter
{
    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        parent::__construct($storage);

        $name     = $options->get('name', 'octopusframe');
        $lifetime = $options->get('lifetime', 1440); // Default is 24h

        $this->storage = JFactory::getCache($name, null);
        $this->storage->setCaching(true);
        $this->storage->setlifetime($lifetime);
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
        // Store the lifetime data for the cache, if set
        if (!is_null($lifetime)) {
            $now = new DateTime('now');

            $lifetimeData = array(
                'lifetime'  => $lifetime,
                'cached_on' => $now->format('Y-m-d H:i:s')
            );
            $this->storage->store($lifetimeData, $key . '.lifetime');
        }

        // Store the data
        $this->storage->store($value, $key);
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
        $value       = $this->storage->get($key);
        $lifetimeKey = $key . '.lifetime';
        // Check the lifetime, if set
        $lifetimeData = $this->storage->get($lifetimeKey);
        if (!is_null($lifetimeData) && is_array($lifetimeData)) {
            $now    = new DateTime('now');
            $expire = new DateTime($lifetimeData['cached_on']);

            $dateInterval = new DateInterval('PT' . $lifetimeData['lifetime'] . 'M'); // Minutes
            $expire->add($dateInterval);

            if ($expire < $now) {
                // Expired!
                $value = null;
                $this->delete($key);
                $this->delete($lifetimeKey);
            }
        }

        // Check if need to use default value
        if (empty($value)) {
            return $default;
        }

        return $value;
    }

    /**
     * Deletes a specific registred key
     *
     * @param  string $key The cache key to be deleted
     */
    public function delete($key)
    {
        $this->storage->remove($key);
    }

    /**
     * Cleans the whole registry
     */
    public function clean()
    {
        $this->storage->clean();
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
        $fakeValue = md5('notfound' . microtime());

        return $this->get($key, $fakeValue) !== $fakeValue;
    }
}
