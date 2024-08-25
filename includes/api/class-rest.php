<?php
/**
 * API.
 *
 * @package    EventKoi
 * @subpackage EventKoi\API
 */

namespace EventKoi\API;

use EventKoi\API\Events;
use EventKoi\API\Event;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST.
 */
class REST {

	/**
	 * Init.
	 */
	public static function init() {

		add_action( 'rest_api_init', array( __CLASS__, 'create_rest_routes' ) );
	}

	/**
	 * Create rest routes.
	 */
	public static function create_rest_routes() {

		// Init API endpoints.
		Events::init();
		Event::init();
	}

	/**
	 * Validate user authentication.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function authenticate( $request ) {

		if ( empty( $request ) ) {
			return false;
		}

		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate user admin.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function authenticate_as_admin( $request ) {

		if ( empty( $request ) ) {
			return false;
		}

		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}
}
