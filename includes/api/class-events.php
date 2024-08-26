<?php
/**
 * API.
 *
 * @package    EventKoi
 * @subpackage EventKoi\API
 */

namespace EventKoi\API;

use EventKoi\API\REST;
use EventKoi\Core\Events as Query;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Events.
 */
class Events {

	/**
	 * Init.
	 */
	public static function init() {

		register_rest_route(
			EVENTKOI_API,
			'/events',
			array(
				'methods'             => 'get',
				'callback'            => array( __CLASS__, 'get_results' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'allow_all' ),
			)
		);
	}

	/**
	 * Get results.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function get_results( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$response = Query::get_events();

		return rest_ensure_response( $response );
	}
}
