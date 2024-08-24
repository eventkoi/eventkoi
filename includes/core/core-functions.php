<?php
/**
 * Core functions.
 *
 * @package    EventKoi
 * @subpackage EventKoi\Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get permalink structure.
 */
function eventkoi_get_permalink_structure() {

	$saved_permalinks = (array) get_option( 'eventkoi_permalinks', array() );
	$permalinks       = wp_parse_args(
		array_filter( $saved_permalinks ),
		array(
			'event_base'             => _x( 'event', 'slug', 'eventkoi' ),
			'use_verbose_page_rules' => false,
		)
	);

	if ( $saved_permalinks !== $permalinks ) {
		update_option( 'eventkoi_permalinks', $permalinks );
	}

	$permalinks['event_rewrite_slug'] = untrailingslashit( $permalinks['event_base'] );

	return $permalinks;
}

/**
 * Get current theme support.
 */
function eventkoi_current_theme_support() {
	if ( function_exists( 'wp_is_block_theme' ) ) {
		return (bool) wp_is_block_theme();
	}
	if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
		return (bool) gutenberg_is_fse_theme();
	}

	return false;
}

/**
 * Display date.
 *
 * @param string $date A date string.
 */
function eventkoi_date_display( $date ) {

	$date_format = 'Y-m-d h:i A';

	if ( is_numeric( $date ) && (int) $date === $date ) {
		$date = time();
	} else {
		$date = strtotime( $date );
	}

	return wp_date( $date_format, $date );
}
