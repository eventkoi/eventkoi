<?php
/**
 * API.
 *
 * @package    EventKoi
 * @subpackage EventKoi\API
 */

namespace EventKoi\API;

use EventKoi\API\REST;
use EventKoi\Core\Event as SingleEvent;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Event.
 */
class Event {

	/**
	 * Init.
	 */
	public static function init() {

		register_rest_route(
			EVENTKOI_API,
			'/event',
			array(
				'methods'             => 'get',
				'callback'            => array( __CLASS__, 'get_result' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'allow_all' ),
			)
		);
	}

	/**
	 * Get results.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function get_result( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$id       = $request->get_param( 'id' );
		$event    = new SingleEvent( $id );
		$response = $event::get_meta();

		return rest_ensure_response( $response );
	}
}
