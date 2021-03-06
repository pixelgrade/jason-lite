<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Jason
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function jasonlite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ( is_single() || is_page() ) && is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'has_sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'jasonlite_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * @since Jason 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function jasonlite_post_classes( $classes ) {

	if ( is_archive() || is_search() ) {
		$classes[] = 'archive-article';
	}

	return $classes;
}
add_filter( 'post_class', 'jasonlite_post_classes' );

// This function should come from Customify, but we need to do our best to make things happen
if ( ! function_exists( 'pixelgrade_option' ) ) {
	/**
	 * Get option from the database
	 *
	 * @param string $option The option name.
	 * @param mixed  $default Optional. The default value to return when the option was not found or saved.
	 * @param bool   $force_default Optional. When true, we will use the $default value provided for when the option was not saved at least once.
	 *                            When false, we will let the option's default set value (in the Customify settings) kick in first, then our $default.
	 *                            It basically, reverses the order of fallback, first the option's default, then our own.
	 *                            This is ignored when $default is null.
	 *
	 * @return mixed
	 */
	function pixelgrade_option( $option, $default = null, $force_default = false ) {
		/** @var PixCustomifyPlugin $pixcustomify_plugin */
		global $pixcustomify_plugin;

		if ( $pixcustomify_plugin !== null ) {
			// Customify is present so we should get the value via it
			// We need to account for the case where a option has an 'active_callback' defined in it's config
			$options_config = $pixcustomify_plugin->get_options_configs();
			if ( ! empty( $options_config ) && ! empty( $options_config[ $option ] ) && ! empty( $options_config[ $option ]['active_callback'] ) ) {
				// This option has an active callback
				// We need to "question" it
				//
				// IMPORTANT NOTICE:
				//
				// Be extra careful when setting up the options to not end up in a circular logic
				// due to callbacks that get an option and that option has a callback that gets the initial option - INFINITE LOOPS :(
				if ( is_callable( $options_config[ $option ]['active_callback'] ) ) {
					// Now we call the function and if it returns false, this means that the control is not active
					// Hence it's saved value doesn't matter
					$active = call_user_func( $options_config[ $option ]['active_callback'] );
					if ( empty( $active ) ) {
						// If we need to force the default received; we respect that
						if ( true === $force_default && null !== $default ) {
							return $default;
						} else {
							// Else we return false
							// because we treat the case when the active callback returns false as if the option would be non-existent
							// We do not return the default configured value in this case
							return false;
						}
					}
				}
			}

			// Now that the option is truly active, we need to see if we are not supposed to force over the option's default value
			if ( $default !== null && false === $force_default ) {
				// We will not pass the received $default here so Customify will fallback on the option's default value, if set
				$customify_value = $pixcustomify_plugin->get_option( $option );

				// We only fallback on the $default if none was given from Customify
				if ( null === $customify_value ) {
					return $default;
				}
			} else {
				$customify_value = $pixcustomify_plugin->get_option( $option, $default );
			}

			return $customify_value;
		} elseif ( false === $force_default ) {
			// In case there is no Customify present and we were not supposed to force the default
			// we want to know what the default value of the option should be according to the configuration
			// For this we will fire the all-gathering-filter that Customify uses
			$config = apply_filters( 'customify_filter_fields', array() );

			// Next we will search for this option and see if it has a default value set ('default')
			if ( ! empty( $config['sections'] ) && is_array( $config['sections'] ) ) {
				foreach ( $config['sections'] as $section ) {
					if ( ! empty( $section['options'] ) && is_array( $section['options'] ) ) {
						foreach ( $section['options'] as $option_id => $option_config ) {
							if ( $option_id == $option ) {
								// We have found our option (the option ID should be unique)
								// It's time to deal with it's default, if it has one
								if ( isset( $option_config['default'] ) ) {
									return $option_config['default'];
								}

								// If the targeted option doesn't have a default value
								// there is no point in searching further because the option IDs should be unique
								// Just return the $default
								return $default;
							}
						}
					}
				}
			}
		}

		// If all else failed, return the default (even if it's null)
		return $default;
	}
}

