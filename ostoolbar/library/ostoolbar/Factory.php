<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined( 'ABSPATH' ) or die();

/**
 * Class Factory
 *
 * @package Ostoolbar
 */
abstract class Factory {
	static $instances = array();

	/**
	 * Main application object
	 *
	 * @return Application
	 */
	public static function get_application() {
		return static::get_instance( '\Ostoolbar\Application' );
	}

	/**
	 * @return Configuration
	 */
	public static function get_configuration() {
		return static::get_instance('\Ostoolbar\Configuration');
	}

	/**
	 * @return Controller
	 */
	public static function get_controller() {
		return static::get_instance('\Ostoolbar\Controller');
	}

	/**
	 * @return Sanitize
	 */
	public static function get_sanitize() {
		return static::get_instance('\Ostoolbar\Sanitize');
	}

	/**
	 * @param $name
	 *
	 * @return Model
	 */
	public static function get_model($name)
	{
		return static::get_instance('\Ostoolbar\Model\\' . $name);
	}

	/**
	 * @return Cache\StorageFile
	 */
	public static function get_cache_storage()
	{
		return static::get_instance('\Ostoolbar\Cache\StorageFile');
	}

	/**
	 * Generic factory method
	 *
	 * @param $class_name
	 *
	 * @return mixed
	 */
	protected static function get_instance( $class_name ) {
		$key = md5( $class_name );
		if ( ! isset( static::$instances[ $key ] ) ) {
			static::$instances[ $key ] = new $class_name();
		}

		return static::$instances[ $key ];
	}

}
