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
		global $wp_filesystem;

		require_once ABSPATH . 'wp-admin/includes/image.php';

		if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
			$creds = request_filesystem_credentials( site_url() );
			wp_filesystem( $creds );
		}

		$data = $request->get_file_params();

		$file_name = $data['uploadedfile']['name'];
		$file_temp = $data['uploadedfile']['tmp_name'];

		$upload_dir = wp_upload_dir();
		$image_data = $wp_filesystem->get_contents( $file_temp );
		$filename   = basename( $file_name );
		$filetype   = wp_check_filetype( $file_name );
		$filename   = time() . '.' . $filetype['ext'];

		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		$wp_filesystem->put_contents( $file, $image_data );
		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id   = wp_insert_attachment( $attachment, $file );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return rest_ensure_response(
			array(
				'id'  => $attach_id,
				'url' => wp_get_attachment_url( $attach_id ),
			)
		);
	}
}
