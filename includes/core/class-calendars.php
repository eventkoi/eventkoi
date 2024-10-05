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
	 * Init.
	 */
	public static function get_calendars() {

		$terms = get_terms(
			array(
				'taxonomy'   => 'event_cal',
				'hide_empty' => false,
			)
		);

		// Return all events including their meta.
		$results = array();

		foreach ( $terms as $term ) {
			$results[] = array(
				'id'    => $term->term_id,
				'slug'  => $term->slug,
				'name'  => $term->name,
				'count' => $term->count,
			);
		}

		return $results;
	}
}
