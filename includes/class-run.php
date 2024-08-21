<?php
/**
 * The main class used to run the plugin.
 *
 * @package EventKoi
 */

namespace EventKoi;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run.
 */
class Run {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Load inside admin only.
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$admin_menus   = new \EventKoi\Admin\Menus();
			$admin_scripts = new \EventKoi\Admin\Scripts();
		}
	}
}
