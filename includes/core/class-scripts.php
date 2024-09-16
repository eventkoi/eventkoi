<?php
/**
 * Frontend scripts.
 *
 * @package    EventKoi
 * @subpackage EventKoi\Core
 */

namespace EventKoi\Core;

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
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wp_enqueue_scripts' ), 999 );
	}

	/**
	 * Load scripts.
	 */
	public static function wp_enqueue_scripts() {

		if ( ! is_singular( array( 'event' ) ) ) {
			return;
		}

		$asset_file = include EVENTKOI_PLUGIN_DIR . 'scripts/frontend/build/index.asset.php';
		$build_url  = EVENTKOI_PLUGIN_URL . 'scripts/frontend/build/';

		wp_register_script( 'eventkoi-frontend', $build_url . 'index.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_script( 'eventkoi-frontend' );

		$eventkoi_params = array();

		wp_localize_script( 'eventkoi-frontend', 'eventkoi_params', apply_filters( 'eventkoi_frontend_params', $eventkoi_params ) );

		wp_register_style( 'eventkoi-frontend-tw', $build_url . 'tailwind.css', null, $asset_file['version'] );
		wp_enqueue_style( 'eventkoi-frontend-tw' );

		wp_register_style( 'eventkoi-frontend', $build_url . 'index.css', array(), $asset_file['version'] );
		wp_enqueue_style( 'eventkoi-frontend' );
	}
}
