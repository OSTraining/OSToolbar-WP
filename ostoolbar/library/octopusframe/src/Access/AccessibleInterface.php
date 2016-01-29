<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Access;

defined('OCTOPUSFRAME_LOADED') or die();


/**
 * Interface for the OSTeammate access controller
 */
interface AccessibleInterface
{
    /**
     * Returns true if the context is allowed to the current user
     *
     * @param  string  $context The context we want to check
     *
     * @return boolean          True is has access
     */
    public function hasAccess($context);
}
