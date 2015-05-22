<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined( 'ABSPATH' ) or die();

class Model {
	protected $state = array();
	protected $errors = array();

	public function set_state( $key, $value ) {
		$this->state[ $key ] = $value;

		return true;
	}

	public function get_state( $key ) {
		if ( isset( $this->state[ $key ] ) ) {
			return $this->state[ $key ];
		} else {
			return false;
		}
	}

	public function set_error( $msg ) {
		$this->errors[] = $msg;
	}

	public function get_error( $all = false ) {
		if ( empty( $this->errors ) ) {
			return false;
		}

		if ( $all ) {
			return $this->errors;
		}

		$last = count( $this->errors ) - 1;

		return $this->errors[ $last ];
	}
}
