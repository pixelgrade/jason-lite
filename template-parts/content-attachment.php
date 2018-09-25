<?php
/**
 * The template part for displaying the content in image.php.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Jason
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<div class="entry-meta">
			<?php edit_post_link( esc_html__( 'Edit', 'jason-lite' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-content">

		<div class="entry-attachment">

			<?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?>

			<?php if ( has_excerpt() ) : ?>
				<div class="entry-caption">
					<?php the_excerpt(); ?>
				</div><!-- .entry-caption -->
			<?php endif; ?>

		</div><!-- .entry-attachment -->

		<?php
		the_content();

wp_link_pages(
    array(
    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jason-lite' ),
    'after'  => '</div>',
    ) 
);
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->