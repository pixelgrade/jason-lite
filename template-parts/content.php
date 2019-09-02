<?php
/**
 * Template part for displaying posts on the home page.
 *
 * @package Jason
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<div class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : ?>
				<?php jasonlite_posted_on(); ?>
			<?php endif; ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
			/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'jason-lite' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jason-lite' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
