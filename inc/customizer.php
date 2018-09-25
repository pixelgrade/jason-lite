<?php
/**
 * Jason Lite Theme Customizer
 *
 * @package Jason
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function jason_customize_preview_js() {
	wp_enqueue_script( 'jason_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20150825', true );
}
add_action( 'customize_preview_init', 'jason_customize_preview_js' );
