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
 * Get default date format.
 */
function eventkoi_get_default_date_format() {
	$date_format = 'Y-m-d h:i A';

	return apply_filters( 'eventkoi_get_default_date_format', $date_format );
}

/**
 * Returns date based on GMT date string.
 *
 * @param string $date A date.
 * @param bool   $gmt  If true, GMT timezone will be used.
 */
function eventkoi_date_i18n( $date, $gmt = false ) {

	$format = eventkoi_get_default_date_format();

	if ( is_numeric( $date ) && (int) $date === $date ) {
		$date = time();
	} else {
		$date = strtotime( $date );
	}

	return $gmt ? wp_date( $format, $date, new DateTimeZone( 'GMT' ) ) : wp_date( $format, $date );
}

/**
 * Returns current date based on GMT and specific format.
 *
 * @param string $format A date format.
 */
function eventkoi_gmt_date( $format = false ) {
	if ( ! $format ) {
		$format = eventkoi_get_default_date_format();
	}

	return current_time( $format, true );
}

/**
 * Get local timezone.
 */
function eventkoi_timezone() {
	$timezone = wp_timezone_string();

	return apply_filters( 'eventkoi_timezone', $timezone );
}
