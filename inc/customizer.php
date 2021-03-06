<?php
/**
 * Jason Lite Theme Customizer
 *
 * @package Jason Lite
 */

function jasonlite_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// View Pro
	$wp_customize->add_section( 'pro__section', array(
		'title'       => '' . esc_html__( 'View PRO Version', 'jason-lite' ),
		'priority'    => 2,
		'description' => sprintf(
		/* translators: %s is the whole upselling text */
			__( '<div class="upsell-container">
					<h2>Need More? Go PRO</h2>
					<p>Take it to the next level. See the features below:</p>
					<ul class="upsell-features">
                            <li>
                            	<h4>Personalize to Match Your Style</h4>
                            	<div class="description">Having different tastes and preferences might be tricky for users, but not with Jason onboard. It has an intuitive and catchy interface which allows you to change <strong>fonts, colors or layout sizes</strong> in a blink of an eye.</div>
                            </li>

                            <li>
                            	<h4>Adaptive Layouts For Your Posts</h4>
                            	<div class="description">Whether your featured image is in portrait or landscape mode, Jason takes care of it by changing the post layout to provide the right fit.</div>
                            </li>

                            <li>
                            	<h4>Premium Customer Support</h4>
                            	<div class="description">You will benefit by priority support from a caring and devoted team, eager to help and to spread happiness. We work hard to provide a flawless experience for those who vote us with trust and choose to be our special clients.</div>
                            </li>
                            
                    </ul> %s </div>', 'jason-lite' ),
			sprintf( '<a href="%1$s" target="_blank" class="button button-primary">%2$s</a>', esc_url( jasonlite_get_pro_link() ), esc_html__( 'View Jason PRO', 'jason-lite' ) )
		),
	) );

	$wp_customize->add_setting( 'jasonlite_style_view_pro_desc', array(
		'default'           => '',
		'sanitize_callback' => '__return_true',
	) );
	$wp_customize->add_control( 'jasonlite_style_view_pro_desc', array(
		'section' => 'pro__section',
		'type'    => 'hidden',
	) );
}
add_action( 'customize_register', 'jasonlite_customize_register' );

/**
 * Generate a link to the Jason Lite info page.
 */
function jasonlite_get_pro_link() {
	return 'https://pixelgrade.com/themes/blogging/jason-lite?utm_source=jason-lite-clients&utm_medium=customizer&utm_campaign=jason-lite#pro';
}

/**
 * Assets that will be loaded for the customizer sidebar
 */
function jasonlite_customizer_assets() {
	wp_enqueue_style( 'jasonlite_customizer_style', get_template_directory_uri() . '/inc/admin/css/customizer.css', null, '1.1.2', false );
}
add_action( 'customize_controls_enqueue_scripts', 'jasonlite_customizer_assets' );

/**
 * JavaScript that handles the Customizer AJAX logic
 * This will be added in the preview part
 */
function jasonlite_customizer_preview_assets() {
	wp_enqueue_script( 'jasonlite_customizer_preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'customize-preview' ), '1.1.2', true );
}
add_action( 'customize_preview_init', 'jasonlite_customizer_preview_assets' );
