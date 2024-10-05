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
use EventKoi\API\Uploads;
use EventKoi\API\Calendars;

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
	public function __construct() {

		add_action( 'init', array( __CLASS__, 'register_api_key' ), 1 );
		add_action( 'rest_api_init', array( __CLASS__, 'create_rest_routes' ) );
	}

	/**
	 * Register API keys.
	 */
	public static function register_api_key() {
		if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$user_id = get_current_user_id();
		$has_key = get_user_meta( $user_id, '_eventkoi_api_key_set', true );

		if ( ! $has_key ) {
			update_user_meta( $user_id, '_eventkoi_api_key', self::generate_api_key() );
			update_user_meta( $user_id, '_eventkoi_api_key_set', 'yes' );
		}
	}

	/**
	 * Generate API key.
	 */
	public static function generate_api_key() {
		global $current_user;

		return strtolower( $current_user->user_login ) . '_' . bin2hex( random_bytes( 16 ) );
	}

	/**
	 * Create rest routes.
	 */
	public static function create_rest_routes() {

		// Init API endpoints.
		Events::init();
		Event::init();
		Uploads::init();
		Calendars::init();
	}

	/**
	 * Get API key.
	 */
	public static function get_api_key() {
		return get_user_meta( get_current_user_id(), '_eventkoi_api_key', true );
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
		global $current_user;

		$headers = $request->get_headers();

		if ( empty( $headers ) || empty( $headers['eventkoi_api_key'] ) ) {
			return false;
		}

		$api_key = $headers['eventkoi_api_key'][0];

		if ( ! empty( $api_key ) ) {

			if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
				if ( str_starts_with( $api_key, $current_user->user_login ) ) {
					$user_key = get_user_meta( $current_user->ID, '_eventkoi_api_key', true );
					if ( strtolower( $user_key ) === strtolower( $api_key ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}
}
