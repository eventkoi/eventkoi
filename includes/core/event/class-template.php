<?php
/**
 * Template.
 *
 * @package    EventKoi
 * @subpackage EventKoi\Core\Event
 */

namespace EventKoi\Core\Event;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template.
 */
class Template {

	/**
	 * Init.
	 */
	public function __construct() {

		add_action( 'single_template', array( __CLASS__, 'single_event' ) );
	}

	/**
	 * Register core post types.
	 *
	 * @param string $single A single template.
	 */
	public static function single_event( $single ) {
		global $post;

		if ( 'event' === $post->post_type && is_singular( 'event' ) ) {
			$default_file = EVENTKOI_PLUGIN_DIR . 'includes/core/event/views/single-event-page.php';
			if ( file_exists( $default_file ) ) {
				$single = $default_file;
			}
		}

		return $single;
	}
}
