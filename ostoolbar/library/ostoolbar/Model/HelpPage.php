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

	public function getData() {
		$data = Cache::callback( $this, '_fetchList', array(), null, true );

		return $data;
	}

	public function _fetchList() {

		$data = array( 'resource' => 'help' );

		$response = Request::makeRequest( $data );

		if ( $response->hasError() ) :
			$this->setError( __( 'OSToolbar Error' ) . ':  ' . $response->getErrorMsg() . ' (' . __( 'Code' ) . ' ' . $response->getErrorCode() . ')' );

			return false;
		endif;

		$list = $response->getBody();

		return $list;
	}

}
