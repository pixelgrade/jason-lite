<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Jason
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'jason-lite' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="overlay-toggle  menu-toggle  menu-open" aria-controls="primary-menu" aria-expanded="false">
                <?php get_template_part( 'assets/icons/menu-bars-svg' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'jason-lite' ); ?></span>
			</button>
			<button class="overlay-toggle  menu-toggle  menu-close">
				<?php get_template_part( 'assets/icons/close-icon-svg' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close Primary Menu', 'jason-lite' ); ?></span>
			</button>

			<button class="overlay-toggle  sidebar-toggle  sidebar-open">
				<?php get_template_part( 'assets/icons/sidebar-icon-svg' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Sidebar', 'jason-lite' ); ?></span>
			</button>
			<button class="overlay-toggle  right-close-button">
				<?php get_template_part( 'assets/icons/close-icon-svg' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close Sidebar', 'jason-lite' ); ?></span>
			</button>

			<div class="overlay-shadow"></div>

			<?php get_template_part( 'search', 'overlay' ); ?>

			<?php wp_nav_menu( array(
				'depth'     => -1,
				'container' => false,
				'theme_location' => 'social',
				'menu_class' => 'social-menu',
				'fallback_cb' => false,
				'link_before' => '<span class="icon-text">',
				'link_after'  => '</span>'
				) ); ?>
			<?php wp_nav_menu( array(
				'container' => false,
				'theme_location' => 'primary',
				'menu_class' => 'primary-menu',
				) ); ?>
		</nav><!-- #site-navigation -->

        <?php
        $style_class = esc_attr( get_theme_mod( 'jason_site_title_styling', 'site-branding--style-style1' ) );
        $size_class = esc_attr( get_theme_mod( 'jason_title_size', 'site-branding--size-medium' ) );
        ?>
		<div <?php jason_site_title_classes( array( 'site-branding', $style_class, $size_class ) ); ?>>
			<?php if ( function_exists( 'jetpack_the_site_logo' ) ) {
				jetpack_the_site_logo();
			} ?>

			<?php if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php jason_site_title(); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php jason_site_title(); ?></a></p>
			<?php endif; ?>
			<p class="site-description"><span class="site-description-text"><?php bloginfo( 'description' ); ?></span></p>
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
