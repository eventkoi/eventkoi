<?php
/**
 * API.
 *
 * @package    EventKoi
 * @subpackage EventKoi\API
 */

namespace EventKoi\API;

use EventKoi\API\REST;
use EventKoi\Core\Calendar as SingleCal;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calendar.
 */
class Calendar {

	/**
	 * Init.
	 */
	public static function init() {

		register_rest_route(
			EVENTKOI_API,
			'/calendar',
			array(
				'methods'             => 'get',
				'callback'            => array( __CLASS__, 'get_calendar' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'public_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/update_calendar',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'update_calendar' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);
	}

	/**
	 * Get a single calendar.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function get_calendar( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$id       = $request->get_param( 'id' );
		$calendar = new SingleCal( $id );
		$response = $calendar::get_meta();

		return rest_ensure_response( $response );
	}

	/**
	 * Update a single calendar.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function update_calendar( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$data = json_decode( $request->get_body(), true );

		$calendar = ! empty( $data['calendar'] ) ? $data['calendar'] : null;

		$query    = new SingleCal( $calendar['id'] );
		$response = $query::update( $calendar );

		return rest_ensure_response( $response );
	}
}
