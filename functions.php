<?php
/**
 * Jason functions and definitions
 *
 * @package Jason
 */

if ( ! function_exists( 'jason_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function jason_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Jason, use a find and replace
		 * to change 'jason' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'jason', get_template_directory() . '/languages' );

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
		add_image_size( 'jason-max-image', 1440, 9999, false );

		/*
		 * Add theme support for site logo
		 *
		 * First, it's the image size we want to use for the logo thumbnails
		 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
		 */
		add_theme_support( 'custom-logo', apply_filters( 'jason_header_site_logo', array(
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
			'primary'   => esc_html__( 'Primary Menu', 'jason' ),
			'footer'    => esc_html__( 'Footer Menu', 'jason' ),
			'social'    => esc_html__( 'Social Menu', 'jason' ),
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
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		/*
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts and self-hosted ones
		 */
		add_editor_style( array( 'editor-style.css', jason_google_fonts_url(), jason_libre_caslon_text_font_url() ) );

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
add_action( 'after_setup_theme', 'jason_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function jason_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'jason_content_width', 1050, 0 );
}
add_action( 'after_setup_theme', 'jason_content_width', 0 );

/**
 * Set the gallery widget width in pixels, based on the theme's design and stylesheet.
 */
function jason_gallery_widget_width( $args, $instance ) {
	return '1050';
}
add_filter( 'gallery_widget_content_width', 'jason_gallery_widget_width', 10, 3 );

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
function jason_content_image_sizes_attr( $sizes, $size ) {
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
add_filter( 'wp_calculate_image_sizes', 'jason_content_image_sizes_attr', 10 , 2 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function jason_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'jason' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'jason_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function jason_scripts() {
	/* Main Stylesheet */

	wp_enqueue_style( 'jason-style', get_stylesheet_uri() );

	/* Default Google Fonts */

	wp_enqueue_style( 'jason-google-fonts', jason_google_fonts_url(), array(), null );

	/* Default Self-hosted Fonts */

	wp_enqueue_style( 'jason-fonts-librecaslontext', jason_libre_caslon_text_font_url() );

	/*  Branding Google Fonts */

	wp_enqueue_style( 'jason-branding-google-fonts', jason_branding_google_fonts_url(), array(), null );

	/*  Branding Self-hosted Fonts */
	global $wp_customize;
	if ( isset( $wp_customize ) ) {
		//when in the Customizer, load all the fonts
		wp_enqueue_style( 'jason-fonts-norwester', jason_norwester_font_url() );
	} else {
		//only load the fonts that are actually used depending on the branding style
		$branding_style = get_theme_mod( 'jason_site_title_styling', 'site-branding--style-style1' );
		switch ( $branding_style ) {
			case 'site-branding--style-style1':
				wp_enqueue_style( 'jason-fonts-norwester', jason_norwester_font_url() );
				break;
			case 'site-branding--style-style2':
			case 'site-branding--style-style3':
				break;
			default:
		};
	}

	/* Enqueue Jason Custom Scripts */
	wp_enqueue_script( 'jason-velocity-js', get_stylesheet_directory_uri() . '/assets/js/velocity.js', array( 'jquery' ), '1.2.2', true );
	wp_enqueue_script( 'jason-hover-intent', get_stylesheet_directory_uri() . '/assets/js/jquery.hoverIntent.js', array( 'jquery' ), '1.8.1', true );
	wp_enqueue_script( 'jason-arianav', get_stylesheet_directory_uri() . '/assets/js/arianavigation.js', array( 'jquery', 'jason-hover-intent' ), '1.0.0', true );
	wp_enqueue_script( 'jason-scripts', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'jquery', 'jason-velocity-js', 'jason-arianav' ), '1.0.0', true );

	//pass the home url so we can use it for the categories dropdown on archives
	wp_localize_script( 'jason-scripts', 'jasonData', array(
		'homeUrl'   => esc_url_raw( home_url() ),
	) );

	//pass the home url so we can use it for the categories dropdown on archives
	wp_localize_script( 'jason-scripts', 'jasonData', array(
		'homeUrl'   => esc_url_raw( home_url() ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'jason_scripts' );

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

/**
 * Theme About page.
 */
require get_template_directory() . '/inc/admin/about-page.php';
