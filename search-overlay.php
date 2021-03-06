<?php
/**
 * The template for displaying the search overlay.
 *
 * @package Jason
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<button class="overlay-toggle  search-toggle" aria-controls="search-overlay" aria-expanded="false">
    <span class="screen-reader-text"><?php esc_html_e( 'Search', 'jason-lite' ); ?></span>
    <i class="icon icon-search"></i>
</button>
<div class="search-overlay">
    <div class="search-overlay-content">
        <?php get_search_form( true ); ?>
        <span class="assistive-text">
            <?php esc_html_e( 'Begin typing your search above and press return to search. Press \'Esc\' or X to cancel.', 'jason-lite' ); ?>
        </span>
    </div>
</div>
