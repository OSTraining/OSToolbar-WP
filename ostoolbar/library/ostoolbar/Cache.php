<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined( 'ABSPATH' ) or die();

class Cache {
	public static $cache_group = 'ostoolbar';

	const DAY = 86400;
	const HALF_DAY = 43200;
	const HOUR = 3600;
	const MINUTE = 60;

	/**
	 * @param object $object
	 * @param string $method
	 * @param array  $args
	 * @param null   $cache_lifetime
	 *
	 * @return mixed
	 */
	public static function callback( $object, $method, $args = array(), $cache_lifetime = null ) {
		$cache = Factory::getCacheStorage();

		if ( ! $cache_lifetime ) {
			$cache_lifetime = static::HALF_DAY;
		}
		$cache->setLifetime( $cache_lifetime );

		$response = Request::makeRequest( array( 'resource' => 'checkapi' ) );
		if ( $response->hasError() ) {
			static::$cache_group = static::$cache_group . '_trial';
			Request::is_trial();
		}

		$callback = array( $object, $method );
		$cache_id = static::get_cache_id( $callback, $args );

		$data = $cache->get( $cache_id, static::$cache_group );

		if ( $data ) {
			$data     = unserialize( $data );
			$response = Request::makeRequest( array( 'resource' => 'lastupdate' ) );
			if ( ! $response->hasError() ) {
				$last_update = strtotime( $response->getBody() );
				if ( is_array( $data ) ) {
					if ( ( count( $data ) && strtotime( $data[0]->last_update_date ) < $last_update )
					     || count( $data ) == 0
					) {
						$cache->remove( $cache_id, static::$cache_group );
						$data = call_user_func_array( $callback, $args );

						if ( $data !== false ) {
							$cached = trim( serialize( $data ) );
							$cache->store( $cache_id, static::$cache_group, $cached );
						}

						return $data;
					}
				}
			}

			return $data;

		} else {
			$data = call_user_func_array( $callback, $args );

			if ( $data !== false ) {
				$cached = trim( serialize( $data ) );
				$cache->store( $cache_id, static::$cache_group, $cached );
			}

			return $data;
		}
	}

	/**
	 * @param mixed $callback
	 * @param mixed $args
	 *
	 * @return string
	 */
	protected function get_cache_id( $callback, $args ) {
		if ( is_array( $callback ) && is_object( $callback[0] ) ) {
			$vars        = get_object_vars( $callback[0] );
			$vars[]      = strtolower( get_class( $callback[0] ) );
			$callback[0] = $vars;
		}

		return md5( serialize( array( $callback, $args, static::$cache_group ) ) );
	}
}
