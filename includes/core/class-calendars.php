<?php
/**
 * Calendars.
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
 * Calendars.
 */
class Calendars {

	/**
	 * Get calendars.
	 */
	public static function get_calendars() {

		$terms = get_terms(
			array(
				'taxonomy'   => 'event_cal',
				'hide_empty' => false,
			)
		);

		// Get array of calendars as terms.
		$results = array();

		foreach ( $terms as $term ) {
			$results[] = array(
				'id'    => $term->term_id,
				'slug'  => $term->slug,
				'name'  => $term->name,
				'count' => $term->count,
				'url'   => get_term_link( $term->slug, 'event_cal' ),
			);
		}

		return $results;
	}

	/**
	 * Remove calendars permanently.
	 *
	 * @param array $ids An array of events IDs to delete.
	 */
	public static function delete_calendars( $ids = array() ) {

		$eventkoi_default_calendar = (int) get_option( 'default_event_cal', 0 );

		foreach ( $ids as $id ) {
			// Do not delete default calendar.
			if ( $eventkoi_default_calendar === $id ) {
				continue;
			}

			wp_delete_term( $id, 'event_cal' );
		}

		$result = array(
			'ids'     => $ids,
			'success' => _n( 'Calendar removed permanently.', 'Calendars removed permanently.', count( $ids ), 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Duplicate calendars.
	 *
	 * @param array $ids An array of events IDs to duplicate.
	 */
	public static function duplicate_calendars( $ids = array() ) {

		foreach ( $ids as $id ) {
			$term = get_term_by( 'id', $id, 'event_cal' );

			/* translators: %s: term name */
			$name = sprintf( __( '[Duplicate] %s', 'eventkoi' ), $term->name );

			$args = array(
				'slug'        => wp_unique_term_slug( $term->name, $term ),
				'description' => $term->description,
			);

			$new_term = wp_insert_term( $name, 'event_cal', $args );
		}

		$result = array(
			'ids'     => $ids,
			'success' => _n( 'Calendar duplicated permanently.', 'Calendars duplicated permanently.', count( $ids ), 'eventkoi' ),
		);

		return $result;
	}
}
