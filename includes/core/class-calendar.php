<?php
/**
 * Calendar.
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
 * Calendar.
 */
class Calendar {

	/**
	 * Calendar object.
	 *
	 * @var $calendar.
	 */
	private static $calendar = null;

	/**
	 * Calendar ID.
	 *
	 * @var $calendar_id.
	 */
	private static $calendar_id = 0;

	/**
	 * Construct.
	 *
	 * @param {object, number} $calendar A calendar object or calendar ID.
	 */
	public function __construct( $calendar = null ) {

		if ( is_numeric( $calendar ) ) {
			$calendar = get_term_by( 'id', $calendar, 'event_cal' );
		}

		self::$calendar    = $calendar;
		self::$calendar_id = ! empty( $calendar->term_id ) ? $calendar->term_id : 0;
	}

	/**
	 * Get meta.
	 */
	public static function get_meta() {

		$meta = array(
			'id'   => self::get_id(),
			'name' => self::get_name(),
			'slug' => self::get_slug(),
			'url'  => self::get_url(),
		);

		return apply_filters( 'eventkoi_get_calendar_meta', $meta, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get calendar ID.
	 */
	public static function get_id() {
		$id = self::$calendar_id;

		return apply_filters( 'eventkoi_get_calendar_id', $id, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get name.
	 */
	public static function get_name() {
		$name = ! empty( self::$calendar->name ) ? self::$calendar->name : '';

		return apply_filters( 'eventkoi_get_calendar_name', $name, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get slug.
	 */
	public static function get_slug() {
		$slug = ! empty( self::$calendar->slug ) ? self::$calendar->slug : '';

		return apply_filters( 'eventkoi_get_calendar_slug', $slug, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get URL.
	 */
	public static function get_url() {
		$url = get_term_link( self::get_slug(), 'event_cal' );

		return apply_filters( 'eventkoi_get_calendar_url', $url, self::$calendar_id, self::$calendar );
	}

	/**
	 * Update calendar.
	 *
	 * @param array $meta An array with calendar meta.
	 */
	public static function update( $meta = array() ) {

		$meta = apply_filters( 'eventkoi_pre_update_calendar_meta', $meta, $meta['id'] );

		$id   = $meta['id'];
		$name = $meta['name'];

		$slug = ! empty( $meta['slug'] ) ? sanitize_text_field( $meta['slug'] ) : '';

		if ( 0 === $id ) {
			$args = array();

			$last_id           = wp_insert_term( $name, 'event_cal', $args );
			$calendar          = get_term_by( 'id', $last_id['term_id'], 'event_cal' );
			self::$calendar    = $calendar;
			self::$calendar_id = ! empty( $calendar->term_id ) ? $event->term_id : 0;

			self::update_meta( $meta );

			return array_merge(
				array(
					'update_endpoint' => true,
					'message'         => __( 'Calendar created.', 'eventkoi' ),
				),
				self::get_meta(),
			);
		}

		$calendar = get_term_by( 'id', $id, 'event_cal' );

		$args = array(
			'name' => $name,
			'slug' => $slug,
		);

		$last_id = wp_update_term( $id, 'event_cal', $args );

		if ( is_wp_error( $last_id ) ) {
			$result = array(
				'error' => html_entity_decode( $last_id->get_error_message() ),
			);
			return $result;
		}

		self::$calendar    = get_term_by( 'id', $last_id['term_id'], 'event_cal' );
		self::$calendar_id = ! empty( $calendar->term_id ) ? $calendar->term_id : 0;
		self::update_meta( $meta );

		return array_merge(
			array(
				'message' => __( 'Calendar updated.', 'eventkoi' ),
			),
			self::get_meta(),
		);
	}

	/**
	 * Update calendar meta.
	 *
	 * @param array $meta An array with calendar meta.
	 */
	public static function update_meta( $meta = array() ) {
		// Hook to allow chnages to calendar metadata.
		$meta = apply_filters( 'eventkoi_update_event_meta', $meta, self::$calendar_id, self::$calendar );

		do_action( 'eventkoi_before_update_calendar_meta', $meta, self::$calendar_id, self::$calendar );

		do_action( 'eventkoi_after_update_calendar_meta', $meta, self::$calendar_id, self::$calendar );
	}
}
