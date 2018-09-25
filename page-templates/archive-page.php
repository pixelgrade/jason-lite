<?php
/**
 * Template Name: Archive Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Jason
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

			<header class="page-header">
				<?php the_title( '<h1 class="page-title"><span class="archive-title">', '</span></h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content  archive-entry-content">

				<?php the_content(); ?>

			</div><!-- .entry-content -->

			<?php endwhile; ?>

			<?php get_template_part( 'template-parts/archive-filters' ); ?>

			<div class="entry-content  archive-entry-content">

				<?php
					//we will use the setting Settings > Reading > Blog pages show at most
					$posts_per_page = get_option( 'posts_per_page' );

					$args = array(
						'post_type' => array( 'post' ),
						'posts_per_page' => $posts_per_page, //we will show the latest $posts_per_page
					);
					$the_query = new WP_Query( $args );

					if ( $the_query->have_posts() ) :

						while ( $the_query->have_posts() ) : $the_query->the_post();

							/*
							 * Include the template for the archive layout
							 * If you want to override this in a child theme, then include a file
							 * called content-archive.php located in the template-parts directory
							 * and that will be used instead.
							 */
							get_template_part( 'template-parts/content-archive' );

						endwhile;

						wp_reset_postdata(); ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>

			</div><!-- .entry-content -->

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>