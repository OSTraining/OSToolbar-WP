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
	public static $host_url = 'https://www.ostraining.com/index.php?option=com_api&v=wp';
	public static $is_trial = false;

	public static function is_trial() {
		static::$host_url = "http://www.ostraining.com/index.php?option=com_api&v=wp_trial";
		static::$is_trial  = true;
	}

	public static function makeRequest( $data ) {
		$api_key = get_option( 'api_key' );

		$static_data = array(
			'format' => 'json',
			'key'    => $api_key
		);

		if ( ! isset( $data['app'] ) ) {
			$data['app'] = 'tutorials';
		}
		$data = array_merge( $data, $static_data );

		$response = Rest\Request::send( static::$host_url, $data );

		if ( $body = $response->getBody() ) {
			$response->setBody( json_decode( $body ) );
		}

		if ( $response->hasError() ) {
			$body = $response->getBody();
			if ( isset( $body->code ) ) {
				$response->setErrorCode( $body->code );
			}
			if ( isset( $body->message ) ) {
				$response->setErrorMsg( $body->message );
			}
		}

		return $response;
	}

	public static function filter( $text ) {
		$split   = explode( 'index.php', static::$host_url );
		$ost_url = $split[0];

		$text = preg_replace( '#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="' . $ost_url . '$2$3', $text );

		return $text;
	}
}
