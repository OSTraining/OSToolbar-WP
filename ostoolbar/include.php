<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

require_once __DIR__ . '/library/osteammate/src/include.php';

if (!defined('OSTOOLBAR_LOADED')) {
    define('OSTOOLBAR_LOADED', true);
    define('OSTOOLBAR_BASE_PATH', __DIR__);
    define('OSTOOLBAR_LIBRARY_PATH', OSTOOLBAR_BASE_PATH . '/library');
    define('OSTOOLBAR_ASSETS_PATH', OSTOOLBAR_BASE_PATH . '/assets');
    define('OSTOOLBAR_IMAGES_PATH', OSTOOLBAR_ASSETS_PATH . '/images');
    define('OSTOOLBAR_LOG_PATH', OSTOOLBAR_BASE_PATH . '/log');

    require_once 'default_token.php';
}
