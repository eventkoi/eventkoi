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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load the autoloader.
require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

/**
 * Triggers activation action.
 */
function activate_eventkoi() {
	\EventKoi\Core\Activator::activate();
}

/**
 * Triggers deactivation action.
 */
function deactivate_eventkoi() {
	\EventKoi\Core\Deactivator::deactivate();
}

/**
 * Initialize the plugin.
 */
function eventkoi() {
	$plugin = new \EventKoi\Run();
}

// Hooks.
register_activation_hook( __FILE__, 'activate_eventkoi' );
register_deactivation_hook( __FILE__, 'deactivate_eventkoi' );

eventkoi();
