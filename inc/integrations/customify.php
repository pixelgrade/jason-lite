<?php
/**
 * Jason Lite Customizer Options Config.
 *
 * @package Jason Lite
 */

/**
 * Hook into the Customify's fields and settings.
 *
 * The config can turn to be complex so is best to visit:
 * https://github.com/pixelgrade/customify
 *
 * @param array $options Contains the plugin's options array right before they are used, so edit with care.
 *
 * @return array The returned options are required, if you don't need options return an empty array.
 */

add_filter( 'customify_filter_fields', 'jasonlite_add_customify_options', 11, 1 );
add_filter( 'customify_filter_fields', 'jasonlite_add_customify_style_manager_section', 12, 1 );

add_filter( 'customify_filter_fields', 'jasonlite_fill_customify_options', 20 );

define( 'JASONLITE_SM_COLOR_PRIMARY', '#D65456' );
define( 'JASONLITE_SM_COLOR_SECONDARY', '#80C9DD' );
define( 'JASONLITE_SM_COLOR_TERTIARY', '#F55859' );

define( 'JASONLITE_SM_DARK_PRIMARY', '#222222' );
define( 'JASONLITE_SM_DARK_SECONDARY', '#AAAFB3' );
define( 'JASONLITE_SM_DARK_TERTIARY', '#3A4249' );

define( 'JASONLITE_SM_LIGHT_PRIMARY', '#FFFFFF' );
define( 'JASONLITE_SM_LIGHT_SECONDARY', '#EBEBEB' );
define( 'JASONLITE_SM_LIGHT_TERTIARY', '#DDDDDD' );

function jasonlite_add_customify_options( $options ) {
	$options['opt-name'] = 'jason_options';

	$options['sections'] = array();

	return $options;
}

/**
 * Add the Style Manager cross-theme Customizer section.
 *
 * @param array $options
 *
 * @return array
 */
function jasonlite_add_customify_style_manager_section( $options ) {
	// If the theme hasn't declared support for style manager, bail.
	if ( ! current_theme_supports( 'customizer_style_manager' ) ) {
		return $options;
	}

	if ( ! isset( $options['sections']['style_manager_section'] ) ) {
		$options['sections']['style_manager_section'] = array();
	}

	$new_config = array(
		'options' => array(
			'sm_color_primary'   => array(
				'default'          => JASONLITE_SM_COLOR_PRIMARY,
				'connected_fields' => array(
					'primary_color',
				),
			),
			'sm_color_secondary' => array(
				'default'          => JASONLITE_SM_COLOR_SECONDARY,
				'connected_fields' => array(
					'secondary_color',
				),
			),
			'sm_color_tertiary'  => array(
				'default'          => JASONLITE_SM_COLOR_TERTIARY,
				'connected_fields' => array(
					'secondary_site_title_color',
				),
			),
			'sm_dark_primary'    => array(
				'default'          => JASONLITE_SM_DARK_PRIMARY,
				'connected_fields' => array(
					'primary_site_title_color',
				),
			),
			'sm_dark_secondary'  => array(
				'default'          => JASONLITE_SM_DARK_SECONDARY,
				'connected_fields' => array(
					'body_color',
				),
			),
			'sm_dark_tertiary'   => array(
				'default'          => JASONLITE_SM_DARK_TERTIARY,
				'connected_fields' => array(
					'tertiary_dark_color'
				),
			),
			'sm_light_primary'   => array(
				'default'          => JASONLITE_SM_LIGHT_PRIMARY,
				'connected_fields' => array(
					'background_color',
				),
			),
			'sm_light_secondary' => array(
				'default'          => JASONLITE_SM_LIGHT_SECONDARY,
			),
			'sm_light_tertiary'  => array(
				'default' => JASONLITE_SM_LIGHT_TERTIARY,
			),
		),
	);

	// The section might be already defined, thus we merge, not replace the entire section config.
	if ( class_exists( 'Customify_Array' ) && method_exists( 'Customify_Array', 'array_merge_recursive_distinct' ) ) {
		$options['sections']['style_manager_section'] = Customify_Array::array_merge_recursive_distinct( $options['sections']['style_manager_section'], $new_config );
	} else {
		$options['sections']['style_manager_section'] = array_merge_recursive( $options['sections']['style_manager_section'], $new_config );
	}

	return $options;
}

