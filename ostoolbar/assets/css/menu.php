<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
use Ostoolbar\Factory;
use Ostoolbar\Sanitize;

defined( 'ABSPATH' ) or die();

$path = Factory::getSanitize()->getFileName('icon', Sanitize::HASH_REQUEST);

header("Content-Type:text/css");
?>
li.toplevel_page_ostoolbar .wp-menu-image a img
{
	display:none;
}

li.toplevel_page_ostoolbar .wp-menu-image
{
	background:url(../images/<?php echo $path; ?>) no-repeat;
	background-position:0 -32px;
}

li.current.toplevel_page_ostoolbar .wp-menu-image, li.toplevel_page_ostoolbar:hover .wp-menu-image
{
	background-position:0 0;
}
