<?php
/**
 * Jason functions and definitions
 *
 * @package Jason Lite
 */

if ( ! function_exists( 'jasonlite_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function jasonlite_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Jason, use a find and replace
		 * to change 'jason-lite' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'jason-lite', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		//we need this size so we can make the responsive images work better
		add_image_size( 'jasonlite-max-image', 1440, 9999, false );

		/*
		 * Add theme support for site logo
		 *
		 * First, it's the image size we want to use for the logo thumbnails
		 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
		 */
		add_theme_support( 'custom-logo', apply_filters( 'jasonlite_header_site_logo', array(
			'height'      => 600,
			'width'       => 1360,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array(
				'site-title',
				'site-description-text',
			)
		) ) );

		// This theme uses wp_nav_menu() in three locations.
		register_nav_menus( array(
			'primary'   => esc_html__( 'Primary Menu', 'jason-lite' ),
			'footer'    => esc_html__( 'Footer Menu', 'jason-lite' ),
			'social'    => esc_html__( 'Social Menu', 'jason-lite' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts and self-hosted ones
		 */
		add_editor_style( array( 'editor-style.css', jasonlite_google_fonts_url(), jasonlite_libre_caslon_text_font_url() ) );

		/*
		 * Enable support for Visible Edit Shortcuts in the Customizer Preview
		 *
		 * @link https://make.wordpress.org/core/2016/11/10/visible-edit-shortcuts-in-the-customizer-preview/
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Enable support for the Style Manager Customizer section (via Customify).
		 */
		add_theme_support( 'customizer_style_manager' );
	}
endif; // jason_setup
add_action( 'after_setup_theme', 'jasonlite_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function jasonlite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'jasonlite_content_width', 1050, 0 );
}
add_action( 'after_setup_theme', 'jasonlite_content_width', 0 );

/**
 * Set the gallery widget width in pixels, based on the theme's design and stylesheet.
 */
function jasonlite_gallery_widget_width( $args, $instance ) {
	return '1050';
}
add_filter( 'gallery_widget_content_width', 'jasonlite_gallery_widget_width', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Jason 1.2.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function jasonlite_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		1070 <= $width && $sizes = '(max-width: 899px) 100vw, (max-width: 1099px) 70vw, (max-width: 1509px) 67vw, 1070px';
		1070 > $width && $width >= 899 && $sizes = '(max-width: 899px) 100vw, (max-width: ' . $width . 'px) 70vw,' . $width . 'px';
		899 > $width && $sizes = '(max-width: ' . $width .'px) 100vw, ' . $width . 'px';
	} else {
		1440 <= $width && $sizes = '(max-width: 1440px) 100vw, 1440px';
		1440 > $width && $sizes = '(max-width: ' . $width .'px) 100vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'jasonlite_content_image_sizes_attr', 10 , 2 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function jasonlite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'jason-lite' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'jasonlite_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function jasonlite_scripts() {
	$theme = wp_get_theme( get_template() );

	/* Main Stylesheet */
	wp_enqueue_style( 'jasonlite-style', get_stylesheet_uri(), array(), $theme->get( 'Version' ) );
	wp_style_add_data( 'jasonlite-style', 'rtl', 'replace' );

	/* Default Google Fonts */

	wp_enqueue_style( 'jasonlite-google-fonts', jasonlite_google_fonts_url(), array(), null );

	/* Default Self-hosted Fonts */

	wp_enqueue_style( 'jasonlite-fonts-librecaslontext', jasonlite_libre_caslon_text_font_url() );

	/*  Branding Google Fonts */

	wp_enqueue_style( 'jasonlite-branding-google-fonts', jasonlite_branding_google_fonts_url(), array(), null );

	/*  Branding Self-hosted Fonts */
	global $wp_customize;
	if ( isset( $wp_customize ) ) {
		//when in the Customizer, load all the fonts
		wp_enqueue_style( 'jasonlite-fonts-norwester', jasonlite_norwester_font_url() );
	} else {
		//only load the fonts that are actually used depending on the branding style
		$branding_style = get_theme_mod( 'jasonlite_site_title_styling', 'site-branding--style-style1' );
		switch ( $branding_style ) {
			case 'site-branding--style-style1':
				wp_enqueue_style( 'jasonlite-fonts-norwester', jasonlite_norwester_font_url() );
				break;
			case 'site-branding--style-style2':
			case 'site-branding--style-style3':
				break;
			default:
		};
	}

	/* Enqueue Jason Custom Scripts */
	wp_register_script( 'jasonlite-velocity-js', get_stylesheet_directory_uri() . '/assets/js/velocity.js', array( 'jquery' ), '1.2.2', true );
	wp_register_script( 'jasonlite-arianav', get_stylesheet_directory_uri() . '/assets/js/arianavigation.js', array( 'jquery', 'hoverIntent' ), '1.0.0', true );

	wp_enqueue_script( 'jasonlite-scripts', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'jquery', 'jasonlite-velocity-js', 'jasonlite-arianav' ), $theme->get( 'Version' ), true );

	wp_enqueue_script( 'skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	//pass the home url so we can use it for the categories dropdown on archives
	wp_localize_script( 'jasonlite-scripts', 'jasonData', array(
		'homeUrl'   => esc_url_raw( home_url() ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'jasonlite_scripts' );

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/inc/customizer.php';
