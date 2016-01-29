<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Model;

defined('OCTOPUSFRAME_LOADED') or die();


interface StatableInterface
{
    /**
     * Set a value to the specified state
     *
     * @param string|int $state The state name
     * @param mixed      $value The state value
     */
    public function setState($state, $value);

    /**
     * Get the value of the specified state or the default value, if not found.
     *
     * @param  string|int $state   The state name
     * @param  mixed      $default The default value
     *
     * @return mixed               The state value or default value
     */
    public function getState($state, $default = null);
}
