<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

$maybe_custom_output = apply_filters(
	'blocksy:posts-listing:canvas:custom-output',
	null
);

if ($maybe_custom_output) {
	echo $maybe_custom_output;
	return;
}

$container_class = 'ct-container';


/**
 * Note to code reviewers: This line doesn't need to be escaped.
 * Function blocksy_output_hero_section() used here escapes the value properly.
 */
echo blocksy_output_hero_section([
	'type' => 'type-2'
]);

$section_class = '';

if (! have_posts()) {
	$section_class = 'class="ct-no-results"';
}

?>
<div class="wp-block-cover contact-area page-tit_area w-100 pt-0" style="min-height:184px;aspect-ratio:unset; background-color:#103674;">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:#103674"></span>
	<div class="wp-block-cover__inner-container is-layout-constrained wp-block-cover-is-layout-constrained">
	<h2 class="wp-block-heading new-page-tit pb-0 fs-1 has-palette-color-8-color has-text-color has-link-color has-x-large-font-size wp-elements-2c5e546a87f0951a966487833a5d5bc1" style="margin-right:0;margin-left:0;padding-top:0;padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--60);line-height:1.8"><?php echo get_the_archive_title(); ?></h2>
	</div>
</div>

<div class="<?php echo $container_class ?> news-content" <?php echo wp_kses_post(blocksy_sidebar_position_attr()); ?> <?php echo blocksy_get_v_spacing() ?>>
	<section <?php echo $section_class ?>>
		<?php
			// Heroセクションは現状のまま
			echo blocksy_output_hero_section([
				'type' => 'type-1'
			]);
		?>

		<?php if ( have_posts() ) : ?>
			<div class="row g-4">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
						// 共通カードテンプレート（CFSのカテゴリ色・白文字バッジ＋日付＋タイトル）
						get_template_part( 'template-parts/card', 'post' );
					?>
				<?php endwhile; ?>
			</div>

			<div class="mt-4">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p class="my-5 mb-0"><?php esc_html_e( 'No posts found.', 'your-textdomain' ); ?></p>
		<?php endif; ?>
		<?php if ( ! ( is_home() && ! is_front_page() ) ) : ?>
		<div class="link-area text-center mt-5">
			<a href="/news/" class="rounded-pill">新着情報トップへ<i class="ps-2 fa-regular fa-circle-right"></i></a>
		</div>
		<?php endif; ?>
	</section>

	<?php get_sidebar(); ?>
</div>
