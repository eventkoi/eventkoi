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
	public function __construct() {

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
		wp_enqueue_media();

		$events   = new \EventKoi\Core\Events();
		$event    = new \EventKoi\Core\Event( 0 );
		$calendar = new \EventKoi\Core\Calendar( 0 );
		$api      = new \EventKoi\API\REST();

		$default_cal_id = (int) get_option( 'default_event_cal', 0 );
		$default_cal    = get_term_by( 'id', $default_cal_id, 'event_cal' );
		$cal_url        = get_term_link( $default_cal, 'event_cal' );
		$cal_url        = str_replace( $default_cal->slug, '[calendar-name]', $cal_url );

		$eventkoi_params = array(
			'version'             => EVENTKOI_VERSION,
			'api'                 => EVENTKOI_API,
			'general_options_url' => admin_url( 'options-general.php' ),
			'api_key'             => $api::get_api_key(),
			'date_now'            => wp_date( 'j M Y', time() ),
			'date_24h'            => wp_date( 'j M Y', strtotime( '+1 day', time() ) ),
			'time_now'            => wp_date( 'g:i A', strtotime( '+1 hour', time() ) ),
			'new_event'           => $event::get_meta(),
			'new_calendar'        => $calendar::get_meta(),
			'default_cal'         => (int) get_option( 'default_event_cal', 0 ),
			'default_cal_url'     => untrailingslashit( $cal_url ),
			'counts'              => array(
				'events' => $events::get_counts(),
			),
		);

		wp_localize_script( 'eventkoi-admin', 'eventkoi_params', apply_filters( 'eventkoi_admin_params', $eventkoi_params ) );

		wp_register_style( 'eventkoi-admin-tw', $build_url . 'tailwind.css', null, $asset_file['version'] );
		wp_enqueue_style( 'eventkoi-admin-tw' );

		wp_register_style( 'eventkoi-admin', $build_url . 'index.css', array( 'wp-components' ), $asset_file['version'] );
		wp_enqueue_style( 'eventkoi-admin' );
	}
}
