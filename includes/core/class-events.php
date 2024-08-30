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

		$status = array( 'publish', 'draft' );

		if ( ! empty( $args['status'] ) ) {
			if ( 'trash' === $args['status'] ) {
				$status = array( 'trash' );
			}
		}

		$args = array(
			'post_type'      => 'event',
			'orderby'        => 'modified',
			'order'          => 'DESC',
			'posts_per_page' => -1,
			'post_status'    => $status,
		);

		$query = new \WP_Query( $args );

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
}
