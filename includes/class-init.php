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
 * Init.
 */
class Init {

	/**
	 * Constructor.
	 */
	public function __construct() {

		include_once EVENTKOI_PLUGIN_DIR . 'includes/core/core-functions.php';

		// Loaded in both frontend and backend.
		$install    = new \EventKoi\Core\Install();
		$post_types = new \EventKoi\Core\Post_Types();
		$template   = new \EventKoi\Core\Event\Template();
		$blocks     = new \EventKoi\Core\Blocks();
		$api        = new \EventKoi\API\REST();
		$scripts    = new \EventKoi\Core\Scripts();

		// Load inside admin only.
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$admin_menus     = new \EventKoi\Admin\Menus();
			$admin_redirects = new \EventKoi\Admin\Redirects();
			$admin_scripts   = new \EventKoi\Admin\Scripts();
		}
	}
}
