<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Jason
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

	<?php if ( have_posts() ) { ?>

		<header class="page-header">
			<h1 class="page-title">
				<?php the_archive_title(); ?>
			</h1>
		</header><!-- .page-header -->

		<?php the_archive_description( '<div class="entry-content  archive-entry-content  tax-description">', '</div>' );

		get_template_part('template-parts/archive-filters');

		/* Start the Loop */

		while ( have_posts() ) : the_post();

			/*
			 * Include the template for the archive layout
			 * If you want to override this in a child theme, then include a file
			 * called content-archive.php located in the template-parts directory
			 * and that will be used instead.
			 */
			get_template_part( 'template-parts/content-archive' );

		endwhile;

		the_posts_navigation();

	} else {
		get_template_part( 'template-parts/content', 'none' );
	} ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
