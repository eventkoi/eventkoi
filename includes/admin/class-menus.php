<?php
/**
 * Admin menus.
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
 * Menus.
 */
class Menus {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'in_admin_header', array( $this, 'remove_admin_notices' ), 99 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 99 );

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 );
		add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );

		add_action( 'admin_menu', array( $this, 'menu_order_fix' ), 999 );
		add_action( 'admin_menu_editor-menu_replaced', array( $this, 'menu_order_fix' ), 999 );
	}

	/**
	 * Remove all admin notices.
	 */
	public function remove_admin_notices() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : '';

		if ( ! empty( $screen ) && 'toplevel_page_eventkoi' === $screen->base ) {
			if ( apply_filters( 'eventkoi_remove_all_admin_notices', true ) ) {
				remove_all_actions( 'admin_notices' );
			}
		}
	}

	/**
	 * Admin body class.
	 *
	 * @param string $classes A space separated string for classes.
	 */
	public function admin_body_class( $classes ) {

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : '';

		if ( ! empty( $screen ) && 'toplevel_page_eventkoi' === $screen->base ) {
			$classes .= ' wp-eventkoi';
		} elseif ( ! empty( $screen->id ) && strstr( $screen->id, 'eventkoi_' ) ) {
			$classes .= ' wp-eventkoi';
		}

		return $classes;
	}

	/**
	 * Setup admin menu.
	 */
	public function admin_menu() {

		if ( isset( $_GET['page'] ) && 'eventkoi' === $_GET['page'] ) { // phpcs:ignore
			add_filter( 'admin_footer_text', '__return_empty_string', 11 );
			remove_filter( 'update_footer', 'core_update_footer' );
		}

		$admin_page = add_menu_page(
			__( 'EventKoi', 'eventkoi' ),
			__( 'EventKoi', 'eventkoi' ),
			'manage_options',
			'eventkoi',
			array( $this, 'load_admin' ),
			$this->get_admin_icon(),
			'25.33'
		);

		$items = array(
			'dashboard' => __( 'Dashboard', 'eventkoi' ),
		);

		foreach ( $items as $slug => $name ) {
			add_submenu_page( 'eventkoi', $name, $name, 'manage_options', 'admin.php?page=eventkoi#/' . $slug, null );
		}
	}

	/**
	 * Get admin icon.
	 */
	public function get_admin_icon() {

		$svg = '';

		$icon = 'data:image/svg+xml;base64,' . base64_encode( $svg ); // phpcs:ignore

		return $icon;
	}

	/**
	 * Admin wrapper.
	 */
	public function load_admin() {
		echo '<div id="eventkoi-admin" class="eventkoi-admin"></div>';
	}

	/**
	 * Enables custom menu order.
	 *
	 * @param bool $enabled A true/false value that allows custom menu order.
	 */
	public function custom_menu_order( $enabled ) {
		return $enabled || current_user_can( 'manage_options' );
	}

	/**
	 * Removes the parent menu item.
	 */
	public function menu_order_fix() {
		global $submenu;

		if ( isset( $submenu ) && is_array( $submenu ) ) {
			foreach ( $submenu as $key => $array ) {
				if ( 'eventkoi' === $key ) {
					foreach ( $array as $index => $value ) {
						if ( isset( $value[2] ) && 'eventkoi' === $value[2] ) {
							unset( $submenu['eventkoi'][ $index ] );
						}
					}
				}
			}
		}
	}
}
