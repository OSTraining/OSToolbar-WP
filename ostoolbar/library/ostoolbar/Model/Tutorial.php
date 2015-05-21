<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar\Model;

use Ostoolbar\Model;

class Tutorial extends \Ostoolbar\Model {
	protected $data = null;

	public function getData() {
		$id        = $this->getState( 'id' );
		$tmodel    = \Ostoolbar\Factory::getModel( 'Tutorials' );
		$tutorials = $tmodel->getList();

		foreach ( @$tutorials as $t ) {
			if ( $t->id == $id ) {
				$this->data            = $t;
				$this->data->introtext = OST_RequestHelper::filter( $this->data->introtext );
				$this->data->fulltext  = OST_RequestHelper::filter( $this->data->fulltext );
				break;
			}
		}

		return $this->data;
	}
}
