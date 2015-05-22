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

class Help extends Model {
	public function listen() {
		$uri          = $_SERVER['REQUEST_URI'];
		$split        = explode( "/", $uri );
		$last         = count( $split ) - 1;
		$admin_link   = $split[ $last ];
		$helparticles = $this->get_list();

		if ( $msg = $this->get_error() ) {
			if ( strpos( $msg, 'API Key Not Found' ) !== false ) {
				$msg .= ". Fix this <a href='options-general.php?page=options-ostoolbar'>here</a>.";
			}
			echo $msg;

			return false;
		}

		if ( $article = $this->search( $admin_link, $helparticles ) ) {
			$link = 'admin.php?page=ostoolbar&help=' . $article->id;;
			echo '<a href="javascript:ostoolbar_popup(\'' . $link . '\', \'' . $article->title . '\');">' . $article->title . '</a>';
		}

	}

	private function search( $admin_link, $helparticles ) {
		$admin_uri = $this->parse_uri( $admin_link );

		for ( $i = 0; $i < count( $helparticles ); $i ++ ) {
			$h      = $helparticles[ $i ];
			$parsed = $this->parse_uri( $h->url );
			if ( $h->url_exact ) {
				if ( $parsed['hash'] == $admin_uri['hash'] ) {
					return $h;
				}
			} elseif ( $parsed['page'] == $admin_uri['page'] ) {
				if ( ! $parsed['vars'] ) {
					return $h;
				}
				// Compare keys
				$admin_keys  = array_keys( $admin_uri['vars'] );
				$parsed_keys = array_keys( $parsed['vars'] );

				$intersect = array_intersect( $parsed_keys, $admin_keys );
				if ( count( $intersect ) == count( $parsed_keys ) ) {
					$compare = array();
					foreach ( $intersect as $index ) {
						$compare[ $index ] = $admin_uri['vars'][ $index ];
					}
					ksort( $compare );

					if ( md5( serialize( $compare ) ) == md5( serialize( $parsed['vars'] ) ) ) {
						return $h;
					}
				}

			}
		}

		return false;

	}

	protected function parse_uri( $uri ) {
		list( $page, $query ) = explode( "?", $uri );
		$vars = array();
		if ( $query ) {
			parse_str( $query, $vars );
		}

		ksort( $vars );

		$hash = $page;
		if ( $vars ) {
			$hash .= md5( serialize( $vars ) );
		}

		return compact( 'page', 'vars', 'hash' );
	}

	public function get_data() {
		$data = $this->get_list();
		for ( $i = 0; $i < count( $data ); $i ++ ) {
			$d = $data[ $i ];
			if ( $d->id == $this->get_state( 'id' ) ) {
				$d->introtext = Request::filter( $d->introtext );
				$d->fulltext  = Request::filter( $d->fulltext );

				return $d;
			}
		}

		return null;
	}

	public function get_list() {
		$data = Cache::callback( $this, 'fetch_list', array(), null, true );

		return $data;
	}

	public function fetch_list() {

		$data = array( 'resource' => 'helparticles' );

		$response = Request::make_request( $data );

		if ( $response->has_error() ) :
			$this->set_error(
				__( 'OSToolbar Error' ) . ':  '
				. $response->get_error_msg()
				. ' (' . __( 'Code' ) . ' ' . $response->get_error_code() . ')'
			);

			return false;
		endif;

		$list = $response->get_body();

		for ( $i = 0; $i < count( $list ); $i ++ ) :
			$list[ $i ]->link = 'admin.php?page=ostoolbar&id=' . $list[ $i ]->id;
		endfor;

		return $list;
	}

}