/**
 * Add extra controls in the Customizer
 *
 * @package jason
 */

function jasonlite_fill_customify_options( $options )  {
	$new_config = array(
		'colors_section'    => array(
			'title' => '',
			'type'  => 'hidden',
			'options'   => array(
				'primary_color'              => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Primary Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_COLOR_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.site-title .alt,
								h1,
								h2,
								h3,
								blockquote,
								.dropcap,
								.single .entry-content:before,
								.page .entry-content:before,
								.color--primary,
								.entry-title,
								.archive-title,
								.entry-content h1,
								.comment__content h1,
								.entry-content ul li:before,
								.entry-content ol li:before,
								ul.primary-menu > li[class*="current-menu"] > a,
								.primary-menu > ul > li[class*="current-menu"] > a,
								.entry-title a,
								.search-overlay-content.search-field,
								.comment__content ol li:before,
								.entry-content ol li:before',
						),
						array(
							'property' => 'background-color',
							'selector' => '
								.intro,
								.autostyle-intro .entry-content > p:first-child',
						),
						array(
							'property' => 'color',
							'selector' => '.search-overlay-content .search-field::-webkit-input-placeholder',
						),
						array(
							'property' => 'color',
							'selector' => '.search-overlay-content .search-field:-moz-placeholder',
						),
						array(
							'property' => 'color',
							'selector' => '.search-overlay-content .search-field::-moz-placeholder',
						),
						array(
							'property' => 'color',
							'selector' => '.search-overlay-content .search-field:-ms-input-placeholder',
						),
					),
				),
				'secondary_color'            => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Secondary Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_COLOR_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.color--secondary,
								.entry-content h2,
								.comment__content h2,
								.entry-content h3,
								.comment__content h3,
								#jp-relatedposts h3.jp-relatedposts-headline,
								.entry-content h4,
								.comment__content h4,
								.entry-content blockquote cite,
								.comment__content blockquote cite,
								.taxonomy-description,
								.entry-content h5,
								.comment__content h5,
								.widget .widgets-list-layout
								.cat-links,
								.widget_recent_comments .recentcomments span a,
								.widget_rss li
								.rsswidget,
								.entry-content blockquote,
								.comment__content blockquote,
								.comments_add-comment,
								.comment__author-name,
								.comment-edit-link:hover,
								.current_page_item,
								.comment-reply-link:hover,
								#cancel-comment-reply-link:hover,
								.widget_text blockquote,
								.widget_archive > ul > li,
								.widget_categories > ul > li,
								.widget_meta > ul > li,
								.entry-meta [class*="-link"],
								.entry-content p a,
								.comment__content p a,
								.page-links a,
								.widget ul .cat-links,
								.widget_recent_comments .recentcomments .comment-author-link,
								.widget_rss li .rsswidget,
								.dropcap,
								.site-footer a[rel="designer"],
								p.comment-likes[data-liked] span.comment-like-feedback:hover,
								.site-footer a:hover,
								.widget-area h1:not(.widget-title),
								.widget-area h2:not(.widget-title),
								.widget-area h3:not(.widget-title),
								.widget-area h4:not(.widget-title),
								.widget-area h5:not(.widget-title),
								.widget-area h6:not(.widget-title),
								.widget_archive a:hover,
								.widget_archive a:focus,
								.widget_categories a:hover,
								.widget_categories a:focus,
								.widget_meta a:hover,
								.widget_meta a:focus,
								.widget_nav_menu a:hover,
								.widget_nav_menu a:focus,
								.tags-links a:hover,
								.tags-links a:focus,
								.entry-title em,
								.archive-title em,
								.entry-content h1 em,
								.comment__content h1 em,
								.intro, 
								.autostyle-intro .entry-content > p:first-child',
						),
						array(
							'property' => 'border-color',
							'selector' => '.widget_blog_subscription input[type="submit"], .widget_text blockquote:before',
						),
						array(
							'property' => 'background-color',
							'selector' => '
								.btn, 
								input[type="submit"], 
								input[type="reset"], 
								div#infinite-handle span,
								.nav-previous, 
								.nav-next, 
								.more-link',
						),
						array(
							'property'        => 'color',
							'selector'        => '.content-quote blockquote:after',
							'callback_filter' => 'accent_color_box_shadow'
						),
						array(
							'property'        => 'border-color',
							'selector'        => '.bypostauthor .comment__avatar img',
						),
						array(
							'property'        => 'border-color',
							'selector'        => '.widget_text blockquote',
						),
						array(
							'property'        => 'background-color',
							'selector'        => '.widget_text blockquote:after',
						),
					)
				),
				'primary_site_title_color'   => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Site Title Primary Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.site-title:not(.alt)',
						)
					)
				),
				'secondary_site_title_color' => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Site Title Secondary Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_COLOR_TERTIARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.site-title .alt',
						)
					)
				),
				'body_color'                 => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Body Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								html,
								.color--neutral, .entry-content h6,
								.comment__content h6,
								.primary-menu,
								.tags-links,
								div.sd-social.sd-social > div.sd-content.sd-content ul li > a,
								.widget[class*="cloud"] a,
								.widget_pages a,
								.entry-meta,
								.comment-edit-link,
								.comment-reply-link,
								#cancel-comment-reply-link,
								.widget-title,
								p.comment-likes[data-liked] span.comment-like-feedback,
								div.sharedaddy h3.sd-title,
								#jp-relatedposts h3.jp-relatedposts-headline,
								.textwidget,
								.widget_rss,
								.rss-date,
								.widget_rss cite,
								.site-branding--style-style1 .site-description,
								.main-navigation button,

								.social-menu .menu-item > a:hover,
								.social-menu .menu-item > a:focus,

								.search-toggle:hover,
								.search-overlay-content .assistive-text,
								.contact-form[class] label span,
								.site-footer',
						),
						array(
							'property' => 'background-color',
							'selector' => '
								.site-branding--style-style1 .site-description:before,
								.entry-content pre:before,
								.comment__content pre:before',
						),
						array(
							'property' => 'border-color',
							'selector' => '
								div.sd-social.sd-social > div.sd-content.sd-content ul li > a:hover,
								.widget[class*="cloud"] a:hover,
								.widget_pages a:hover,
								.entry-content pre,
								.comment__content pre',
						),
						array(
							'property'        => 'border-color',
							'selector'        => '
								div.sd-social.sd-social > div.sd-content.sd-content ul li > a,
								.main-navigation,
								.sharing-hidden .inner,

								.widget[class*="cloud"] a,
								.widget_pages a,
								.menu-toggle,
								.sidebar-open',
							'callback_filter' => 'jasonlite_transparent_color',
							'unit'            => '30',
						),
						array(
							'media'           => 'not screen and (min-width: 900px) ',
							'property'        => 'border-color',
							'selector'        => '
								ul.primary-menu > li, 
								.primary-menu > ul > li,
								ul.primary-menu .sub-menu li:after, 
								ul.primary-menu .children li:after, 
								.primary-menu > ul .sub-menu li:after, 
								.primary-menu > ul .children li:after,
								.primary-menu .menu-item.hover > a:before, 
								.primary-menu .page_item.hover > a:before',
							'callback_filter' => 'jasonlite_transparent_color',
							'unit'            => '30',
						),
						array(
							'media'           => 'not screen and (min-width: 900px)',
							'property'        => 'color',
							'selector'        => '
								ul.primary-menu .sub-menu a:before, 
								ul.primary-menu .children a:before, 
								.primary-menu > ul .sub-menu a:before, 
								.primary-menu > ul .children a:before',
						),
						array(
							'property'        => 'border-color',
							'selector'        => '
								.widget_archive ul li,
								.widget_categories ul li,
								.widget_meta ul li,
								.widget_nav_menu ul li,
								.widget_archive ul li li:first-child,
								.widget_categories ul li li:first-child,
								.widget_meta ul li li:first-child,
								.widget_nav_menu ul li li:first-child',
							'callback_filter' => 'jasonlite_transparent_color',
							'unit'            => '50',
						),
						array(
							'property'        => 'border-bottom-color',
							'selector'        => '.sharing-hidden .inner:before',
							'callback_filter' => 'jasonlite_transparent_color',
							'unit'            => '30',
						),
						array(
							'property'        => 'background-color',
							'selector'        => '.highlight',
							'callback_filter' => 'jasonlite_transparent_color',
							'unit'            => '30',
						),
					)
				),
				'background_color'           => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Background Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => '--sm-light-primary',
							'selector' => ':root',
						),
						array(
							'property' => 'background-color',
							'selector' => '
								html,
								body,
								.site-branding--style-style1 .site-description
								.site-description-text,
								.site-branding .site-description .site-description-text,
								.main-navigation,
								.sharing-hidden .inner,

								.widget_archive ul li:before,
								.widget_categories ul li:before,
								.widget_meta ul li:before,
								.widget_nav_menu ul li:before,

								.search-overlay,

								select,
								textarea,
								input[type="text"],
								input[type="password"],
								input[type="datetime"],
								input[type="datetime-local"],
								input[type="date"],
								input[type="month"],
								input[type="time"],
								input[type="week"],
								input[type="number"],
								input[type="email"],
								input[type="url"],
								input[type="search"],
								input[type="tel"],
								input[type="color"],

								.widget_text blockquote p,
								.widget_text blockquote:before',
						),
						array(
							'media' => 'not screen and (min-width: 900px)',
							'property' => 'background-color',
							'selector' => '
								.widget-area,
								ul.primary-menu, 
								.primary-menu > ul'
						),
						array(
							'property' => 'border-bottom-color',
							'selector' => '.sharing-hidden .inner:after',
						),
						array(
							'property' => 'color',
							'selector' => '
								.site-branding--style-style1 .site-description:before,
								.entry-content pre:before,
								.comment__content pre:before,

								.btn,
								input[type="submit"],
								input[type="reset"],
								div#infinite-handle span,
								.nav-previous,
								.nav-next,
								.more-link',
						),
					)
				),
				'tertiary_dark_color'        => array(
					'type'  => 'hidden_control',
					'label'   => esc_html__( 'Tertiary Dark Color', 'jason' ),
					'live'    => true,
					'default' => JASONLITE_SM_DARK_TERTIARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								div.sd-social.sd-social .sd-content ul li:hover,
								div.sd-social.sd-social .sd-content ul li:active,
								div.sd-social.sd-social .sd-content ul li:focus,

								.color--dark,
								.social-menu a,
								.comment-reply-title,
								.widget_recent_entries,
								.widget_recent_comments,
								.widget_archive ul a,
								.widget_categories ul a,
								.widget_meta ul a,
								.widget_nav_menu ul a,

								.widget ul,

								.primary-menu .menu-item:hover > a,
								.primary-menu .page_item:hover > a,
								.primary-menu .menu-item > a:focus,
								.primary-menu .page_item > a:focus,

								.comments-title,
								.search-overlay-content .search-field,

								.widget_pages a:hover,
								.widget_pages a:active,
								.widget_pages a:focus,
								.widget[class*="cloud"] a:hover,
								.widget[class*="cloud"] a:active,
								.widget[class*="cloud"] a:focus',
						),
						array(
							'media'    => 'not screen and (min-width: 900px)',
							'property' => 'color',
							'selector' => '
								ul.primary-menu, 
								.primary-menu > ul',
						),
					)
				)
			)
		)
	);

	if ( class_exists( 'Customify_Array' ) && method_exists( 'Customify_Array', 'array_merge_recursive_distinct' ) ) {
		$options['sections'] = Customify_Array::array_merge_recursive_distinct( $options['sections'], $new_config );
	} else {
		$options['sections'] = array_merge_recursive( $options['sections'], $new_config );
	}

	return $options;
}

