<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Jason
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'jason-lite' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'jason-lite' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'jason-lite' ), 'Jason Lite', '<a href="https://pixelgrade.com" rel="designer">PixelGrade</a>' ); ?>
		</div><!-- .site-info -->

		<div class="footer-menu">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => '',
					'menu_class'     => 'nav  nav--footer',
					'items_wrap'     => '<nav><h5 class="screen-reader-text">' . esc_html__( 'Footer Menu', 'jason-lite' ) . '</h5><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
					'depth'          => 1,
				)
			); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>