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

	/**
	 * Main application object
	 *
	 * @return Application
	 */
	public static function getApplication() {
		return static::getInstance( '\Ostoolbar\Application' );
	}

	/**
	 * @return Configuration
	 */
	public static function getConfiguration() {
		return static::getInstance('\Ostoolbar\Configuration');
	}


	/**
	 * Generic factory method
	 *
	 * @param $class_name
	 *
	 * @return mixed
	 */
	protected static function getInstance( $class_name ) {
		$key = md5( $class_name );
		if ( ! isset( static::$instances[ $key ] ) ) {
			static::$instances[ $key ] = new $class_name();
		}

		return static::$instances[ $key ];
	}

}