function jasonlite_archive_title( $title ) {
	if ( is_category() ) {
		$title = '<span class="screen-reader-text">' . esc_html__( 'Category Archive ', 'jason-lite' ) . '</span>
				<span class="archive-subtitle">' . esc_html__( 'Browsing Category:', 'jason-lite' ) . '</span>
				<span class="archive-title">' . single_cat_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = '<span class="screen-reader-text">' . esc_html__( 'Tag Archive ', 'jason-lite' ) . '</span>
				<span class="archive-subtitle">' . esc_html__( 'Browsing Tag:', 'jason-lite' ) . '</span>
				<span class="archive-title">' . single_tag_title( '', false ) . '</span>';
	} elseif ( is_date() ) {
		if ( is_year() ) {
			$title = '<span class="screen-reader-text">' . esc_html__( 'Yearly Archive ', 'jason-lite' ) . '</span>
				<span class="archive-subtitle">' . esc_html__( 'Browsing Year:', 'jason-lite' ) . '</span>
				<span class="archive-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'jason-lite' ) ) . '</span>';
		} elseif ( is_month() ) {
			$title = '<span class="screen-reader-text">' . esc_html__( 'Monthly Archive ', 'jason-lite' ) . '</span>
				<span class="archive-subtitle">' . esc_html__( 'Browsing Month:', 'jason-lite' ) . '</span>
				<span class="archive-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'jason-lite' ) ) . '</span>';
		} elseif ( is_day() ) {
			$title = '<span class="screen-reader-text">' . esc_html__( 'Daily Archive ', 'jason-lite' ) . '</span>
				<span class="archive-subtitle">' . esc_html__( 'Browsing Day:', 'jason-lite' ) . '</span>
				<span class="archive-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'jason-lite' ) ) . '</span>';
		}
	} else {
		$title = '<span class="archive-title">' . $title . '</span>';
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'jasonlite_archive_title' );

/**
 * Prints HTML with meta information for the tags.
 */
function jasonlite_tags_list( $content ) {

	$tags_content = '';

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list();
		if ( $tags_list ) {
			$tags_content .= '<span class="tags-links">' . $tags_list . '</span>';
		}
	}

	return $content . $tags_content;
}
//add this filter with a priority smaller than sharedaddy - it has 19
add_filter( 'the_content', 'jasonlite_tags_list', 18 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Jason 1.0
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function jasonlite_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}
add_filter( 'wp_page_menu_args', 'jasonlite_page_menu_args' );

/**
 * Print individual comment layout
 *
 * @param WP_Comment $comment
 * @param array $args
 * @param int $depth
 */
function jasonlite_comment( $comment, $args, $depth ) {
	static $comment_number;

	if ( ! isset( $comment_number ) ) {
		$comment_number = $args['per_page'] * ( $args['page'] - 1 ) + 1;
	} else {
		$comment_number ++;
	} ?>
<li <?php comment_class(); ?>>
	<article id="comment-<?php comment_ID() ?>" class="comment-article  media">

		<aside class="comment__avatar  media__img"><?php echo get_avatar( $comment ); ?></aside>

		<div class="media__body">
			<header class="comment__header">
				<?php printf( '<span class="comment__author-name">%s</span>', get_comment_author_link( $comment->comment_ID ) ) ?>

				<div class="comment__meta">
					<time class="comment__time" datetime="<?php comment_time( 'c' ); ?>">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"
						   class="comment__timestamp"><?php /* translators: %1$s comment date, %2$s comment timestamp */ printf( esc_html__( '%1$s at %2$s', 'jason-lite' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) ); ?> </a>
					</time>
					<?php
						edit_comment_link( esc_html__( 'Edit', 'jason-lite' ), '  ' );

						comment_reply_link( array_merge( $args, array(
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
						) ) );
					?>
				</div>

			</header>
			<!-- .comment-meta -->
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<div class="alert info">
					<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'jason-lite' ) ?></p>
				</div>
			<?php endif; ?>
			<section class="comment__content">
				<?php comment_text( $comment->comment_ID ); ?>
			</section>
		</div>
	</article>
	<!-- </li> is added by WordPress automatically -->
	<?php
}

