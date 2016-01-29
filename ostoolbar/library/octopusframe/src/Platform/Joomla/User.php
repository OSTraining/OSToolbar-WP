<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\Joomla;

use OctopusFrame\Access\UserInterface;
use JFactory;

defined('OCTOPUSFRAME_LOADED') or die();


class User implements UserInterface
{
    public function getId()
    {
        return JFactory::getUser()->id;
    }
}
