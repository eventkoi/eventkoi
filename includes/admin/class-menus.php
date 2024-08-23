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

		add_action( 'admin_head', array( $this, 'add_menu_css' ) );
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

		$encode = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNi45MTgiIGhlaWdodD0iMjEuODkiIHZpZXdCb3g9IjAgMCAxNi45MTggMjEuODkiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTEzLjY5NikiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMTMuNjk2KSI+PHBhdGggZD0iTTYuMTktNzIuNzI1QTMuODE4LDMuODE4LDAsMCwwLDQuODM3LTc1LjM4YTMuMzU5LDMuMzU5LDAsMCwwLTIuNDg0LS44NzZBMS44ODUsMS44ODUsMCwwLDAsLjYxMS03NC45YTEuNjEyLDEuNjEyLDAsMCwwLS4wOTIsMS4wNiw2LjIzLDYuMjMsMCwwLDAsLjUxOCwxLjE3OCw4LjU1LDguNTUsMCwwLDEsLjQuODMyLDQuMzg3LDQuMzg3LDAsMCwxLC4yMjQuNzc1LDcuNTgyLDcuNTgyLDAsMCwwLC45ODQsMi41LDQuNTQyLDQuNTQyLDAsMCwwLDEuNTIsMS42MTMsMS4wMjIsMS4wMjIsMCwwLDAsMS4zNjMtLjEwN3EuNTYzLS4zOTEuNjkzLTIuMzMyQTE4LjMzLDE4LjMzLDAsMCwwLDYuMTktNzIuNzI1Wm0tLjA5LDcuMnEtMS4wMzQsMC0xLjI0MiwyLjdhMTguMTYyLDE4LjE2MiwwLDAsMCwuNDEzLDUuNDQycS42MjIsMi43MzksMS42NzcsMi44NzgsMi4zLjMxMywyLjcyOS0yLjQyMmExMC42MzYsMTAuNjM2LDAsMCwwLS43MzYtNS42NjhRNy43NzMtNjUuNTIxLDYuMS02NS41MjFabTIuMDUtMS41MmEuODguODgsMCwwLDAsLjk0MS40ODcsMi40OSwyLjQ5LDAsMCwwLDEuMTItLjY1N3EuNTgzLS41MzksMS4zODktMS40LjQ4MS0uNjI3LjktMS4wOTFhNS40NjIsNS40NjIsMCwwLDEsLjgwNS0uNzU0LDQuMzc4LDQuMzc4LDAsMCwwLDIuMDUxLTIuOTMxLDIuNDgzLDIuNDgzLDAsMCwwLS45MTctMi41MzMsMi42NzQsMi42NzQsMCwwLDAtMi45MTQtLjAyOEE1LjcxNSw1LjcxNSwwLDAsMCw5LjM0My03My41NSwxMi41MDksMTIuNTA5LDAsMCwwLDcuOS02OS43OCwzLjQyMiwzLjQyMiwwLDAsMCw4LjE0OS02Ny4wNDFaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMC40NjcgNzYuMzU2KSIgZmlsbD0iI2ZmZmZmZiIgLz48L2c+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNy4zMDEgMjAuMDYxKSI+PHBhdGggZD0iTTM0LjU2NC0zMi41MTFhMi44MTYsMi44MTYsMCwwLDAtLjI2OSwxLjI0LDEuNDYxLDEuNDYxLDAsMCwwLC4yNjkuOTEzYy41MzUuODUyLjgxOCwxLjEzOSwxLjEsMS41OWExNS4wMDYsMTUuMDA2LDAsMCwwLDMuOCw0LjEyNXEyLjIyMywxLjYzNSwzLjU4Ljc3NGExLjU1NSwxLjU1NSwwLDAsMCwuODY1LTEuNDQ4LDMuMjM1LDMuMjM1LDAsMCwwLS42MTktMS42MjJBMTcuMTMxLDE3LjEzMSwwLDAsMCw0MS44NS0yOC42N2wtLjMzMi0uMzg2YTIwLjgwNSwyMC44MDUsMCwwLDAtMi41LTIuMjY1LDEwLjYsMTAuNiwwLDAsMC0yLjgtMS42NTZRMzQuOTM2LTMzLjQ0NywzNC41NjQtMzIuNTExWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTM0LjI5NSAzMy4xMzQpIiBmaWxsPSIjZmZmZmZmIiAvPjwvZz48L2c+PC9zdmc+';
		$icon   = 'data:image/svg+xml;base64,' . $encode;

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

	/**
	 * Add css for menu logo.
	 */
	public function add_menu_css() {
		?>
		<style type="text/css">
			#adminmenu li.toplevel_page_eventkoi div.wp-menu-image.svg { background-size: 12px auto !important; }
		</style>
		<?php
	}
}
