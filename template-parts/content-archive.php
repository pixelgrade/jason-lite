<?php
/**
 * Template part for displaying posts on archives and search results
 *
 * @package Jason
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ): ?>

		<div class="article-image">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
		</div>

	<?php endif; ?>

	<div class="article-content">
		<header class="entry-header">
			<div class="entry-meta">
				<?php if ( 'post' == get_post_type() ) : ?>
					<?php jason_archive_posted_on(); ?>
				<?php endif; ?>
			</div><!-- .entry-meta -->

			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>
</article><!-- #post-## -->
