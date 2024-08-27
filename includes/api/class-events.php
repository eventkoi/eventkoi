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
				'callback'            => array( __CLASS__, 'get_events' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'allow_all' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/delete_events',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'delete_events' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'allow_super_admins' ),
			)
		);
	}

	/**
	 * Get events.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function get_events( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$response = Query::get_events();

		return rest_ensure_response( $response );
	}

	/**
	 * Delete events.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function delete_events( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$ids      = ! empty( $data['ids'] ) ? array_map( 'intval', $data['ids'] ) : null;
		$response = Query::delete_events( $ids );

		return rest_ensure_response( $response );
	}
}
