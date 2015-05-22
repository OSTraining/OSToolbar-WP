<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar\Rest;

defined( 'ABSPATH' ) or die();

class Request {
	/**
	 * @param string       $url
	 * @param string|array $data
	 * @param string       $method
	 * @param array        $curl_options
	 *
	 * @return Response
	 */
	public static function send( $url, $data = null, $method = 'GET', $curl_options = array() ) {
		$data   = self::prepare_data( $data );
		$handle = curl_init();

		self::set_curl_option( $handle, CURLOPT_RETURNTRANSFER, true, $curl_options );

		switch ( $method ) {
			case 'POST':
				curl_setopt( $handle, CURLOPT_POST, true );
				if ( $data ) {
					curl_setopt( $handle, CURLOPT_POSTFIELDS, $data );
				}
				break;

			case 'GET':
				$divider = strpos( $url, '?' ) === false ? '?' : '&';
				$url .= $divider . $data;
				break;
		}
		curl_setopt( $handle, CURLOPT_URL, $url );

		if ( ! empty( $curl_options ) ) {
			foreach ( $curl_options as $option => $value ) {
				curl_setopt( $handle, $option, $value );
			}
		}

		$receive_headers = isset( $curl_options[ CURLOPT_HEADER ] );
		$response        = new Response( $handle, $receive_headers );

		return $response;
	}

	/**
	 * @param resource $handle
	 * @param int      $name
	 * @param mixed    $value
	 * @param array    $curl_options
	 *
	 * @return bool
	 */
	protected static function set_curl_option( $handle, $name, $value, $curl_options = array() ) {
		$options = array_keys( $curl_options );
		if ( in_array( $name, $options ) ) {
			return false;
		} else {
			curl_setopt( $handle, $name, $value );

			return true;
		}
	}

	/**
	 * @param string|array $data
	 *
	 * @return string
	 */
	protected static function prepare_data( $data ) {
		if ( is_array( $data ) ) {
			$data = http_build_query( $data );
		}

		return $data;
	}
}
