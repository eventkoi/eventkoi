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
	 * This is for endpoints that should be publicly accessible.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function allow_all( $request ) {

		if ( empty( $request ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Only logged in users and admins.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function allow_super_admins( $request ) {

		if ( empty( $request ) ) {
			return false;
		}

		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}
}
