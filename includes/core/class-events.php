<?php
/**
 * Events.
 *
 * @package    EventKoi
 * @subpackage EventKoi\Core
 */

namespace EventKoi\Core;

use EventKoi\Core\Event;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Events.
 */
class Events {

	/**
	 * Init.
	 *
	 * @param array $args Array of arguments to pass.
	 */
	public static function get_events( $args = array() ) {

		$query_args = array(
			'post_type'      => 'event',
			'orderby'        => 'modified',
			'order'          => 'DESC',
			'posts_per_page' => -1,
			'post_status'    => array( 'publish', 'draft' ),
		);

		// Get events based on status.
		if ( ! empty( $args['status'] ) ) {
			if ( in_array( $args['status'], array( 'draft', 'trash' ), true ) ) {
				$query_args['post_status'] = array( $args['status'] );
			}

			if ( 'completed' === $args['status'] ) {
				$query_args['meta_query'] = array( // phpcs:ignore
					array(
						'key'     => 'end_timestamp_gmt',
						'value'   => time(),
						'compare' => '>',
					),
				);
			} elseif ( 'live' === $args['status'] ) {
				$query_args['meta_query'] = array( // phpcs:ignore
					'relation' => 'AND',
					array(
						'key'     => 'start_timestamp_gmt',
						'value'   => time(),
						'compare' => '<',
					),
					array(
						'key'     => 'end_timestamp_gmt',
						'value'   => time(),
						'compare' => '<',
					),
				);
			} elseif ( 'upcoming' === $args['status'] ) {
				$query_args['meta_query'] = array( // phpcs:ignore
					array(
						'key'     => 'start_timestamp_gmt',
						'value'   => time(),
						'compare' => '>',
					),
				);
			}
		}

		$query = new \WP_Query( $query_args );

		// Return counts only.
		if ( ! empty( $args['counts_only'] ) ) {
			return $query->found_posts;
		}

		// Return all events including their meta.
		$results = array();

		foreach ( $query->posts as $post ) {
			$event     = new Event( $post );
			$results[] = $event::get_meta();
		}

		return $results;
	}

	/**
	 * Delete events.
	 *
	 * @param array $ids An array of events IDs to delete.
	 * @param bool  $force_delete If true, events will be permanently deleted.
	 */
	public static function delete_events( $ids = array(), $force_delete = false ) {

		foreach ( $ids as $id ) {
			if ( $force_delete ) {
				wp_delete_post( $id, $force_delete );
			} else {
				wp_trash_post( $id );
			}
		}

		$result = array(
			'ids'     => $ids,
			'success' => _n( 'Event moved to trash.', 'Events moved to trash.', count( $ids ), 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Get events counts.
	 */
	public static function get_counts() {

		$upcoming = self::get_events(
			array(
				'status'      => 'upcoming',
				'counts_only' => true,
			)
		);

		$live = self::get_events(
			array(
				'status'      => 'upcoming',
				'counts_only' => true,
			)
		);

		$completed = self::get_events(
			array(
				'status'      => 'upcoming',
				'counts_only' => true,
			)
		);

		$result = array(
			'upcoming'  => absint( $upcoming ),
			'live'      => absint( $live ),
			'completed' => absint( $completed ),
			'draft'     => wp_count_posts( 'event' )->draft,
			'trash'     => wp_count_posts( 'event' )->trash,
		);

		return $result;
	}
}
