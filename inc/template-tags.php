<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Jason
 */

if ( ! function_exists( 'jason_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function jason_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( 'post' == get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'jason-lite' ) );
			if ( $categories_list && jason_categorized_blog() ) {
				echo '<span class="cat-links">' . $categories_list . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Write a comment', 'jason-lite' ), esc_html__( '1 Comment', 'jason-lite' ), esc_html__( '% Comments', 'jason-lite' ) );
			echo '</span>';
		}

		jason_post_views();

		edit_post_link( esc_html__( 'Edit', 'jason-lite' ), '<span class="edit-link">', '</span>' );

	}
endif;

if ( ! function_exists( 'jason_archive_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function jason_archive_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( 'post' == get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'jason-lite' ) );
			if ( $categories_list && jason_categorized_blog() ) {
				echo '<span class="cat-links">' . $categories_list . '</span>' ; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function jason_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'jason_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'jason_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so jason_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so jason_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in jason_categorized_blog.
 */
function jason_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'jason_categories' );
}
add_action( 'edit_category', 'jason_category_transient_flusher' );
add_action( 'save_post', 'jason_category_transient_flusher' );

if ( ! function_exists( 'jason_first_content_character' ) ) :
	/**
	 * Returns the first UTF-8 character of the content
	 * returns empty string if nothing found
	 *
	 * @param bool $data_attribute
	 *
	 * @return string
	 */
	function jason_first_content_character() {
		//no need for this when a password is required
		if ( post_password_required() ) {
			return;
		}

		$content = get_the_content();

		// remove [caption] shortcode
		// because if it is the first part of the content we don't need the caption
		$content = trim( preg_replace( '/\[caption.*\[\/caption\]/si', '', $content ) );

		//now apply the regular filters, without the captions
		$content = apply_filters( 'the_content', $content );

		$content = strip_tags( html_entity_decode( $content ) );

		$first_letter = '';

		if ( ! empty( $content ) ) {

			//find the first alphanumeric character - multibyte
			preg_match( '/[\p{Xan}]/u', $content, $results );

			if ( isset( $results ) && ! empty( $results[0] ) ) {
				$first_letter = $results[0];
			} else {
				//lets try the old fashion way
				//find the first alphanumeric character - non-multibyte
				preg_match( '/[a-zA-Z\d]/', $content, $results );

				if ( isset( $results ) && ! empty( $results[0] ) ) {
					$first_letter = $results[0];
				}
			};

		};

		return $first_letter;
	}

endif;

if ( ! function_exists( 'jason_site_title' ) ) :
	/**
	 * Display the site title in the header area, applying special processing depending on Customizer options
	 */
	function jason_site_title() {
		//get the original site title
		$site_title = get_bloginfo( 'name', 'display' );

		//output sanitized site title
		echo wp_kses( $site_title, array() );
	}
endif;

if ( ! function_exists( 'jason_site_title_classes') ) :
	/**
	 * Display the class attribute for the site branding (site title and tagline)
	 *
	 * @param array $classes Classes for the site branding element.
	 */
	function jason_site_title_classes( $classes ) {
		//get the original site title
		$site_title = get_bloginfo( 'name', 'display' );
		$site_title_length = mb_strlen( $site_title, 'UTF-8' );

		//depending on the site title length we will make the site title smaller or bigger
		if ( $site_title_length < 8 ) {
			$classes[] = 'site-branding--size-large';
		} elseif ( $site_title_length > 20 ) {
			$classes[] = 'site-branding--size-small';
		} else {
			$classes[] = 'site-branding--size-medium';
		}

		//make sure everything is in proper order, security wise
		$classes = array_map( 'esc_attr', $classes );

		// Separates classes with a single space, collates classes for site branding
		echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
	}
endif;

if ( ! function_exists( 'jason_post_views' ) ) :
	/**
	 * Display the number of post views using WP.com Stats from Jetpack
	 *
	 * @param int $post_ID Optional.
	 */
	function jason_post_views( $post_ID = null ) {
		//bail if Jetpack is not present
		if ( ! function_exists( 'stats_get_csv' ) ) {
			return;
		}

		//use the current post ID if none given
		if ( empty( $post_ID ) ) {
			$post_ID = get_the_ID();
		}

		//don't display views for pages
		if ( 'page' == get_post_type( $post_ID ) ) {
			return;
		}

		$args = array(
			'days'    => - 1,
			'limit'   => - 1,
			'post_id' => $post_ID,
		);

		$result = stats_get_csv( 'postviews', $args );

		if ( ! empty( $result[0]['views'] ) ) {
			$views = $result[0]['views'];

			printf(
				/* translators: %s: the number of views */
				'<span class="post-views">' . esc_html( _nx( '%s view', '%s views', $views, 'post views', 'jason-lite' ) ) . '</span>',
				esc_html( number_format_i18n( $views ) )
			);
		}
	}
endif;

if ( ! function_exists( 'jason_the_image_navigation' ) ) :

	/**
	 * Display navigation to next/previous image attachment
	 */
	function jason_the_image_navigation() { ?>

		<nav class="navigation post-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Image navigation', 'jason-lite' ); ?></h2>
			<div class="nav-links">
				<div class="nav-previous">

					<?php //the previous image link
						adjacent_image_link( true ); ?>

				</div>
				<div class="nav-next">

					<?php //the next image link
						adjacent_image_link( false ); ?>

				</div>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->

		<?php
	} #function

endif;

if ( ! function_exists( 'jason_tags_dropdown' ) ) :
	/**
	 * Display a dropdown of tags ordered by count decending
	 * Inspired by the core function wp_tag_cloud()
	 */
	function jason_tags_dropdown() {
		$args = array(
			'number' => 45,
			'exclude' => '', 'include' => '', 'taxonomy' => 'post_tag', 'post_type' => '',
		);
		$tags = get_terms( $args['taxonomy'], array_merge( $args, array( 'orderby' => 'count', 'order' => 'DESC' ) ) ); // Always query top tags

		if ( empty( $tags ) || is_wp_error( $tags ) )
			return;

		foreach ( $tags as $key => $tag ) {
			$link = get_term_link( intval($tag->term_id), $tag->taxonomy );
			if ( is_wp_error( $link ) )
				return;

			$tags[ $key ]->link = $link;
			$tags[ $key ]->id = $tag->term_id;
		}

		//now display the HTML markup
		?>
		<select id="page-filter-by-tag" name="archive-dropdown">
			<option value=""><?php esc_html_e( 'TAG', 'jason-lite' ); ?></option>
			<?php foreach ( $tags as $key => $tag ) : ?>
				<option value="<?php echo esc_attr( $tag->link ); ?>"><?php echo esc_html( $tag->name ); ?>&nbsp;(<?php echo esc_html( $tag->count ); ?>)</option>
			<?php endforeach; ?>
		</select><!-- select -->
	<?php } #function

endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Fire the wp_body_open action.
	 *
	 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
	 *
	 * @since Jason Lite 1.1.6
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 *
		 * @since Jason Lite 1.1.6
		 */
		do_action( 'wp_body_open' );
	}
endif;
