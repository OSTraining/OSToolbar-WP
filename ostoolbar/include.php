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
    define('OSTOOLBAR_BASE', __DIR__);
    define('OSTOOLBAR_LIBRARY', OSTOOLBAR_BASE . '/library');
    define('OSTOOLBAR_ASSETS', OSTOOLBAR_BASE . '/assets');
    define('OSTOOLBAR_IMAGES', OSTOOLBAR_ASSETS . '/images');
}
