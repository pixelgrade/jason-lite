<?php
/**
 * Template part for displaying archives browse by dropdowns.
 *
 * @package Jason
 */
?>
<div class="archive-filters">
	<span class="filter-by-title"><?php ( is_page() && is_page_template( 'page-templates/archive-page.php' ) ) ? esc_html_e( 'See the Latest Posts or Browse by:', 'jason' ) : esc_html_e( 'Browse by:', 'jason' ); ?></span>
	<ul>
		<li>
			<select id="page-filter-by-month" name="archive-dropdown">
				<option value=""><?php esc_html_e( 'MONTH', 'jason-lite' ); ?></option>

				<?php wp_get_archives( array(
					'type'            => 'monthly',
					'format'          => 'option',
					'show_post_count' => '1',
				) ); ?>
			</select>
		</li>
		<li>
			<?php jason_tags_dropdown(); ?>
		</li>
		<li>
			<?php wp_dropdown_categories( array(
				'id' => 'page-filter-by-category',
				'selected' => 0,
				'show_option_none' => esc_html__( 'CATEGORY', 'jason-lite' ),
			) ); ?>

		</li>
	</ul>
</div>