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
				'permission_callback' => array( '\EventKoi\API\REST', 'public_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/get_event_counts',
			array(
				'methods'             => 'get',
				'callback'            => array( __CLASS__, 'get_event_counts' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'public_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/delete_events',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'delete_events' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/remove_events',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'remove_events' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/restore_events',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'restore_events' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
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

		$status = $request->get_param( 'status' );

		$response = Query::get_events( array( 'status' => $status ) );

		return rest_ensure_response( $response );
	}


	/**
	 * Get events counts.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function get_event_counts( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$response = Query::get_counts();

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

	/**
	 * Remove events.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function remove_events( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$ids      = ! empty( $data['ids'] ) ? array_map( 'intval', $data['ids'] ) : null;
		$response = Query::remove_events( $ids );

		return rest_ensure_response( $response );
	}

	/**
	 * Restore events.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function restore_events( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$ids      = ! empty( $data['ids'] ) ? array_map( 'intval', $data['ids'] ) : null;
		$response = Query::restore_events( $ids );

		return rest_ensure_response( $response );
	}
}
