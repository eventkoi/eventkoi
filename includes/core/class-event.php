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
			'id'                => self::get_id(),
			'title'             => self::get_title(),
			'description'       => self::get_description(),
			'image'             => self::get_image(),
			'image_id'          => self::get_image_id(),
			'start_date'        => self::get_start_date(),
			'start_date_gmt'    => self::get_start_date( true ),
			'end_date'          => self::get_end_date(),
			'end_date_gmt'      => self::get_end_date( true ),
			'modified_date'     => self::get_modified_date(),
			'modified_date_gmt' => self::get_modified_date( true ),
			'status'            => self::get_status(),
			'wp_status'         => self::get_wp_status(),
			'url'               => self::get_url(),
			'tbc'               => self::get_tbc(),
			'tbc_note'          => self::get_tbc_note(),
			'type'              => self::get_type(),
			'address1'          => self::get_address1(),
			'address2'          => self::get_address2(),
			'address3'          => self::get_address3(),
			'virtual_url'       => self::get_virtual_url(),
			'template'          => self::get_template(),
			'timezone'          => eventkoi_timezone(),
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
	public static function update_meta( $meta = array() ) {
		// Hook to allow chnages to event metadata.
		$meta = apply_filters( 'eventkoi_update_event_meta', $meta, self::$event_id, self::$event );

		do_action( 'eventkoi_before_update_event_meta', $meta, self::$event_id, self::$event );

		$tbc         = ! empty( $meta['tbc'] );
		$tbc_note    = ! empty( $meta['tbc_note'] ) ? esc_attr( $meta['tbc_note'] ) : '';
		$start_date  = ! empty( $meta['start_date'] ) ? esc_attr( $meta['start_date'] ) : '';
		$end_date    = ! empty( $meta['end_date'] ) ? esc_attr( $meta['end_date'] ) : '';
		$type        = ! empty( $meta['type'] ) ? esc_attr( $meta['type'] ) : 'inperson';
		$address1    = ! empty( $meta['address1'] ) ? esc_attr( $meta['address1'] ) : '';
		$address2    = ! empty( $meta['address2'] ) ? esc_attr( $meta['address2'] ) : '';
		$address3    = ! empty( $meta['address3'] ) ? esc_attr( $meta['address3'] ) : '';
		$virtual_url = ! empty( $meta['virtual_url'] ) ? esc_attr( $meta['virtual_url'] ) : '';
		$description = ! empty( $meta['description'] ) ? sanitize_text_field( htmlentities( $meta['description'] ) ) : '';
		$image       = ! empty( $meta['image'] ) ? sanitize_url( $meta['image'] ) : '';
		$image_id    = ! empty( $meta['image_id'] ) ? absint( $meta['image_id'] ) : 0;

		update_post_meta( self::$event_id, 'tbc', (bool) $tbc );
		update_post_meta( self::$event_id, 'tbc_note', (string) $tbc_note );
		update_post_meta( self::$event_id, 'type', (string) $type );
		update_post_meta( self::$event_id, 'address1', (string) $address1 );
		update_post_meta( self::$event_id, 'address2', (string) $address2 );
		update_post_meta( self::$event_id, 'address3', (string) $address3 );
		update_post_meta( self::$event_id, 'virtual_url', (string) $virtual_url );
		update_post_meta( self::$event_id, 'description', normalize_whitespace( $description ) );
		update_post_meta( self::$event_id, 'image', (string) $image );
		update_post_meta( self::$event_id, 'image_id', $image_id );

		if ( $start_date ) {
			update_post_meta( self::$event_id, 'start_date', eventkoi_get_gmt_from_date( $start_date ) );
		} else {
			delete_post_meta( self::$event_id, 'start_date' );
		}

		if ( $end_date ) {
			update_post_meta( self::$event_id, 'end_date', eventkoi_get_gmt_from_date( $end_date ) );
		} else {
			delete_post_meta( self::$event_id, 'end_date' );
		}

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
	 * Get event description.
	 */
	public static function get_description() {
		$description = get_post_meta( self::$event_id, 'description', true );

		return apply_filters( 'eventkoi_get_event_description', html_entity_decode( normalize_whitespace( $description ) ), self::$event_id, self::$event );
	}

	/**
	 * Get event image.
	 */
	public static function get_image() {
		$image = get_post_meta( self::$event_id, 'image', true );

		return apply_filters( 'eventkoi_get_event_image', esc_url( $image ), self::$event_id, self::$event );
	}

	/**
	 * Get event image ID.
	 */
	public static function get_image_id() {
		$image_id = get_post_meta( self::$event_id, 'image_id', true );

		return apply_filters( 'eventkoi_get_event_image_id', absint( $image_id ), self::$event_id, self::$event );
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

		$starts = self::get_start_date( true );
		$ends   = self::get_end_date( true );

		if ( $starts && $ends && ! in_array( $status, array( 'trash' ), true ) ) {
			if ( $ends < eventkoi_gmt_date() ) {
				$status = 'completed';
			} else {
				$status = 'upcoming';
				if ( self::get_tbc() ) {
					$status = 'tbc';
				}
			}
		}

		if ( $starts && $ends ) {
			if ( $starts < eventkoi_gmt_date() && $ends >= eventkoi_gmt_date() ) {
				$status = 'live';
			}
		}

		if ( self::get_tbc() ) {
			$status = 'tbc';
		}

		return apply_filters( 'eventkoi_get_event_status', $status, self::$event_id, self::$event );
	}

	/**
	 * Get event status from WordPress.
	 */
	public static function get_wp_status() {
		$status = ! empty( self::$event->post_status ) ? self::$event->post_status : 'draft';

		return apply_filters( 'eventkoi_get_event_core_status', $status, self::$event_id, self::$event );
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
	 * Get to be confirmed notification.
	 */
	public static function get_tbc_note() {
		$tbc_note = get_post_meta( self::$event_id, 'tbc_note', true );

		return apply_filters( 'eventkoi_get_event_tbc_note', (string) $tbc_note, self::$event_id, self::$event );
	}

	/**
	 * Get event type.
	 */
	public static function get_type() {
		$type = get_post_meta( self::$event_id, 'type', true );

		if ( empty( $type ) ) {
			$type = 'inperson';
		}

		return apply_filters( 'eventkoi_get_event_type', (string) $type, self::$event_id, self::$event );
	}

	/**
	 * Get event address 1.
	 */
	public static function get_address1() {
		$address1 = get_post_meta( self::$event_id, 'address1', true );

		return apply_filters( 'eventkoi_get_event_address1', (string) $address1, self::$event_id, self::$event );
	}

	/**
	 * Get event address 2.
	 */
	public static function get_address2() {
		$address2 = get_post_meta( self::$event_id, 'address2', true );

		return apply_filters( 'eventkoi_get_event_address2', (string) $address2, self::$event_id, self::$event );
	}

	/**
	 * Get event address 3.
	 */
	public static function get_address3() {
		$address3 = get_post_meta( self::$event_id, 'address3', true );

		return apply_filters( 'eventkoi_get_event_address3', (string) $address3, self::$event_id, self::$event );
	}

	/**
	 * Get event virtual URL.
	 */
	public static function get_virtual_url() {
		$virtual_url = get_post_meta( self::$event_id, 'virtual_url', true );

		return apply_filters( 'eventkoi_get_event_virtual_url', (string) $virtual_url, self::$event_id, self::$event );
	}

	/**
	 * Get event template.
	 */
	public static function get_template() {
		$template = get_post_meta( self::$event_id, 'template', true );

		if ( empty( $template ) ) {
			$template = 'default';
		}

		return apply_filters( 'eventkoi_get_event_template', (string) $template, self::$event_id, self::$event );
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
			'message' => __( 'Event restored successfully.', 'eventkoi' ),
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
			'message' => __( 'Event moved to Trash.', 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Duplicate a single event.
	 */
	public static function duplicate_event() {

		$meta = self::get_meta();

		/* translators: %s event title */
		$title = sprintf( __( '[Duplicate]: %s', 'eventkoi' ), $meta['title'] );

		$args = array(
			'post_type'   => 'event',
			'post_status' => 'draft',
			'post_title'  => $title,
			'post_name'   => sanitize_title_with_dashes( $title, '', 'save' ),
			'post_author' => get_current_user_id(),
		);

		$last_id        = wp_insert_post( $args );
		$event          = get_post( $last_id );
		self::$event    = $event;
		self::$event_id = ! empty( $event->ID ) ? $event->ID : 0;

		wp_update_post( array( 'ID' => $last_id ) );

		self::update_meta( $meta );

		$result = array_merge(
			array(
				'update_endpoint' => true,
				'message'         => __( 'Event duplicated.', 'eventkoi' ),
			),
			self::get_meta(),
		);

		return $result;
	}
}
