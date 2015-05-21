<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar\Model;

use Ostoolbar\Model;

class HelpPage extends Model {
	protected $option = null;
	protected $view = null;
	protected $context = null;
	protected $pagination = null;

	protected $list = null;
	protected $total = null;

	public function getData() {
		$data = OST_Cache::callback( $this, '_fetchList', array(), null, true );

		return $data;
	}

	public function _fetchList() {

		$data = array( 'resource' => 'help' );

		$response = OST_RequestHelper::makeRequest( $data );

		if ( $response->hasError() ) :
			$this->setError( __( 'OSToolbar Error' ) . ':  ' . $response->getErrorMsg() . ' (' . __( 'Code' ) . ' ' . $response->getErrorCode() . ')' );

			return false;
		endif;

		$list = $response->getBody();

		return $list;
	}

}
