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
			if ( ! empty( $event->post_type ) && 'event' !== $event->post_type ) {
				$event = array();
			}
		}

		self::$event    = $event;
		self::$event_id = ! empty( $event->ID ) ? $event->ID : 0;
	}

	/**
	 * Get event.
	 *
	 * @param int $event_id ID for an event.
	 */
	public static function get_event( $event_id ) {
		$event          = get_post( $event_id );
		self::$event    = $event;
		self::$event_id = ! empty( $event->ID ) ? $event->ID : 0;

		return self::get_meta();
	}

	/**
	 * Get meta.
	 */
	public static function get_meta() {

		$meta = array(
			'id'       => self::get_id(),
			'title'    => self::get_title(),
			'date'     => array(
				'start'        => self::get_start_date(),
				'start_gmt'    => self::get_start_date( true ),
				'end'          => self::get_end_date(),
				'end_gmt'      => self::get_end_date( true ),
				'modified'     => self::get_modified_date(),
				'modified_gmt' => self::get_modified_date( true ),
			),
			'status'   => self::get_status(),
			'url'      => self::get_url(),
			'tbc'      => self::get_tbc(),
			'timezone' => eventkoi_timezone(),
		);

		return apply_filters( 'eventkoi_get_event_meta', $meta, self::$event_id, self::$event );
	}

	/**
	 * Update event.
	 *
	 * @param array  $meta An array with event meta.
	 * @param string $status A pre-defeind event status.
	 */
	public static function update( $meta = array(), $status = 'draft' ) {

		$meta = apply_filters( 'eventkoi_pre_update_event_meta', $meta, $meta['id'] );

		$id    = $meta['id'];
		$title = $meta['title'];

		if ( 0 === $id ) {
			$args = array(
				'post_type'   => 'event',
				'post_status' => $status,
				'post_title'  => $title,
				'post_name'   => sanitize_title_with_dashes( $title, '', 'save' ),
				'post_author' => get_current_user_id(),
			);

			$last_id        = wp_insert_post( $args );
			$event          = get_post( $last_id );
			self::$event    = $event;
			self::$event_id = ! empty( $event->ID ) ? $event->ID : 0;

			self::update_meta( $meta );

			return array_merge(
				array(
					'update_endpoint' => true,
					'message'         => __( 'Event created.', 'eventkoi' ),
				),
				self::get_meta(),
			);
		}

		$args = array(
			'ID'          => $id,
			'post_title'  => $title,
			'post_name'   => sanitize_title_with_dashes( $title, '', 'save' ),
			'post_status' => $status,
		);

		$last_id        = wp_update_post( $args );
		$event          = get_post( $last_id );
		self::$event    = $event;
		self::$event_id = ! empty( $event->ID ) ? $event->ID : 0;

		self::update_meta( $meta );

		return array_merge(
			array(
				'message' => __( 'Event updated.', 'eventkoi' ),
			),
			self::get_meta(),
		);
	}

	/**
	 * Update event meta.
	 *
	 * @param array $meta An array with event meta.
	 */
	public static function update_meta( $meta = array(), ) {
		// Hook to allow chnages to event metadata.
		$meta = apply_filters( 'eventkoi_update_event_meta', $meta, self::$event_id, self::$event );

		do_action( 'eventkoi_before_update_event_meta', $meta, self::$event_id, self::$event );

		$tbc        = ! empty( $meta['tbc'] );
		$start_date = ! empty( $meta['date']['start'] ) ? esc_attr( $meta['date']['start'] ) : '';
		$end_date   = ! empty( $meta['date']['end'] ) ? esc_attr( $meta['date']['end'] ) : '';

		update_post_meta( self::$event_id, 'tbc', (bool) $tbc );
		update_post_meta( self::$event_id, 'start_date', get_gmt_from_date( $start_date, eventkoi_get_default_date_format() ) );
		update_post_meta( self::$event_id, 'end_date', get_gmt_from_date( $end_date, eventkoi_get_default_date_format() ) );

		do_action( 'eventkoi_after_update_event_meta', $meta, self::$event_id, self::$event );
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
		$status = ! empty( self::$event->post_status ) ? self::$event->post_status : 'draft';

		return apply_filters( 'eventkoi_get_event_status', $status, self::$event_id, self::$event );
	}

	/**
	 * Get event start date.
	 *
	 *  @param bool $gmt If true, GMT time will be returned.
	 */
	public static function get_start_date( $gmt = false ) {
		$date = get_post_meta( self::$event_id, 'start_date', true );
		$date = eventkoi_date_i18n( $date, $gmt );

		$hook = $gmt ? 'eventkoi_get_event_start_date_gmt' : 'eventkoi_get_event_start_date';

		return apply_filters( $hook, (string) $date, self::$event_id, self::$event );
	}

	/**
	 * Get event end date.
	 *
	 *  @param bool $gmt If true, GMT time will be returned.
	 */
	public static function get_end_date( $gmt = false ) {
		$date = get_post_meta( self::$event_id, 'end_date', true );
		$date = eventkoi_date_i18n( $date, $gmt );

		$hook = $gmt ? 'eventkoi_get_event_end_date_gmt' : 'eventkoi_get_event_end_date';

		return apply_filters( $hook, (string) $date, self::$event_id, self::$event );
	}

	/**
	 * Get event modified date.
	 *
	 * @param bool $gmt If true, GMT time will be returned.
	 */
	public static function get_modified_date( $gmt = false ) {
		if ( ! empty( self::$event->post_modified_gmt ) && strtotime( self::$event->post_modified_gmt ) > 0 ) {
			$date = eventkoi_date_i18n( self::$event->post_modified_gmt, $gmt );
		} else {
			$date = '';
		}

		$hook = $gmt ? 'eventkoi_get_event_modified_date_gmt' : 'eventkoi_get_event_modified_date';

		return apply_filters( $hook, (string) $date, self::$event_id, self::$event );
	}

	/**
	 * Get event to be confirmed status.
	 */
	public static function get_tbc() {
		$tbc = get_post_meta( self::$event_id, 'tbc', true );

		return apply_filters( 'eventkoi_get_event_tbc', (bool) $tbc, self::$event_id, self::$event );
	}

	/**
	 * Restore a single event.
	 *
	 * @param int $event_id ID of an event.
	 */
	public static function restore_event( $event_id = 0 ) {

		wp_untrash_post( $event_id );

		$result = array(
			'event'   => self::get_event( $event_id ),
			'success' => __( 'Event restored successfully.', 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Delete a single event.
	 *
	 * @param int $event_id ID of an event.
	 */
	public static function delete_event( $event_id = 0 ) {

		wp_trash_post( $event_id );

		$result = array(
			'success' => __( 'Event moved to Trash.', 'eventkoi' ),
		);

		return $result;
	}
}
