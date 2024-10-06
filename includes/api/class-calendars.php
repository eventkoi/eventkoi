<?php
/**
 * API.
 *
 * @package    EventKoi
 * @subpackage EventKoi\API
 */

namespace EventKoi\API;

use EventKoi\API\REST;
use EventKoi\Core\Calendars as Query;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calendars.
 */
class Calendars {

	/**
	 * Init.
	 */
	public static function init() {

		register_rest_route(
			EVENTKOI_API,
			'/calendars',
			array(
				'methods'             => 'get',
				'callback'            => array( __CLASS__, 'get_calendars' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'public_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/delete_calendars',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'delete_calendars' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/duplicate_calendars',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'duplicate_calendars' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);
	}

	/**
	 * Get calendars.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function get_calendars( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$response = Query::get_calendars();

		return rest_ensure_response( $response );
	}

	/**
	 * Delete calendars.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function delete_calendars( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$ids      = ! empty( $data['ids'] ) ? array_map( 'intval', $data['ids'] ) : null;
		$response = Query::delete_calendars( $ids );

		return rest_ensure_response( $response );
	}

	/**
	 * Duplicate calendars.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function duplicate_calendars( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$ids      = ! empty( $data['ids'] ) ? array_map( 'intval', $data['ids'] ) : null;
		$response = Query::duplicate_calendars( $ids );

		return rest_ensure_response( $response );
	}
}
