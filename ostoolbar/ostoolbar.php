<?php
/*
Plugin Name: OSToolbar
Plugin URI: http://www.ostraining.com/ostoolbar/
Description: This plugin shows training videos inside your WordPress admin panel.
Author: OSTraining.com
Version: 4.0.0
Author URI: http://www.ostraining.com
*/
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2016 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use OctopusFrame\Registry\ArrayRegistry;
use OctopusFrame\Factory;
use OSTeammate\Platform\WordPress\Client;
use OSTeammate\Services;

defined('ABSPATH') or die();

require_once __DIR__ . '/include.php';

$options = new ArrayRegistry(
    array(
        'platform'  => 'WordPress',
        'cachePath' => realpath(__DIR__ . '/cache'),
        'logPath'   => realpath(__DIR__ . '/log'),
        'services'  => new Services
    )
);
$container = Factory::getContainer($options);
$container->client->init();
