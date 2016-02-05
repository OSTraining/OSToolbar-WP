<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2016 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// This token give access to WP free training only
if (!defined('OSTOOLBAR_DEFAULT_TOKEN')) {
    define('OSTOOLBAR_DEFAULT_TOKEN', 'znfdjlozqed5z5miyh8ew6ekkms7cik9');
}

// Specify if should display the ad for partners in the admin
if (!defined('OSTOOLBAR_DEFAULT_TRAINING_AD_PARTNER')) {
    define('OSTOOLBAR_DEFAULT_TRAINING_AD_PARTNER', true);
}

// Specify the training provider
if (!defined('OSTOOLBAR_DEFAULT_TRAINING_PROVIDER')) {
    define('OSTOOLBAR_DEFAULT_TRAINING_PROVIDER', 'OSTraining');
    define('OSTOOLBAR_DEFAULT_TRAINING_PROVIDER_SITE', 'https://www.ostraining.com');
    define('OSTOOLBAR_DEFAULT_TRAINING_PROVIDER_CONTACT', 'mailto:contact@ostraining.com');
}