function jasonlite_transparent_color( $value, $selector, $property, $unit ) {
	if ( empty( $unit ) ) {
		$unit = '30';
	}

	$output = $selector . ' {' .
	          $property . ': ' . $value . $unit . ';' .
	          '}';

	return $output;
}

function jasonlite_transparent_color_customizer_preview() {

	$js = "

    function makeSafeForCSS(name) {
        return name.replace(/[^a-z0-9]/g, function(s) {
            var c = s.charCodeAt(0);
            if (c == 32) return '-';
            if (c >= 65 && c <= 90) return '_' + s.toLowerCase();
            return '__' + ('000' + c.toString(16)).slice(-4);
        });
    }

    String.prototype.hashCode = function() {
        var hash = 0, i, chr;

        if ( this.length === 0 ) return hash;

        for (i = 0; i < this.length; i++) {
            chr   = this.charCodeAt(i);
            hash  = ((hash << 5) - hash) + chr;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    };

	function jasonlite_transparent_color( value, selector, property, unit ) {
	    var css = '',
	        id = 'jasonlite_transparent_color_style_tag_' + makeSafeForCSS( property + selector ).hashCode(),
	        style = document.getElementById( id ),
	        head = document.head || document.getElementsByTagName('head')[0];

	    if ( typeof unit !== 'string' ) {
	        unit = '30';
	    }

	    css += selector + ' {' + property + ': ' + value.substring(0,7) + unit + ';}';

	    if ( style !== null ) {
	        style.innerHTML = css;
	    } else {
	        style = document.createElement('style');
	        style.setAttribute('id', id);

	        style.type = 'text/css';
	        if ( style.styleSheet ) {
	            style.styleSheet.cssText = css;
	        } else {
	            style.appendChild(document.createTextNode(css));
	        }

	        head.appendChild(style);
	    }
	}" . PHP_EOL;

	wp_add_inline_script( 'customify-previewer-scripts', $js );
}
add_action( 'customize_preview_init', 'jasonlite_transparent_color_customizer_preview', 20 );

function jasonlite_add_default_color_palette( $color_palettes ) {

	$color_palettes = array_merge( array(
		'default' => array(
			'label'   => 'Theme Default',
			'preview' => array(
				'background_image_url' => 'https://cloud.pixelgrade.com/wp-content/uploads/2018/07/jason-palette.jpg',
			),
			'options' => array(
				'sm_color_primary'   => JASONLITE_SM_COLOR_PRIMARY,
				'sm_color_secondary' => JASONLITE_SM_COLOR_SECONDARY,
				'sm_color_tertiary'  => JASONLITE_SM_COLOR_TERTIARY,
				'sm_dark_primary'    => JASONLITE_SM_DARK_PRIMARY,
				'sm_dark_secondary'  => JASONLITE_SM_DARK_SECONDARY,
				'sm_dark_tertiary'   => JASONLITE_SM_DARK_TERTIARY,
				'sm_light_primary'   => JASONLITE_SM_LIGHT_PRIMARY,
				'sm_light_secondary' => JASONLITE_SM_LIGHT_SECONDARY,
				'sm_light_tertiary'  => JASONLITE_SM_LIGHT_TERTIARY,
			),
		),
	), $color_palettes );

	return $color_palettes;
}
add_filter( 'customify_get_color_palettes', 'jasonlite_add_default_color_palette' );
