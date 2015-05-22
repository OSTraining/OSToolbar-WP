<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar\Model;

use Ostoolbar\Cache;
use Ostoolbar\Model;
use Ostoolbar\Request;

class HelpPage extends Model {
	protected $option = null;
	protected $view = null;
	protected $context = null;
	protected $pagination = null;

	protected $list = null;
	protected $total = null;

	public function get_data() {
		$data = Cache::callback( $this, 'fetch_list', array(), null, true );

		return $data;
	}

	public function fetch_list() {

		$data = array( 'resource' => 'help' );

		$response = Request::make_request( $data );

		if ( $response->has_error() ) :
			$this->set_error( __( 'OSToolbar Error' ) . ':  ' . $response->get_error_msg() . ' (' . __( 'Code' ) . ' ' . $response->get_error_code() . ')' );

			return false;
		endif;

		$list = $response->get_body();

		return $list;
	}

}
