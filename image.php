<?php
	/**
	 * The template for displaying image attachments
	 *
	 * @package Jason
	 */

	global $content_width;

	$content_width = 1050; /* pixels */

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 1450; /* pixels */
	}

	get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
				// Start the loop.
				while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'attachment' ); ?>

					<?php jason_the_image_navigation(); ?>

					<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					// The parent post link.
					/* translators: used on the attachment page to link to the parent post */
					the_post_navigation( array(
							/* translators: used on the attachment page to link to the parent post */
							'prev_text' => sprintf( esc_html__( 'Published in %s', 'jason-lite' ), '<span class="post-title">%title</span>' ),
						)
					); ?>

				<?php endwhile; // End the loop. ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>