<?php
/**
 * Company brochure download section.
 *
 * @package Blocksy
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Load the brochure styles only on the company page.
 */
function heiankenzai_enqueue_company_brochure_styles() {
	if (! is_page('company')) {
		return;
	}

	$path = get_template_directory() . '/assets/css/company-brochure.css';
	wp_enqueue_style(
		'heiankenzai-company-brochure',
		get_template_directory_uri() . '/assets/css/company-brochure.css',
		[],
		file_exists($path) ? (string) filemtime($path) : wp_get_theme()->get('Version')
	);
}
add_action('wp_enqueue_scripts', 'heiankenzai_enqueue_company_brochure_styles', 20);

/**
 * Add the brochure download after the existing company information.
 *
 * @param string $content Company page content.
 * @return string
 */
function heiankenzai_company_brochure_content($content) {
	if (! is_page('company') || ! in_the_loop() || ! is_main_query()) {
		return $content;
	}

	$pdf_url = get_template_directory_uri() . '/assets/catalog/company-pamphlet_2025.pdf#page=1';

	ob_start();
	?>
	<section class="hk-company-brochure" aria-labelledby="hk-company-brochure-heading">
		<div class="hk-company-brochure__copy">
			<p class="hk-company-brochure__label">COMPANY PROFILE</p>
			<h2 id="hk-company-brochure-heading">会社パンフレット</h2>
			<p>平安建材の会社案内資料をPDFでご覧いただけます。</p>
		</div>
		<a class="hk-company-brochure__button" href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener noreferrer">
			<span>会社案内資料ダウンロード</span>
			<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M12 4v11m0 0 4-4m-4 4-4-4M5 19h14"/></svg>
		</a>
	</section>
	<?php

	return $content . (string) ob_get_clean();
}
add_filter('the_content', 'heiankenzai_company_brochure_content', 30);
