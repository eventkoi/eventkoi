<?php
/**
 * Displays a single event.
 *
 * @package EventKoi
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'eventkoi_before_event_content' );

while ( have_posts() ) :

	if ( class_exists( 'WooCommerce' ) ) {
		wc_print_notices();
	}

	do_action( 'eventkoi_single_event_template', the_post() );

endwhile; // end of the loop.

do_action( 'eventkoi_after_event_content' );
