<?php
/**
 * Blocks.
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
 * Event.
 */
class Blocks {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_filter( 'render_block', array( __CLASS__, 'render_event_block' ), 10, 2 );
	}

	/**
	 * Get event.
	 *
	 * @param string $block_content This is the block content.
	 * @param array  $block The block array.
	 */
	public static function render_event_block( $block_content, $block ) {

		$event = new Event( get_the_ID() );

		if ( ! empty( $block['attrs']['className'] ) && strstr( $block['attrs']['className'], 'eventkoi-root' ) ) {
			$id            = $event::get_id();
			$block_content = str_replace( 'class="wp-block-group eventkoi-root', 'data-event="' . absint( $id ) . '" class="wp-block-group eventkoi-root', $block_content );
		}

		if ( ! empty( $block['attrs']['className'] ) && strstr( $block['attrs']['className'], 'eventkoi-heading' ) ) {
			$title         = wp_kses_post( $event::get_title() );
			$block_content = $title ? str_replace( '[event_heading]', $title, $block_content ) : '';
		}

		if ( ! empty( $block['attrs']['className'] ) && strstr( $block['attrs']['className'], 'eventkoi-image' ) ) {
			$image_url     = wp_kses_post( $event::get_image() );
			$block_content = $image_url ? str_replace( '[event_image_url]', $image_url, $block_content ) : '';
		}

		if ( ! empty( $block['attrs']['className'] ) && strstr( $block['attrs']['className'], 'eventkoi-description' ) ) {
			$description   = nl2br( $event::get_description() );
			$block_content = $description ? str_replace( '[event_description]', $description, $block_content ) : '';
		}

		return $block_content;
	}

	/**
	 * Get default event template markup.
	 */
	public static function get_default_template() {

		$content = '<!-- wp:group {"className":"eventkoi-root","layout":{"type":"constrained","wideSize":"900px","contentSize":"900px"}} -->
<div class="wp-block-group eventkoi-root"><!-- wp:image {"sizeSlug":"large","className":"eventkoi-image","style":{"border":{"radius":"10px"}}} -->
<figure class="wp-block-image size-large has-custom-border eventkoi-image"><img src="[event_image_url]" alt="" style="border-radius:10px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"className":"eventkoi-heading","style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"color":{"text":"#333333"},"elements":{"link":{"color":{"text":"#333333"}}}},"fontFamily":"body"} -->
<h3 class="wp-block-heading eventkoi-heading has-text-color has-link-color has-body-font-family" style="color:#333333;font-style:normal;font-weight:600">[event_heading]</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"eventkoi-description"} -->
<p class="eventkoi-description">[event_description]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';

		return apply_filters( 'eventkoi_get_default_template', $content );
	}
}
