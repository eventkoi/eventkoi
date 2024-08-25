<?php
/**
 * Event.
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
 * Event.
 */
class Event {

	/**
	 * Event object.
	 *
	 * @var $event.
	 */
	private static $event = null;

	/**
	 * Construct.
	 *
	 * @param {object, number} $event An event object or event ID.
	 */
	public function __construct( $event = null ) {

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		self::$event = $event;
	}

	/**
	 * Get meta.
	 */
	public static function get_meta() {

		$event = self::$event;

		if ( empty( $event->ID ) ) {
			return array();
		}

		$title = $event->post_title ? $event->post_title : __( 'Untitled event', 'eventkoi' );

		if ( strtotime( $event->post_modified_gmt ) > 0 ) {
			$date_modified = eventkoi_date_display( $event->post_modified_gmt );
		} else {
			$date_modified = '';
		}

		$meta = array(
			'id'            => $event->ID,
			'title'         => $title,
			'event'         => $event,
			'date_modified' => $date_modified,
			'status'        => 'draft',
			'date'          => $date_modified,
		);

		return apply_filters( 'eventkoi_get_event_meta', $meta, $event->ID );
	}
}
