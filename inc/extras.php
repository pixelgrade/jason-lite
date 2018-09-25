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
function jason_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ( is_single() || is_page() ) && is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'has_sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'jason_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * @since Jason 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function jason_post_classes( $classes ) {

	if ( is_archive() || is_search() ) {
		$classes[] = 'archive-article';
	}

	if ( ! ( is_archive() || is_search() ) && ! get_theme_mod( 'jason_disable_autostyle_intro' , false ) ) {
		$classes[] = 'autostyle-intro';
	}

	return $classes;
}

add_filter( 'post_class', 'jason_post_classes' );

function jason_archive_title( $title ) {
	if ( is_category() ) {
		$title = '<span class="screen-reader-text">' . esc_html__( 'Category Archive ', 'jason' ) . '</span>
					<span class="archive-subtitle">' . esc_html__( 'Browsing Category:', 'jason' ) . '</span>
					<span class="archive-title">' . single_cat_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = '<span class="screen-reader-text">' . esc_html__( 'Tag Archive ', 'jason' ) . '</span>
					<span class="archive-subtitle">' . esc_html__( 'Browsing Tag:', 'jason' ) . '</span>
					<span class="archive-title">' . single_tag_title( '', false ) . '</span>';
	} elseif ( is_date() ) {
		if ( is_year() ) {
			$title = '<span class="screen-reader-text">' . esc_html__( 'Yearly Archive ', 'jason' ) . '</span>
					<span class="archive-subtitle">' . esc_html__( 'Browsing Year:', 'jason' ) . '</span>
					<span class="archive-title">' . get_the_date( _x( 'Y', 'yearly archives date format' ) ) . '</span>';
		} elseif ( is_month() ) {
			$title = '<span class="screen-reader-text">' . esc_html__( 'Monthly Archive ', 'jason' ) . '</span>
					<span class="archive-subtitle">' . esc_html__( 'Browsing Month:', 'jason' ) . '</span>
					<span class="archive-title">' . get_the_date( _x( 'F Y', 'monthly archives date format' ) ) . '</span>';
		} elseif ( is_day() ) {
			$title = '<span class="screen-reader-text">' . esc_html__( 'Daily Archive ', 'jason' ) . '</span>
					<span class="archive-subtitle">' . esc_html__( 'Browsing Day:', 'jason' ) . '</span>
					<span class="archive-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) . '</span>';
		}
	} else {
		$title = '<span class="archive-title">' . $title . '</span>';
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'jason_archive_title' );

/**
 * Prints HTML with meta information for the tags.
 */
function jason_tags_list( $content ) {

	$tags_content = '';

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list();
		if ( $tags_list ) {
			$tags_content .= sprintf( '<span class="tags-links">' . esc_html__( '%1$s', 'jason' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	return $content . $tags_content;
}
//add this filter with a priority smaller than sharedaddy - it has 19
add_filter( 'the_content', 'jason_tags_list', 18 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Jason 1.0
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function jason_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}

add_filter( 'wp_page_menu_args', 'jason_page_menu_args' );

/*
 * Print individual comment layout
 *
 * @since Jason 1.0
 */
function jason_comment( $comment, $args, $depth ) {
	static $comment_number;

	if ( ! isset( $comment_number ) ) {
		$comment_number = $args['per_page'] * ( $args['page'] - 1 ) + 1;
	} else {
		$comment_number ++;
	}

	$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?>>
	<article id="comment-<?php comment_ID() ?>" class="comment-article  media">
		<?php
		//grab the avatar - by default the Mystery Man
		$avatar = get_avatar( $comment ); ?>

		<aside class="comment__avatar  media__img"><?php echo $avatar; ?></aside>

		<div class="media__body">
			<header class="comment__header">
				<?php printf( '<span class="comment__author-name">%s</span>', get_comment_author_link() ) ?>

				<div class="comment__meta">
					<time class="comment__time" datetime="<?php comment_time( 'c' ); ?>">
						<a href="<?php echo esc_url( get_comment_link( get_comment_ID() ) ) ?>"
						   class="comment__timestamp"><?php printf( esc_html__( '%s at %s', 'jason' ), get_comment_date(), get_comment_time() ); ?> </a>
					</time>
					<?php
					//we need some space before Edit
					edit_comment_link( esc_html__( 'Edit', 'jason' ), '  ' );

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
					<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'jason' ) ?></p>
				</div>
			<?php endif; ?>
			<section class="comment__content">
				<?php comment_text() ?>
			</section>
		</div>
	</article>
	<!-- </li> is added by WordPress automatically -->
	<?php
} // don't remove this bracket!

/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @since Jason 1.0
 *
 * @param $link
 *
 * @return string
 */
function jason_link_pages( $link ) {
	if ( is_numeric( $link ) ) {
		return '<span class="current">' . $link . '</span>';
	}

	return $link;
}

add_filter( 'wp_link_pages_link', 'jason_link_pages' );

/**
 * Wrap more link
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jason_read_more_link( $link ) {
	return '<div class="more-link-wrapper">' . $link . '</div>';
}

add_filter( 'the_content_more_link', 'jason_read_more_link' );

/**
 * We use this iterator class to recursively navigate the DOM elements of the post title
 *
 * PHP's DOM classes are recursive but don't provide an implementation of
 * RecursiveIterator. This class provides a RecursiveIterator for looping over DOMNodeList
 *
 * taken from here: http://php.net/manual/en/class.domnodelist.php#109301
 */
class JasonDOMNodeRecursiveIterator extends ArrayIterator implements RecursiveIterator {

	public function __construct( DOMNodeList $node_list ) {

		$nodes = array();
		foreach ( $node_list as $node ) {
			$nodes[] = $node;
		}

		parent::__construct( $nodes );

	}

	public function getRecursiveIterator() {
		return new RecursiveIteratorIterator( $this, RecursiveIteratorIterator::SELF_FIRST );
	}

	public function hasChildren() {
		return $this->current()->hasChildNodes();
	}


	public function getChildren() {
		return new self( $this->current()->childNodes );
	}

}

/**
 * Based on a set of rules we will try and introduce underline, italic and underline-italic sections in the title
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jason_auto_style_post_title( $title, $post_ID = null ) {
	//only do this if we are filtering the main post title, not other post titles
	if ( in_the_loop() && get_the_ID() == $post_ID ) {
		//we need to use the DOM because the title may have some markup in it due to user input or plugins messing with the title
		$dom = new DOMDocument( '1.0', 'utf-8' );
		//need to encode the utf-8 characters
		$dom->loadHTML( '<?xml encoding="UTF-8">' . '<body>' . $title . '</body>' ); //we wrap it ourselves so PHP doesn't add more markup wrapping
		$dom->encoding = 'UTF-8';

		$dit = new RecursiveIteratorIterator(
			new JasonDOMNodeRecursiveIterator( $dom->childNodes ),
			RecursiveIteratorIterator::SELF_FIRST );

		/**
		 * ENT_HTML401 is a 5.4+ constant, we need to define it for older versions
		 * http://stackoverflow.com/questions/13745353/what-do-the-ent-html5-ent-html401-modifiers-on-html-entity-decode-do
		 */
		if ( ! defined( 'ENT_HTML401' ) ) {
			define('ENT_HTML401', 0);
		}

		foreach ( $dit as $node ) {
			if ( $node->nodeType == XML_PI_NODE ) {
				$dom->removeChild( $node );
			} // remove hack

			if ( $node->nodeName == '#text' ) {
				//make sure we are dealing with HTML entities
				$node->nodeValue = htmlentities( $node->nodeValue, ENT_COMPAT | ENT_HTML401, 'UTF-8', false );
				//Make words followed by ! COLORED
				$node->nodeValue = preg_replace( '/\b(\w+\!)/u', '<em>$1</em>', $node->nodeValue );

				//Make everything in quotes COLORED
				// first the regular quotes
				$node->nodeValue = preg_replace( '/(\"[^\"]+\")/', '<em>$1</em>', $node->nodeValue );
				// then fancy/curly quotes
				$node->nodeValue = preg_replace( '/(\&\#8220\;[^\"]+\&\#8221\;)/', '<em>$1</em>', $node->nodeValue );
				//and single quotes
				$node->nodeValue = preg_replace( '/(\&\#8216\;[^\']+\&\#8217\;)/', '<em>$1</em>', $node->nodeValue );
				$node->nodeValue = preg_replace( '/(\&ldquo\;[^\&]+\&rdquo\;)/', '<em>$1</em>', $node->nodeValue );

				//Make everything between : and ! or ? UNDERLINE
				$node->nodeValue = preg_replace( '/(?<=\:)([^\:\!\?]+[\!|\?]\S*)/', '<u>$1</u>', $node->nodeValue );

				//Make everything between : and the end UNDERLINE
				$node->nodeValue = preg_replace( '/(\:\s*)([^\:]+)/', '$1<u>$2</u>', $node->nodeValue );

				//Make a title with one ? in it, COLORED at the right of the first ? encountered
				$node->nodeValue = preg_replace( '/(\A[^\?\:]+\?)([^\:]+)\z/', '$1<em>$2</em>', $node->nodeValue );
			}
		}

		# remove <!DOCTYPE
		$dom->removeChild( $dom->doctype );

		# remove <html></html>
		$dom->replaceChild( $dom->firstChild->firstChild, $dom->firstChild );

		//decode the specialchars because saveHTML() will do that for us :(
		$title = htmlspecialchars_decode( $dom->saveHTML() );

		#remove the <body> tags we've just added
		$title = preg_replace( '#<body.*?>(.*?)</body>#i', '\1', $title );
	}

	return wp_kses( $title, array(
		'u' => array(),
		'em' => array(),
	) );
}

/**
 * Generate the main Google Fonts URL
 *
 * Based on this article http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 *
 * @since Jason 1.0
 *
 * @return string
 */
function jason_google_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Source Sans Pro, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$source_sans_pro = esc_html_x( 'on', 'Source Sans Pro font: on or off', 'jason' );

	if ( 'off' !== $source_sans_pro ) {
		$font_families = array();

		if ( 'off' !== $source_sans_pro ) {
			$font_families[] = 'Source Sans Pro:300,400,600,300italic,400italic,600italic';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
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
function jason_libre_caslon_text_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Libre Caslon Text, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$libre_caslon_text = esc_html_x( 'on', 'Libre Caslon Text font: on or off', 'jason' );
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
function jason_norwester_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Norwester, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$norwester = esc_html_x( 'on', 'Norwester font: on or off', 'jason' );
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
function jason_branding_google_fonts_url() {
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
	$sacramento = esc_html_x( 'on', 'Sacramento font: on or off', 'jason' );

	/* Translators: If there are characters in your language that are not
	* supported by Josefin Sans, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$josefin_sans = esc_html_x( 'on', 'Josefin Sans font: on or off', 'jason' );
	$waiting_sunrise = esc_html_x( 'on', 'Waiting For The Sunrise font: on or off', 'jason' );
	$permanent_marker = esc_html_x( 'on', 'Permanent Marker font: on or off', 'jason' );

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
			'family' => urlencode( implode( '|', $font_families ) ),
		);

		//to optimize things further we only load the characters actually used in the branding (site title and tagline)
		if ( true === $partial_chars ) {
			//a little preparation for the site title string
			$characters = mb_strtolower( preg_replace( '/\s+/', '', strip_tags( $site_title . $site_description ) ) );
			//now extract only the unique characters, just to keep things clean
			$characters = jason_get_unique_chars( $characters );
			//now also include the uppercase characters
			$characters .= mb_strtoupper( $characters );

			//there is no need for charsets like latin or latin-ext because we specify the characters we need directly
			$query_args['text'] = urlencode( $characters );
		} else {
			$query_args['subset'] = urlencode( 'latin,latin-ext' );
		}

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Returns an array with the halves of the provided string. It cuts by spaces.
 *
 * @since Jason 1.0
 *
 * @param string $content The string to cut
 * @param float $threshold Optional. If a space is not found in the first half, for how long should we search next.
 * This is a percentage of the total $content length
 *
 * @return array with the two parts or false if it could not split
 */
function jason_get_mb_halves( $content, $threshold = 0.25 ) {
	//Strip HTML tags off the text
	$content = strip_tags( $content );
	//Convert HTML special chars into normal text
	$content = html_entity_decode( $content );
	//Also cut line breaks
	$content = str_replace( array( "\r", "\n" ), '', $content );

	$content_length = mb_strlen( $content, 'UTF-8' );
	//If we have a string that is one or two characters long we really don't have anything to work with
	if ( $content_length < 3 ) {
		return false;
	}

	//determine the middle point
	$limit = intval( $content_length / 2 );

	//Find the last space symbol position within the given range
	$last_space = mb_strrpos( mb_substr( $content, 0, $limit, 'UTF-8' ), ' ', 0, 'UTF-8' );

	if ( false === $last_space ) {
		//it seems we haven't found a space in the first exact half
		//let's give it another change by searching for the next space to the right
		$next_space = mb_strpos( $content, ' ', $limit, 'UTF-8' );

		//only split here if we are no further than the $threshold from the middle
		if ( false !== $next_space && $next_space <= floor( $content_length * ( 0.5 + $threshold ) ) ) {
			$last_space = $next_space;
		}
	}

	//if no space was found, return the original
	if ( false === $last_space ) {
		return false;
	}

	return array( mb_substr( $content, 0, $last_space, 'UTF-8' ), mb_substr( $content, $last_space, 9999, 'UTF-8' ) );
}

/**
 * Wraps the first part of the string in the $before and $after strings. It cuts by spaces.
 *
 * @since Jason 1.0
 *
 * @param string $content The string to wrap
 * @param string $before Optional. The string wrap at the front.
 * @param string $after Optional. The string wrap at the end.
 * @param float $threshold Optional. If a space is not found in the first half, for how long should we search next.
 * This is a percentage of the total $content length
 *
 * @return string
 */
function jason_mb_first_half_wrap( $content, $before = '', $after = '', $threshold = 0.25 ) {
	$halves = jason_get_mb_halves( $content, $threshold );

	if ( false === $halves ) {
		return $content;
	}

	//Return the string with the front part wrapped by before and after inserted
	return $before . $halves[0] . $after . $halves[1];
}

/**
 * Gets a string (UTF-8) and returns a string with all the unique characters
 * Taken from this answer on StackOverflow.com http://stackoverflow.com/a/5414735
 *
 * @param string $text
 *
 * @return string
 */
function jason_get_unique_chars( $text ) {
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
function jason_wrap_images_in_figure( $content ) {
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

add_filter( 'the_content', 'jason_wrap_images_in_figure' );

/**
 * Customize the auto excerpt more string
 */
function jason_custom_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter('excerpt_more', 'jason_custom_excerpt_more');
