<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use OctopusFrame\AutoLoader;


if (!defined('OCTOPUSFRAME_LOADED')) {
    define('OCTOPUSFRAME_LOADED', 1);
    define('OCTOPUSFRAME_VENDOR_PATH', __DIR__ . '/../vendor');
    define('OCTOPUSFRAME_LIBRARY_PATH', __DIR__);

    if (!defined('OPENSSL_RAW_DATA')) {
        define('OPENSSL_RAW_DATA', 1);
    }

    require_once OCTOPUSFRAME_VENDOR_PATH . '/defuse/php-encryption/autoload.php';
    require_once OCTOPUSFRAME_VENDOR_PATH . '/autoload.php';
    require_once OCTOPUSFRAME_LIBRARY_PATH . '/AutoLoader.php';

    AutoLoader::register('OctopusFrame', OCTOPUSFRAME_LIBRARY_PATH);
}
