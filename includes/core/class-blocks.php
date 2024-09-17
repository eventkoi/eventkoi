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

		add_filter( 'wp_kses_allowed_html', array( __CLASS__, 'allow_svg_in_content' ), 10, 2 );
		add_filter( 'render_block', array( __CLASS__, 'render_event_block' ), 10, 2 );
	}

	/**
	 * Filter to enable SVG in event content.
	 *
	 * @param array  $tags Array of allowed tags.
	 * @param string $context The current context.
	 */
	public static function allow_svg_in_content( $tags, $context ) {
		if ( ! in_array( $context, array( 'post' ), true ) ) {
			return $tags;
		}

		$tags['svg'] = array(
			'class'       => array(),
			'xmlns'       => array(),
			'width'       => array(),
			'height'      => array(),
			'fill'        => array(),
			'viewbox'     => array(),
			'role'        => array(),
			'aria-hidden' => array(),
			'focusable'   => array(),
		);

		$tags['path'] = array(
			'd'               => array(),
			'transform'       => array(),
			'fill'            => array(),
			'stroke'          => array(),
			'stroke-linecap'  => array(),
			'stroke-width'    => array(),
			'stroke-linejoin' => array(),
		);

		$tags['g'] = array(
			'transform' => array(),
		);

		return $tags;
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

		if ( ! empty( $block['attrs']['className'] ) && strstr( $block['attrs']['className'], 'eventkoi-date' ) ) {
			$block_content = self::render_date( $event );
		}

		if ( ! empty( $block['attrs']['className'] ) && strstr( $block['attrs']['className'], 'eventkoi-location' ) ) {
			$block_content = self::render_location( $event );
		}

		return $block_content;
	}

	/**
	 * Render dates.
	 *
	 * @param object $event Event object.
	 */
	public static function render_date( $event ) {

		$start_date = $event::get_start_date();
		$start_date = date_i18n( 'D, M j, Y h:i A', strtotime( $start_date ) );
		$end_date   = $event::get_end_date();
		/* translators: %s event end date. */
		$end_date = $end_date ? sprintf( __( 'to %s', 'eventkoi' ), date_i18n( 'D, M j, Y h:i A', strtotime( $end_date ) ) ) : '';

		$timezone = eventkoi_timezone();

		ob_start();
		?>

		<div class="eventkoi-flex">
			<div class="eventkoi-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24"><path d="M7.688,3V5.812M20.813,3V5.812M3,22.687V8.625A2.812,2.812,0,0,1,5.813,5.812H22.688A2.812,2.812,0,0,1,25.5,8.625V22.687m-22.5,0A2.812,2.812,0,0,0,5.812,25.5H22.688A2.812,2.812,0,0,0,25.5,22.687m-22.5,0V13.312A2.812,2.812,0,0,1,5.813,10.5H22.688A2.812,2.812,0,0,1,25.5,13.312v9.375" transform="translate(-2.25 -2.25)" fill="none" stroke="#6f6f6f" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg></div>
			<?php /* translators: %1$s start date, %2$s end date, %3$s timezone */ ?>
			<div class="eventkoi-child"><?php printf( esc_html__( '%1$s %2$s %3$s', 'eventkoi' ), esc_html( $start_date ), esc_html( $end_date ), esc_html( $timezone ) ); ?></div>
		</div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Render location.
	 *
	 * @param object $event Event object.
	 */
	public static function render_location( $event ) {
		ob_start();
		?>

		<div class="eventkoi-flex">
			<div class="eventkoi-icon"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="28" viewbox="0 0 22.769 28.086"><g transform="translate(0.75 0.75)"><path d="M17.507,11.754A4.254,4.254,0,1,1,13.254,7.5,4.254,4.254,0,0,1,17.507,11.754Z" transform="translate(-2.619 -1.119)" fill="none" stroke="#6f6f6f" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/><path d="M25.769,13.634c0,10.127-10.634,15.951-10.634,15.951S4.5,23.761,4.5,13.634a10.634,10.634,0,0,1,21.269,0Z" transform="translate(-4.5 -3)" fill="none" stroke="#6f6f6f" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></g></svg></div>
			<div class="eventkoi-child">
				<div><?php echo esc_html( $event::get_address1() ); ?></div>
				<div><?php echo esc_html( $event::get_address2() ); ?></div>
				<div><?php echo esc_html( $event::get_address3() ); ?></div>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Get default event template markup.
	 */
	public static function get_default_template() {

		$content = '<!-- wp:group {"className":"eventkoi-root","layout":{"type":"constrained","wideSize":"900px","contentSize":"900px"}} -->
<div class="wp-block-group eventkoi-root"><!-- wp:image {"sizeSlug":"large","className":"eventkoi-image","style":{"border":{"radius":"10px"}}} -->
<figure class="wp-block-image size-large has-custom-border eventkoi-image"><img src="[event_image_url]" alt="" style="border-radius:10px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":2,"className":"eventkoi-heading","style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"color":{"text":"#333333"},"elements":{"link":{"color":{"text":"#333333"}}}},"fontFamily":"body"} -->
<h2 class="wp-block-heading eventkoi-heading has-text-color has-link-color has-body-font-family" style="color:#333333;font-style:normal;font-weight:600">[event_heading]</h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"border":{"radius":"20px"},"color":{"background":"#f1f1f1"},"spacing":{"blockGap":{"left":"var:preset|spacing|60"},"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns has-background" style="border-radius:20px;background-color:#f1f1f1;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"eventkoi-date"} -->
<p class="eventkoi-date">[event_date]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"eventkoi-location"} -->
<p class="eventkoi-location">[event_location]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:paragraph {"className":"eventkoi-description"} -->
<p class="eventkoi-description">[event_description]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';

		return apply_filters( 'eventkoi_get_default_template', $content );
	}
}
