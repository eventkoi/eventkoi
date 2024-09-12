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
				'permission_callback' => array( '\EventKoi\API\REST', 'public_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/update_event',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'update_event' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/restore_event',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'restore_event' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/duplicate_event',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'duplicate_event' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);

		register_rest_route(
			EVENTKOI_API,
			'/delete_event',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'delete_event' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);
	}

	/**
	 * Get a single event.
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

	/**
	 * Update a single event.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function update_event( $request ) {

		if ( empty( $request ) ) {
			die( -1 );
		}

		$data = json_decode( $request->get_body(), true );

		$event  = ! empty( $data['event'] ) ? $data['event'] : null;
		$status = ! empty( $data['status'] ) ? $data['status'] : 'draft';

		$query    = new SingleEvent( $event['id'] );
		$response = $query::update( $event, $status );

		return rest_ensure_response( $response );
	}

	/**
	 * Restore a single event.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function restore_event( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$event_id = ! empty( $data['event_id'] ) ? absint( $data['event_id'] ) : 0;
		$response = SingleEvent::restore_event( $event_id );

		return rest_ensure_response( $response );
	}

	/**
	 * Duplicate a single event.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function duplicate_event( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$event_id = ! empty( $data['event_id'] ) ? absint( $data['event_id'] ) : 0;

		$event    = new SingleEvent( $event_id );
		$response = $event::duplicate_event();

		return rest_ensure_response( $response );
	}

	/**
	 * Delete a single event.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function delete_event( $request ) {

		$data     = json_decode( $request->get_body(), true );
		$event_id = ! empty( $data['event_id'] ) ? absint( $data['event_id'] ) : 0;
		$response = SingleEvent::delete_event( $event_id );

		return rest_ensure_response( $response );
	}
}
