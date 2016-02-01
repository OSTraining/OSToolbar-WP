<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\WordPress;

use OctopusFrame\Access\AbstractAccess;
use OctopusFrame\Factory;

defined('OCTOPUSFRAME_LOADED') or die();


class Access extends AbstractAccess
{
    /**
     * Returns true if the context is allowed to the current user
     *
     * @param  string  $context The context we want to check
     *
     * @return boolean          True is has access
     */
    public function hasAccess($context)
    {
        // Give access to all views
        if (preg_match('/^view\./', $context)) {
            return true;
        }

        return Factory::getContainer()->user->can($context);
    }
}
