<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined( 'ABSPATH' ) or die();

abstract class Request {
	public static $host_url = 'https://www.ostraining.com/';
	public static $is_trial = false;

	public static function get_host_url() {
		$trial   = self::$is_trial ? '_trial' : '';

		$vars = array(
			'option' => 'com_api',
			'v'      => 'wp' . $trial
		);

		return self::$host_url . 'index.php?' . http_build_query( $vars );
	}

	public static function is_trial() {
		static::$is_trial = true;
	}

	public static function make_request( $data ) {
		$api_key = get_option( 'api_key' );

		$static_data = array(
			'format' => 'json',
			'key'    => $api_key
		);

		if ( ! isset( $data['app'] ) ) {
			$data['app'] = 'tutorials';
		}
		$data = array_merge( $data, $static_data );

		$response = Rest\Request::send( static::get_host_url(), $data );

		if ( $body = $response->get_body() ) {
			$response->set_body( json_decode( $body ) );
		}

		if ( $response->has_error() ) {
			$body = $response->get_body();
			if ( isset( $body->code ) ) {
				$response->set_error_code( $body->code );
			}
			if ( isset( $body->message ) ) {
				$response->set_error_msg( $body->message );
			}
		}

		return $response;
	}

	public static function filter( $text ) {
		$split   = explode( 'index.php', static::get_host_url() );
		$ost_url = $split[0];

		$text = preg_replace( '#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="' . $ost_url . '$2$3', $text );

		return $text;
	}
}
