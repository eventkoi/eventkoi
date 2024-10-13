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

		register_rest_route(
			EVENTKOI_API,
			'/duplicate_calendar',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'duplicate_calendar' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/delete_calendar',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'delete_calendar' ),
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

	/**
	 * Duplicate a single calendar.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function duplicate_calendar( $request ) {

		$data        = json_decode( $request->get_body(), true );
		$calendar_id = ! empty( $data['calendar_id'] ) ? absint( $data['calendar_id'] ) : 0;

		$calendar = new SingleCal( $calendar_id );
		$response = $calendar::duplicate_calendar();

		return rest_ensure_response( $response );
	}

	/**
	 * Delete a single calendar.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function delete_calendar( $request ) {

		$data        = json_decode( $request->get_body(), true );
		$calendar_id = ! empty( $data['calendar_id'] ) ? absint( $data['calendar_id'] ) : 0;
		$response    = SingleCal::delete_calendar( $calendar_id );

		return rest_ensure_response( $response );
	}
}
