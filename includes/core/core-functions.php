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
 * Get template header.
 */
function eventkoi_get_header() {

	$content = '<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->';

	return do_blocks( apply_filters( 'eventkoi_get_header', $content ) );
}

/**
 * Get template footer.
 */
function eventkoi_get_footer() {

	$content = '<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->';

	return do_blocks( apply_filters( 'eventkoi_get_footer', $content ) );
}

/**
 * Get template content.
 */
function eventkoi_get_content() {

	$template = get_post( get_option( 'eventkoi_default_template_id' ) );

	$blocks = new \EventKoi\Core\Blocks();

	if ( ! empty( $template ) && ! empty( $template->post_content ) ) {
		$event_template = $template->post_content;
	} else {
		$event_template = $blocks::get_default_template();
	}

	return do_blocks( apply_filters( 'eventkoi_get_content', $event_template ) );
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
			'category_base'          => _x( 'calendar', 'slug', 'eventkoi' ),
			'use_verbose_page_rules' => false,
		)
	);

	if ( $saved_permalinks !== $permalinks ) {
		update_option( 'eventkoi_permalinks', $permalinks );
	}

	$permalinks['event_rewrite_slug']    = untrailingslashit( $permalinks['event_base'] );
	$permalinks['category_rewrite_slug'] = untrailingslashit( $permalinks['category_base'] );

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
 * Get GMT time from date given.
 *
 * @param string $date A date.
 */
function eventkoi_get_gmt_from_date( $date ) {
	if ( ! $date ) {
		return '';
	}

	return get_gmt_from_date( $date, eventkoi_get_default_date_format() );
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
	$timezone    = wp_timezone_string();
	$wp_timezone = (array) wp_timezone();

	if ( ! empty( $wp_timezone['timezone_type'] ) ) {
		if ( 1 === $wp_timezone['timezone_type'] ) {
			$timezone = str_replace( ':00', '', $timezone );
			$timezone = str_replace( '+0', '+', $timezone );
			$timezone = str_replace( '-0', '-', $timezone );
			$timezone = 'UTC' . $timezone;
		}
	}

	return apply_filters( 'eventkoi_timezone', $timezone );
}

/**
 * Define a constant if it is not already defined.
 *
 * @param string $name  Constant name.
 * @param mixed  $value Value.
 */
function eventkoi_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}
