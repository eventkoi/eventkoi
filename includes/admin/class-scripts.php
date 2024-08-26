<?php
/**
 * Admin scripts.
 *
 * @package    EventKoi
 * @subpackage EventKoi\Admin
 */

namespace EventKoi\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Scripts.
 */
class Scripts {

	/**
	 * Init.
	 */
	public static function init() {

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ), 999 );
	}

	/**
	 * Load scripts.
	 */
	public static function admin_enqueue_scripts() {

		$screen = get_current_screen();

		if ( empty( $screen ) || 'toplevel_page_eventkoi' !== $screen->base ) {
			if ( empty( $screen->id ) || ! str_starts_with( $screen->id, 'eventkoi_' ) ) {
				return;
			}
		}

		$asset_file = include EVENTKOI_PLUGIN_DIR . 'scripts/backend/build/index.asset.php';
		$build_url  = EVENTKOI_PLUGIN_URL . 'scripts/backend/build/';

		wp_register_script( 'eventkoi-admin', $build_url . 'index.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_script( 'eventkoi-admin' );

		$eventkoi_params = array(
			'version' => EVENTKOI_VERSION,
			'api'     => EVENTKOI_API,
		);

		wp_localize_script( 'eventkoi-admin', 'eventkoi_params', apply_filters( 'eventkoi_admin_params', $eventkoi_params ) );

		wp_register_style( 'eventkoi-admin-tw', $build_url . 'tailwind.css', null, $asset_file['version'] );
		wp_enqueue_style( 'eventkoi-admin-tw' );

		wp_register_style( 'eventkoi-admin', $build_url . 'index.css', array( 'wp-components' ), $asset_file['version'] );
		wp_enqueue_style( 'eventkoi-admin' );
	}
}
