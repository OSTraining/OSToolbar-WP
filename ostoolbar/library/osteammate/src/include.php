<?php
/**
 * @package   OSTeammate
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use OctopusFrame\AutoLoader;


if (!defined('OSTEAMMATE_LOADED')) {
    define('OSTEAMMATE_LOADED', 1);
    // define('OSTEAMMATE_VENDOR_PATH', __DIR__ . '/../vendor');
    define('OSTEAMMATE_LIBRARY_PATH', __DIR__);

    // Include the OctopusFrame
    require_once dirname(dirname(__DIR__)) . '/octopusframe/src/include.php';

    // require_once OSTEAMMATE_VENDOR_PATH . '/autoload.php';

    AutoLoader::register('OSTeammate', OSTEAMMATE_LIBRARY_PATH);
}
