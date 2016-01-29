<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\Joomla;

use OctopusFrame\Access\AbstractAccess;
use JFactory;

defined('OCTOPUSFRAME_LOADED') or die();


/**
 * Joomla access controller
 */
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
        return JFactory::getUser()->authorise('courses.view', 'com_osteammatejoomla');
    }
}
