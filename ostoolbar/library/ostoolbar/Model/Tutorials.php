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

defined( 'ABSPATH' ) or die();

class Tutorials extends Model {
	protected $option = null;
	protected $view = null;
	protected $context = null;
	protected $pagination = null;

	protected $list = null;
	protected $total = null;

	public function get_list() {
		$data = Cache::callback( $this, 'fetch_list', array(), null, true );

		$videos = preg_split( "/,/", get_option( 'videos' ), - 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $videos ) ) {
			$temp = array();
			foreach ( $videos as $item ) {
				foreach ( $data as $row ) {
					if ( $row->id == $item ) {
						$temp[] = $row;
						break;
					}
				}
			}
			$data = $temp;
		}

		return $data;
	}

	public function fetch_list() {

		$data = array( 'resource' => 'articles' );

		$response = Request::make_request( $data );

		if ( $response->has_error() ) {
			wp_die( __( 'OSToolbar Error' ) . ': ' . __( 'Please enter an API key in the Setting > OSToolbar.' ) );

			return false;
		}

		$list = $response->get_body();

		for ( $i = 0; $i < count( $list ); $i ++ ) {
			$list[ $i ]->link = 'admin.php?page=ostoolbar&id=' . $list[ $i ]->id;
		}

		return $list;
	}
}
