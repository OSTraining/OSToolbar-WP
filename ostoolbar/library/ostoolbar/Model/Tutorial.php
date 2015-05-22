<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar\Model;

use Ostoolbar\Factory;
use Ostoolbar\Model;
use Ostoolbar\Request;

class Tutorial extends Model {
	protected $data = null;

	public function get_data() {
		$id = $this->get_state( 'id' );

		/** @var Tutorials $model */
		$model = Factory::get_model( 'Tutorials' );

		$tutorials = $model->get_list();
		if ( is_array( $tutorials ) ) {
			foreach ( $tutorials as $tutorial ) {
				if ( $tutorial->id == $id ) {
					$tutorial->introtext = Request::filter( $tutorial->introtext );
					$tutorial->fulltext  = Request::filter( $tutorial->fulltext );

					$this->data = $tutorial;
					break;
				}
			}
		}

		return $this->data;
	}
}
