<?php
/**
 * Single event page.
 *
 * @package EventKoi
 */

if ( file_exists( get_stylesheet_directory() . '/eventkoi/templates/single-event.php' ) ) {
	require_once get_stylesheet_directory() . '/eventkoi/templates/single-event.php';
} elseif ( file_exists( get_template_directory() . '/eventkoi/templates/single-event.php' ) ) {
	require_once get_template_directory() . '/eventkoi/templates/single-event.php';
} elseif ( file_exists( EVENTKOI_PLUGIN_DIR . 'templates/single-event.php' ) ) {
	include_once EVENTKOI_PLUGIN_DIR . 'templates/single-event.php';
}
