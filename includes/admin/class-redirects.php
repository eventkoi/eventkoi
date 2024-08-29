<?php
/**
 * Admin redirects.
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
 * Redirects.
 */
class Redirects {

	/**
	 * Init.
	 */
	public static function init() {

		add_action( 'admin_init', array( __CLASS__, 'admin_redirect' ) );
	}

	/**
	 * Admin redirects.
	 */
	public static function admin_redirect() {
		global $pagenow;

		// Redirect to add event page.
		if ( 'post-new.php' === $pagenow && ! empty( $_GET['post_type'] ) && 'event' === $_GET['post_type'] ) { // phpcs:ignore
			wp_safe_redirect( admin_url( 'admin.php?page=eventkoi#/events/add' ) );
			exit;
		}
	}
}
