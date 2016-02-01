<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\WordPress;

use OctopusFrame\Access\UserInterface;

defined('OCTOPUSFRAME_LOADED') or die();


class User implements UserInterface
{
    public function getId()
    {
        return get_current_user_id();
    }

    /**
     * Returns true if the user has the specificied capability.
     *
     * @param  string $capability The capability
     * @return bool               True if capable
     */
    public function can($capability)
    {
        return current_user_can($capability);
    }
}
