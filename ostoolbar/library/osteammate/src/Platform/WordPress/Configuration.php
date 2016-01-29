<?php
/**
 * @package   OSTeammateSharedLibrary
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Platform\WordPress;

use OctopusFrame\Platform\WordPress\Configuration as BaseConfiguration;

defined('OSTEAMMATE_LOADED') or die();


class Configuration extends BaseConfiguration
{
    const OPTION_NAME = 'ostoolbar_settings';
}
