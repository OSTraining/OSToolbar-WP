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
	public static function send( $url, $data = null, $method = 'GET', $curl_options = array() ) {
		$data   = self::prepareData( $data );
		$handle = curl_init();

		self::setCurlOption( $handle, CURLOPT_RETURNTRANSFER, true, $curl_options );

		if ( $method == 'POST' ) :
			curl_setopt( $handle, CURLOPT_POST, true );
			if ( $data ) :
				curl_setopt( $handle, CURLOPT_POSTFIELDS, $data );
			endif;
		elseif ( $method == 'GET' ) :
			if ( strpos( $url, "?" ) !== false ) :
				$url .= "&" . $data;
			else :
				$url .= "?" . $data;
			endif;
		endif;

		curl_setopt( $handle, CURLOPT_URL, $url );

		if ( ! empty( $curl_options ) ) :
			foreach ( $curl_options as $option => $value ) :
				curl_setopt( $handle, $option, $value );
			endforeach;
		endif;

		$receive_headers = isset( $curl_options[ CURLOPT_HEADER ] );
		$response        = new RestResponse( $handle, $receive_headers );

		return $response;
	}

	private static function setCurlOption( &$handle, $name, $value, $curl_options = array() ) {
		$options = array_keys( $curl_options );
		if ( in_array( $name, $options ) ) :
			return false;
		else :
			curl_setopt( $handle, $name, $value );

			return true;
		endif;
	}

	private static function prepareData( $data ) {
		if ( is_array( $data ) ) :
			$data = http_build_query( $data );
		endif;

		return $data;
	}

}

