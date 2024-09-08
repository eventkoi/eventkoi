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
	 * A public API request. No checks required.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function public_api( $request ) {

		if ( empty( $request ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Only super-admins and users with valid API key can do this.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function private_api( $request ) {

		if ( empty( $request ) ) {
			return false;
		}

		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}
}
