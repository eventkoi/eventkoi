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
			'id'        => self::get_id(),
			'name'      => self::get_name(),
			'slug'      => self::get_slug(),
			'url'       => self::get_url(),
			'count'     => self::get_count(),
			'display'   => self::get_display(),
			'timeframe' => self::get_timeframe(),
			'startday'  => self::get_startday(),
			'shortcode' => self::get_shortcode(),
			'color'     => self::get_color(),
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

		if ( is_wp_error( $url ) ) {
			$url = '';
		}

		return apply_filters( 'eventkoi_get_calendar_url', $url, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get count.
	 */
	public static function get_count() {
		$count = isset( self::$calendar->count ) ? self::$calendar->count : 0;

		return apply_filters( 'eventkoi_get_calendar_count', $count, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get display type.
	 */
	public static function get_display() {
		$display = get_term_meta( self::$calendar_id, 'display', true );

		if ( empty( $display ) ) {
			$display = 'calendar';
		}

		return apply_filters( 'eventkoi_get_calendar_display', $display, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get timeframe display.
	 */
	public static function get_timeframe() {
		$timeframe = get_term_meta( self::$calendar_id, 'timeframe', true );

		if ( empty( $timeframe ) ) {
			$timeframe = 'month';
		}

		return apply_filters( 'eventkoi_get_calendar_timeframe', $timeframe, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get week start day.
	 */
	public static function get_startday() {
		$startday = get_term_meta( self::$calendar_id, 'startday', true );

		if ( empty( $startday ) ) {
			$startday = 'monday';
		}

		return apply_filters( 'eventkoi_get_calendar_startday', $startday, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get color.
	 */
	public static function get_color() {
		$color = get_term_meta( self::$calendar_id, 'color', true );

		if ( empty( $color ) ) {
			$color = 'accent';
		}

		return apply_filters( 'eventkoi_get_calendar_color', $color, self::$calendar_id, self::$calendar );
	}

	/**
	 * Get shortcode.
	 */
	public static function get_shortcode() {
		$shortcode = '[ek_calendar id=' . absint( self::get_id() ) . ']';

		return apply_filters( 'eventkoi_get_calendar_shortcode', $shortcode, self::$calendar_id, self::$calendar );
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
			$args = array(
				'slug' => ! empty( $slug ) ? $slug : '',
			);

			$last_id           = wp_insert_term( $name, 'event_cal', $args );
			$calendar          = get_term_by( 'id', $last_id['term_id'], 'event_cal' );
			self::$calendar    = $calendar;
			self::$calendar_id = ! empty( $calendar->term_id ) ? $calendar->term_id : 0;

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

		$display   = ! empty( $meta['display'] ) ? sanitize_text_field( $meta['display'] ) : 'calendar';
		$timeframe = ! empty( $meta['timeframe'] ) ? sanitize_text_field( $meta['timeframe'] ) : 'month';
		$startday  = ! empty( $meta['startday'] ) ? sanitize_text_field( $meta['startday'] ) : 'monday';
		$color     = ! empty( $meta['color'] ) ? sanitize_text_field( $meta['color'] ) : 'accent';

		update_term_meta( self::$calendar_id, 'display', (string) $display );
		update_term_meta( self::$calendar_id, 'timeframe', (string) $timeframe );
		update_term_meta( self::$calendar_id, 'startday', (string) $startday );
		update_term_meta( self::$calendar_id, 'color', (string) $color );

		do_action( 'eventkoi_after_update_calendar_meta', $meta, self::$calendar_id, self::$calendar );
	}

	/**
	 * Delete a single calendar.
	 *
	 * @param int $calendar_id ID of calendar.
	 */
	public static function delete_calendar( $calendar_id = 0 ) {

		if ( (int) get_option( 'default_event_cal', 0 ) === (int) $calendar_id ) {
			return;
		}

		wp_delete_term( $calendar_id, 'event_cal' );

		$result = array(
			'message' => __( 'Calendar deleted.', 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Duplicate a single calendar.
	 */
	public static function duplicate_calendar() {

		$meta = self::get_meta();

		$calendar = get_term_by( 'id', self::get_id(), 'event_cal' );

		/* translators: %s: calendar name */
		$name = sprintf( __( '[Duplicate] %s', 'eventkoi' ), $calendar->name );

		$args = array(
			'slug'        => wp_unique_term_slug( $calendar->name, $calendar ),
			'description' => $calendar->description,
		);

		$new_term = wp_insert_term( $name, 'event_cal', $args );
		$new_cal  = get_term_by( 'id', $new_term['term_id'], 'event_cal' );

		self::$calendar    = $new_cal;
		self::$calendar_id = ! empty( $new_cal->term_id ) ? $new_cal->term_id : 0;

		self::update_meta( $meta );

		$result = array_merge(
			array(
				'update_endpoint' => true,
				'message'         => __( 'Calendar duplicated.', 'eventkoi' ),
			),
			self::get_meta(),
		);

		return $result;
	}
}
