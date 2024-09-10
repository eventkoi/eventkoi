<?php
/**
 * API.
 *
 * @package    EventKoi
 * @subpackage EventKoi\API
 */

namespace EventKoi\API;

use EventKoi\API\REST;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Uploads.
 */
class Uploads {

	/**
	 * Init.
	 */
	public static function init() {

		register_rest_route(
			EVENTKOI_API,
			'/upload_image',
			array(
				'methods'             => 'post',
				'callback'            => array( __CLASS__, 'upload_image' ),
				'permission_callback' => array( '\EventKoi\API\REST', 'private_api' ),
			)
		);
	}

	/**
	 * Update an image.
	 *
	 * @param object $request The request that is being passed to API.
	 */
	public static function upload_image( $request ) {

		$data = $request->get_file_params();

		return rest_ensure_response( array( 'data' => $data ) );
	}
}
