<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace Ostoolbar\Rest;

defined( 'ABSPATH' ) or die();

class Response {
	/**
	 * @var array
	 */
	protected $headers = array();

	/**
	 * @var string
	 */
	protected $body = null;

	/**
	 * @var array
	 */
	protected $info = array();

	/**
	 * @var Error
	 */
	protected $error = null;

	protected static $http_codes = Array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	);

	public function __construct( $handle, $receive_headers ) {
		$this->error = new Error();

		$this->process_request( $handle, $receive_headers );
	}

	/**
	 * @param resource $handle
	 * @param bool     $receive_headers
	 *
	 * @return void
	 */
	protected function process_request( $handle, $receive_headers ) {
		$response = curl_exec( $handle );

		if ( $receive_headers ) {
			list( $headers, $body ) = explode( "\r\n\r\n", $response, 2 );
			$this->set_headers( $headers );
		} else {
			$body = $response;
		}

		$this->set_body( $body );
		$this->set_info( curl_getinfo( $handle ) );
		$this->set_errors( $handle );
		curl_close( $handle );
	}

	/**
	 * @param resource $handle
	 *
	 * @return void
	 */
	public function set_errors( $handle ) {
		$this->error = new Error();

		if ( $error_code = curl_errno( $handle ) ) {
			$this->error->code    = $error_code;
			$this->error->message = curl_error( $handle );

		} else {
			$code = $this->get_info( 'http_code' );
			if ( $code >= 400 && $code < 600 ) {
				$this->error->code    = $code;
				$this->error->message = $this->get_status_message( $code );
			}
		}
	}

	/**
	 * @return bool
	 */
	public function has_error() {
		return ( $this->error && $this->error->code );
	}

	/**
	 * @return int
	 */
	public function get_error_code() {
		return $this->error->code;
	}

	/**
	 * @param int $code
	 *
	 * @return int
	 */
	public function set_error_code( $code ) {
		$this->error->code = $code;

		return $this->error->code;
	}

	/**
	 * @return string
	 */
	public function get_error_msg() {
		return $this->error->message;
	}

	/**
	 * @param string $msg
	 *
	 * @return string
	 */
	public function set_error_msg( $msg ) {
		$this->error->message = $msg;

		return $this->error->message;
	}

	/**
	 * @return mixed
	 */
	public function get_body() {
		return $this->body;
	}

	/**
	 * @param string $body
	 *
	 * @return string
	 */
	public function set_body( $body ) {
		$this->body = $body;

		return $this->body;
	}

	/**
	 * @param array $info
	 *
	 * @return array
	 */
	protected function set_info( $info ) {
		$this->info = $info;

		return $this->info;
	}

	/**
	 * @param string $headers
	 *
	 * @return array
	 */
	protected function set_headers( $headers ) {
		$this->headers = explode( "\r\n", $headers );

		return $this->headers;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_info( $key = null ) {
		if ( $key ) {
			if ( isset( $this->info[ $key ] ) ) {
				return $this->info[ $key ];
			}
		} else {
			return $this->info;
		}

		return null;
	}

	/**
	 * @param $status
	 *
	 * @return null|string
	 */
	public static function get_status_message( $status ) {
		if ( isset( static::$http_codes[ $status ] ) ) {
			return static::$http_codes[ $status ];
		}

		return null;
	}

}
