<?php
/**
 * Plugin Name:       EventKoi
 * Plugin URI:        https://eventkoi.com
 * Description:       Event management and scheduling for WordPress.
 * Version:           1.0.0
 * Author:            EventKoi
 * Author URI:        https://eventkoi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eventkoi
 * Domain Path:       /languages
 *
 * @package EventKoi
 */

namespace EventKoi;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'EVENTKOI_VERSION', '1.0.0' );
define( 'EVENTKOI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EVENTKOI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EVENTKOI_PLUGIN_FILE', __FILE__ );

// Load the autoloader.
require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

// Hooks.
register_activation_hook( __FILE__, array( __NAMESPACE__ . '\\Core\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\\Core\Deactivator', 'deactivate' ) );

/**
 * Initialize the plugin.
 */
function eventkoi() {
	$init = new \EventKoi\Init();
}

eventkoi();