/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @since Jason 1.0
 *
 * @param $link
 *
 * @return string
 */
function jasonlite_link_pages( $link ) {
	if ( is_numeric( $link ) ) {
		return '<span class="current">' . $link . '</span>';
	}

	return $link;
}
add_filter( 'wp_link_pages_link', 'jasonlite_link_pages' );

/**
 * Wrap more link
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jasonlite_read_more_link( $link ) {
	return '<div class="more-link-wrapper">' . $link . '</div>';
}
add_filter( 'the_content_more_link', 'jasonlite_read_more_link' );

/**
 * Generate the main Google Fonts URL
 *
 * Based on this article http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jasonlite_google_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Source Sans Pro, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$source_sans_pro = esc_html_x( 'on', 'Source Sans Pro font: on or off', 'jason-lite' );

	if ( 'off' !== $source_sans_pro ) {
		$font_families = array();

		if ( 'off' !== $source_sans_pro ) {
			$font_families[] = 'Source Sans Pro:300,400,600,300italic,400italic,600italic';
		}

		$query_args = array(
			'family' => rawurlencode( implode( '|', $font_families ) ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Generate the Libre Caslon Text font URL
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jasonlite_libre_caslon_text_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Libre Caslon Text, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$libre_caslon_text = esc_html_x( 'on', 'Libre Caslon Text font: on or off', 'jason-lite' );
	if ( 'off' !== $libre_caslon_text ) {
		return get_stylesheet_directory_uri() . '/assets/fonts/librecaslontext/stylesheet.css';
	}

	return '';
}

/**
 * Generate the Norwester font URL
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jasonlite_norwester_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Norwester, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$norwester = esc_html_x( 'on', 'Norwester font: on or off', 'jason-lite' );
	if ( 'off' !== $norwester ) {
		return get_stylesheet_directory_uri() . '/assets/fonts/norwester/font-norwester.css';
	}

	return '';
}


/**
 * Generate the branding Google Fonts URL, in case the site title text is displayed
 *
 * Based on this article http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 *
 * @since Jason 1.0
 *
 * @param boolean $partial_chars Optional. If true we only request title characters from Google Plus.
 * If false the whole latin and latin-ext charsets are requested.
 * @return string
 */
