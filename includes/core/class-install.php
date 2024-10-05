<?php
/**
 * Install.
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
 * Install.
 */
class Install {

	/**
	 * Init.
	 */
	public function __construct() {

		add_action( 'init', array( __CLASS__, 'check_version' ), 6 );
	}

	/**
	 * Check version and run the updater is required.
	 */
	public static function check_version() {
		$ek_version      = get_option( 'eventkoi_version' );
		$ek_code_version = EVENTKOI_VERSION;
		$requires_update = version_compare( $ek_version, $ek_code_version, '<' );
		if ( ! defined( 'IFRAME_REQUEST' ) && $requires_update ) {
			self::install();
			do_action( 'eventkoi_updated' );
		}
	}

	/**
	 * Install plugin.
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( self::is_installing() ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'ek_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		eventkoi_maybe_define_constant( 'EK_INSTALLING', true );

		self::install_core();

		// Force a flush of rewrite rules even if the corresponding hook isn't initialized yet.
		if ( ! has_action( 'eventkoi_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
		}

		/**
		 * Flush the rewrite rules after install or update.
		 */
		do_action( 'eventkoi_flush_rewrite_rules' );

		/**
		 * Run after WooCommerce has been installed or updated.
		 */
		do_action( 'eventkoi_installed' );

		/**
		 * Run after WooCommerce Admin has been installed or updated.
		 */
		do_action( 'eventkoi_admin_installed' );
	}

	/**
	 * Returns true if we're installing.
	 */
	private static function is_installing() {
		return 'yes' === get_transient( 'ek_installing' );
	}

	/**
	 * Core function that performs the install.
	 */
	private static function install_core() {
		self::create_terms();
		self::update_version();

		delete_transient( 'ek_installing' );
	}

	/**
	 * Add the default terms for taxonomies.
	 */
	public static function create_terms() {
		$eventkoi_default_calendar = (int) get_option( 'default_event_cal', 0 );

		if ( ! $eventkoi_default_calendar || ! term_exists( $eventkoi_default_calendar, 'event_cal' ) ) {
			$default_event_cal_id   = 0;
			$default_event_cal_slug = sanitize_title( _x( 'Default calendar', 'Default category slug', 'eventkoi' ) );
			$default_event_cal      = get_term_by( 'slug', $default_event_cal_slug, 'event_cal' ); // @codingStandardsIgnoreLine.

			if ( $default_event_cal ) {
				$default_event_cal_id = absint( $default_event_cal->term_taxonomy_id );
			} else {
				$result = wp_insert_term( _x( 'Default calendar', 'Default category slug', 'eventkoi' ), 'event_cal', array( 'slug' => $default_event_cal_slug ) );

				if ( ! is_wp_error( $result ) && ! empty( $result['term_taxonomy_id'] ) ) {
					$default_event_cal_id = absint( $result['term_taxonomy_id'] );
				}
			}

			if ( $default_event_cal_id ) {
				update_option( 'default_event_cal', $default_event_cal_id );
			}
		}
	}

	/**
	 * Update version to current.
	 */
	private static function update_version() {
		update_option( 'eventkoi_version', EVENTKOI_VERSION );
	}
}
