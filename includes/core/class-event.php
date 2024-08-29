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
	 * Event ID.
	 *
	 * @var $event_id.
	 */
	private static $event_id = 0;

	/**
	 * Construct.
	 *
	 * @param {object, number} $event An event object or event ID.
	 */
	public function __construct( $event = null ) {

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		self::$event    = $event;
		self::$event_id = ! empty( $event->ID ) ? $event->ID : 0;
	}

	/**
	 * Get meta.
	 */
	public static function get_meta() {

		$meta = array(
			'id'       => self::get_id(),
			'title'    => self::get_title(),
			'date'     => self::get_date_modified(),
			'date_gmt' => self::get_date_modified_gmt(),
			'status'   => self::get_status(),
			'url'      => self::get_url(),
			'timezone' => eventkoi_timezone(),
		);

		return apply_filters( 'eventkoi_get_event_meta', $meta, self::$event_id, self::$event );
	}

	/**
	 * Get event ID.
	 */
	public static function get_id() {
		$id = self::$event_id;

		return apply_filters( 'eventkoi_get_event_id', $id, self::$event_id, self::$event );
	}

	/**
	 * Get event title.
	 */
	public static function get_title() {
		$title = ! empty( self::$event->post_title ) ? self::$event->post_title : '';

		return apply_filters( 'eventkoi_get_event_title', $title, self::$event_id, self::$event );
	}

	/**
	 * Get event permalink or URL.
	 */
	public static function get_url() {
		$url = get_permalink( self::$event_id );

		if ( ! $url ) {
			$url = '';
		}

		return apply_filters( 'eventkoi_get_event_url', $url, self::$event_id, self::$event );
	}

	/**
	 * Get event status.
	 */
	public static function get_status() {
		$status = 'draft';

		return apply_filters( 'eventkoi_get_event_status', $status, self::$event_id, self::$event );
	}

	/**
	 * Get event modified date.
	 */
	public static function get_date_modified() {
		if ( ! empty( self::$event->post_modified_gmt ) && strtotime( self::$event->post_modified_gmt ) > 0 ) {
			$date = eventkoi_date_display( self::$event->post_modified_gmt );
		} else {
			$date = '';
		}

		return apply_filters( 'eventkoi_get_event_modified_date', $date, self::$event_id, self::$event );
	}

	/**
	 * Get event modified date (GMT).
	 */
	public static function get_date_modified_gmt() {
		if ( ! empty( self::$event->post_modified_gmt ) && strtotime( self::$event->post_modified_gmt ) > 0 ) {
			$date = date_i18n( eventkoi_get_default_date_format(), strtotime( self::$event->post_modified_gmt ) );
		} else {
			$date = '';
		}

		return apply_filters( 'eventkoi_get_event_modified_date_gmt', $date, self::$event_id, self::$event );
	}
}
