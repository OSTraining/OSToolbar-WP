<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Platform\WordPress;

use OctopusFrame\Platform\WordPress\Cache as WPCache;
use OctopusFrame\Factory;

defined('OCTOPUSFRAME_LOADED') or die();


class Cache extends WPCache
{
    /**
     * Cleans the whole cache
     */
    public function clean()
    {
        parent::clean();

        $ignore = array(
            '.htaccess'
        );

        return Factory::getContainer()->folder->clean($this->getPath(), $ignore);
    }
}