function jasonlite_branding_google_fonts_url() {
	$fonts_url = '';

	//when in the Customizer we want to load the whole latin and latin-ext subsets so one can change it's site title at will
	global $wp_customize;
	$partial_chars = isset( $wp_customize ) ? false : true;

	//get the original site title
	$site_title = get_bloginfo( 'name', 'display' );
	//get the original site description
	$site_description = get_bloginfo( 'description', 'display' );

	/* Translators: If there are characters in your language that are not
	* supported by Sacramento, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$sacramento = esc_html_x( 'on', 'Sacramento font: on or off', 'jason-lite' );

	/* Translators: If there are characters in your language that are not
	* supported by Josefin Sans, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$josefin_sans = esc_html_x( 'on', 'Josefin Sans font: on or off', 'jason-lite' );
	$waiting_sunrise = esc_html_x( 'on', 'Waiting For The Sunrise font: on or off', 'jason-lite' );
	$permanent_marker = esc_html_x( 'on', 'Permanent Marker font: on or off', 'jason-lite' );

	/* Now handle the actual fonts url */

	$font_families = array();

	if ( isset( $wp_customize ) ) {
		//load all the fonts
		if ( 'off' !== $sacramento ) {
			$font_families[] = 'Sacramento';
		}

		if ( 'off' !== $josefin_sans ) {
			$font_families[] = 'Josefin Sans:300';
		}
		if ( 'off' !== $waiting_sunrise ) {
			$font_families[] = 'Waiting for the Sunrise';
		}
		if ( 'off' !== $permanent_marker ) {
			$font_families[] = 'Permanent Marker';
		}
	} else {
		//only load the fonts that are actually used depending on the branding style - when not in the Customizer
		$branding_style = get_theme_mod( 'jason_site_title_styling', 'site-branding--style-style1' );

		switch ( $branding_style ) {
			case 'site-branding--style-style1':
				if ( 'off' !== $sacramento ) {
					$font_families[] = 'Sacramento';
				}
				break;
			case 'site-branding--style-style2':
				if ( 'off' !== $josefin_sans ) {
					$font_families[] = 'Josefin Sans:300';
				}
				if ( 'off' !== $waiting_sunrise ) {
					$font_families[] = 'Waiting for the Sunrise';
				}
				break;
			case 'site-branding--style-style3':
				if ( 'off' !== $josefin_sans ) {
					$font_families[] = 'Josefin Sans:300';
				}
				if ( 'off' !== $permanent_marker ) {
					$font_families[] = 'Permanent Marker';
				}
				break;
			default: //do nothing
		};
	}

	if ( ! empty( $font_families ) ) {

		$query_args = array(
			'family' => rawurlencode( implode( '|', $font_families ) ),
		);

		//to optimize things further we only load the characters actually used in the branding (site title and tagline)
		if ( true === $partial_chars ) {
			//a little preparation for the site title string
			$characters = mb_strtolower( preg_replace( '/\s+/', '', strip_tags( $site_title . $site_description ) ) );
			//now extract only the unique characters, just to keep things clean
			$characters = jasonlite_get_unique_chars( $characters );
			//now also include the uppercase characters
			$characters .= mb_strtoupper( $characters );

			//there is no need for charsets like latin or latin-ext because we specify the characters we need directly
			$query_args['text'] = rawurlencode( $characters );
		} else {
			$query_args['subset'] = rawurlencode( 'latin,latin-ext' );
		}

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Gets a string (UTF-8) and returns a string with all the unique characters
 * Taken from this answer on StackOverflow.com http://stackoverflow.com/a/5414735
 *
 * @param string $text
 *
 * @return string
 */
function jasonlite_get_unique_chars( $text ) {
	$l = mb_strlen( $text );
	$unique = array();
	for ( $i = 0; $i < $l; $i++ ) {
		$char = mb_substr( $text, $i, 1 );
		if ( ! array_key_exists( $char, $unique ) ) {
			$unique[ $char ] = 0;
		}
		$unique[ $char ]++;
	}
	return join('', array_keys( $unique ) );
}

/**
 * Due to the fact that we need a wrapper for center aligned images and for the ones with alignnone, we need to wrap the images without a caption
 * The images with captions already are wrapped by the figure tag
 *
 * @param string $content
 *
 * @return string
 */
function jasonlite_wrap_images_in_figure( $content ) {
	$classes = array ('aligncenter', 'alignnone');

	foreach ($classes as $class) {

		//this regex basically tells this
		//match all the images that are not in captions and that have the X class
		//when an image is wrapped by an anchor tag, match that too
		$regex = '~\[caption[^\]]*\].*\[\/caption\]|((?:<a[^>]*>\s*)?<img.*class="[^"]*' . $class . '[^"]*[^>]*>(?:\s*<\/a>)?)~i';

		// Replace the matches
		$content = preg_replace_callback(
			$regex,
			// in the callback function, if Group 1 is empty,
			// set the replacement to the whole match,
			// i.e. don't replace
			// PS: I know this is a PHP 5.3 but it's too elegant to pass :)
			// @codingStandardsIgnoreLine
			function ( $m ) use ($class) {

				if ( empty( $m[1] ) ) {
					return $m[0];
				}

				return '<span class="' . $class . '">' . $m[1] . '</span>';
			},
			$content );
	}

	return $content;
}
add_filter( 'the_content', 'jasonlite_wrap_images_in_figure' );

/**
 * Customize the auto excerpt more string
 */
function jasonlite_custom_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter('excerpt_more', 'jasonlite_custom_excerpt_more');

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function jasonlite_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
// We will put this script inline since it is so small.
add_action( 'wp_print_footer_scripts', 'jasonlite_skip_link_focus_fix' );
