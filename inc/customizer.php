<?php
/**
 * Jason Theme Customizer
 *
 * @package Jason
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function jason_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/*
	 * Add custom settings
	 */

	$wp_customize->add_section( 'jason_theme_options', array(
		'title'             => esc_html__( 'Theme Options', 'jason' ),
		'priority'          => 30,
	) );

	$wp_customize->add_setting( 'jason_disable_autostyle_titles', array(
		'default'           => '',
		'sanitize_callback' => 'jason_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'jason_disable_autostyle_titles', array(
		'label'             => esc_html__( 'Disable auto-style post titles', 'jason' ),
		'section'           => 'jason_theme_options',
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'jason_disable_autostyle_intro', array(
		'default'           => '',
		'sanitize_callback' => 'jason_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'jason_disable_autostyle_intro', array(
		'label'             => esc_html__( 'Disable auto-style first paragraph', 'jason' ),
		'section'           => 'jason_theme_options',
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'jason_disable_search_in_toolbar', array(
		'default'           => '',
		'sanitize_callback' => 'jason_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'jason_disable_search_in_toolbar', array(
		'label'             => esc_html__( 'Hide search button in toolbar', 'jason' ),
		'section'           => 'jason_theme_options',
		'type'              => 'checkbox',
	) );

}
add_action( 'customize_register', 'jason_customize_register' );

/**
 * Sanitize the title size.
 *
 * @param string $input.
 *
 * @return string
 */
function jason_sanitize_title_size( $input ) {
	$valid = array(
		'site-branding--size-small',
		'site-branding--size-medium',
		'site-branding--size-large',
	);

	if ( false !== array_search( $input, $valid ) ) {
		return $input;
	} else {
		return 'site-branding--size-large';
	}
}

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 *
 * @return boolean true if is 1 or '1', false if anything else
 */
function jason_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Sanitization callback for site title styling.
 *
 * @since Jason 1.0
 *
 * @param string $input Site title styling name value.
 *
 * @return string Site title styling name.
 */
function jason_sanitize_site_title_styling( $input ) {
	$valid = array(
		//'site-branding--style-regular',
		'site-branding--style-style1',
		'site-branding--style-style2',
		'site-branding--style-style3',
	);

	if ( false !== array_search( $input, $valid ) ) {
		return $input;
	} else {
		return 'site-branding--style-style1';
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function jason_customize_preview_js() {
	wp_enqueue_script( 'jason_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20150825', true );
}
add_action( 'customize_preview_init', 'jason_customize_preview_js' );
