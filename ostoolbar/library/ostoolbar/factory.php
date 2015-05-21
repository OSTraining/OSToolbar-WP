<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined( 'ABSPATH' ) or die();

abstract class Factory {
	static $instances = array();

	public static function getInstance( $name ) {
		$key = strtolower( $name );
		if ( ! isset( self::$instances[ $key ] ) ) {
			self::$instances[ $key ] = new $name();
		}

		return self::$instances[ $key ];
	}

	public static function test() {
		echo 'This is my test';
	}
}
