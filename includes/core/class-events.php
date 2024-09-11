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
						'key'     => 'end_date',
						'value'   => eventkoi_gmt_date(),
						'compare' => '<',
					),
				);
			} elseif ( 'live' === $args['status'] ) {
				$query_args['meta_query'] = array( // phpcs:ignore
					'relation' => 'AND',
					array(
						'key'     => 'start_date',
						'value'   => eventkoi_gmt_date(),
						'compare' => '<',
					),
					array(
						'key'     => 'end_date',
						'value'   => eventkoi_gmt_date(),
						'compare' => '>',
					),
					array(
						'key'     => 'tbc',
						'value'   => true,
						'compare' => '!=',
					),
				);
			} elseif ( 'upcoming' === $args['status'] ) {
				$query_args['post_status'] = array( 'publish' );
				$query_args['meta_query'] = array( // phpcs:ignore
					'relation' => 'OR',
					array(
						'key'     => 'start_date',
						'value'   => eventkoi_gmt_date(),
						'compare' => '>',
					),
					array(
						'key'     => 'start_date',
						'compare' => 'NOT EXISTS',
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
	 */
	public static function delete_events( $ids = array() ) {

		foreach ( $ids as $id ) {
			wp_trash_post( $id );
		}

		$result = array(
			'ids'     => $ids,
			'success' => _n( 'Event moved to trash.', 'Events moved to trash.', count( $ids ), 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Remove events permanently.
	 *
	 * @param array $ids An array of events IDs to delete.
	 */
	public static function remove_events( $ids = array() ) {

		foreach ( $ids as $id ) {
			wp_delete_post( $id, true );
		}

		$result = array(
			'ids'     => $ids,
			'success' => _n( 'Event removed permanently.', 'Events removed permanently.', count( $ids ), 'eventkoi' ),
		);

		return $result;
	}

	/**
	 * Restore events.
	 *
	 * @param array $ids An array of events IDs to restore.
	 */
	public static function restore_events( $ids = array() ) {

		foreach ( $ids as $id ) {
			delete_post_meta( $id, 'start_date' );
			delete_post_meta( $id, 'end_date' );

			wp_untrash_post( $id );
		}

		$result = array(
			'ids'     => $ids,
			'success' => _n( 'Event restored successfully.', 'Events restored successfully.', count( $ids ), 'eventkoi' ),
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
				'status'      => 'live',
				'counts_only' => true,
			)
		);

		$completed = self::get_events(
			array(
				'status'      => 'completed',
				'counts_only' => true,
			)
		);

		$counts = array(
			'upcoming'  => absint( $upcoming ),
			'live'      => absint( $live ),
			'completed' => absint( $completed ),
			'draft'     => wp_count_posts( 'event' )->draft,
			'trash'     => wp_count_posts( 'event' )->trash,
		);

		return apply_filters( 'eventkoi_get_event_counts', $counts );
	}
}
