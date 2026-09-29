<?php
/**
 * Catalog management and catalog page rendering.
 *
 * @package Blocksy
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Register catalog entries for editing in the WordPress dashboard.
 */
function heiankenzai_register_catalog_post_type() {
	register_post_type('catalog_item', [
		'labels' => [
			'name'               => 'カタログ',
			'singular_name'      => 'カタログ',
			'menu_name'          => 'カタログ',
			'add_new'            => '新規追加',
			'add_new_item'       => 'カタログを追加',
			'edit_item'          => 'カタログを編集',
			'new_item'           => '新しいカタログ',
			'view_item'          => 'カタログを表示',
			'search_items'       => 'カタログを検索',
			'not_found'          => 'カタログが見つかりませんでした',
			'not_found_in_trash' => 'ゴミ箱にカタログはありません',
		],
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'menu_icon'           => 'dashicons-media-document',
		'supports'            => ['title', 'editor', 'thumbnail', 'page-attributes', 'revisions'],
		'map_meta_cap'        => true,
		'capability_type'     => 'post',
	]);
}
add_action('init', 'heiankenzai_register_catalog_post_type');

/**
 * Add catalog-specific fields without requiring an additional plugin.
 */
function heiankenzai_add_catalog_meta_box() {
	add_meta_box(
		'heiankenzai_catalog_details',
		'カタログ情報',
		'heiankenzai_render_catalog_meta_box',
		'catalog_item',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes_catalog_item', 'heiankenzai_add_catalog_meta_box');

/**
 * Render catalog fields in the editor.
 *
 * @param WP_Post $post Current post.
 */
function heiankenzai_render_catalog_meta_box($post) {
	wp_nonce_field('heiankenzai_save_catalog_details', 'heiankenzai_catalog_nonce');

	$subtitle    = get_post_meta($post->ID, '_catalog_subtitle', true);
	$link_url    = get_post_meta($post->ID, '_catalog_link_url', true);
	$button_text = get_post_meta($post->ID, '_catalog_button_text', true);
	$new_window  = get_post_meta($post->ID, '_catalog_new_window', true);
	?>
	<p>
		<label for="catalog_subtitle"><strong>補足テキスト</strong></label><br>
		<input class="widefat" type="text" id="catalog_subtitle" name="catalog_subtitle" value="<?php echo esc_attr($subtitle); ?>" placeholder="例：LIFE SELECT VOL.18">
	</p>
	<p>
		<label for="catalog_link_url"><strong>カタログのリンク先</strong></label><br>
		<input class="widefat" type="url" id="catalog_link_url" name="catalog_link_url" value="<?php echo esc_attr($link_url); ?>" placeholder="https://example.com/catalog.pdf">
	</p>
	<p>
		<label for="catalog_button_text"><strong>ボタンの文言</strong></label><br>
		<input class="widefat" type="text" id="catalog_button_text" name="catalog_button_text" value="<?php echo esc_attr($button_text); ?>" placeholder="カタログを見る">
	</p>
	<p>
		<label>
			<input type="checkbox" name="catalog_new_window" value="1" <?php checked($new_window, '1'); ?>>
			リンクを新しいタブで開く
		</label>
	</p>
	<p class="description">タイトル、本文（説明文）、アイキャッチ画像、並び順は画面内の各項目から変更できます。</p>
	<?php
}

/**
 * Save catalog-specific fields.
 *
 * @param int $post_id Current post ID.
 */
function heiankenzai_save_catalog_details($post_id) {
	if (
		! isset($_POST['heiankenzai_catalog_nonce'])
		|| ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['heiankenzai_catalog_nonce'])), 'heiankenzai_save_catalog_details')
		|| (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		|| ! current_user_can('edit_post', $post_id)
	) {
		return;
	}

	$subtitle    = isset($_POST['catalog_subtitle']) ? sanitize_text_field(wp_unslash($_POST['catalog_subtitle'])) : '';
	$link_url    = isset($_POST['catalog_link_url']) ? esc_url_raw(wp_unslash($_POST['catalog_link_url'])) : '';
	$button_text = isset($_POST['catalog_button_text']) ? sanitize_text_field(wp_unslash($_POST['catalog_button_text'])) : '';
	$new_window  = isset($_POST['catalog_new_window']) ? '1' : '0';

	update_post_meta($post_id, '_catalog_subtitle', $subtitle);
	update_post_meta($post_id, '_catalog_link_url', $link_url);
	update_post_meta($post_id, '_catalog_button_text', $button_text);
	update_post_meta($post_id, '_catalog_new_window', $new_window);
}
add_action('save_post_catalog_item', 'heiankenzai_save_catalog_details');

/**
 * Improve the catalog list in the dashboard.
 */
function heiankenzai_catalog_columns($columns) {
	return [
		'cb'            => $columns['cb'],
		'catalog_cover' => 'サムネイル',
		'title'         => 'カタログ名',
		'catalog_link'  => 'リンク先',
		'menu_order'    => '並び順',
		'date'          => $columns['date'],
	];
}
add_filter('manage_catalog_item_posts_columns', 'heiankenzai_catalog_columns');

function heiankenzai_catalog_column_content($column, $post_id) {
	if ('catalog_cover' === $column) {
		echo get_the_post_thumbnail($post_id, [72, 96], ['style' => 'width:54px;height:72px;object-fit:cover;']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ('catalog_link' === $column) {
		$url = get_post_meta($post_id, '_catalog_link_url', true);
		if ($url) {
			echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">リンクを確認</a>';
		} else {
			echo '未設定';
		}
	}

	if ('menu_order' === $column) {
		echo esc_html((string) get_post_field('menu_order', $post_id));
	}
}
add_action('manage_catalog_item_posts_custom_column', 'heiankenzai_catalog_column_content', 10, 2);

/**
 * Seed the catalog currently shown on the live site once.
 */
function heiankenzai_seed_catalog_item() {
	if (get_option('heiankenzai_catalog_seed_version') || wp_count_posts('catalog_item')->publish > 0) {
		return;
	}

	$post_id = wp_insert_post([
		'post_type'    => 'catalog_item',
		'post_status'  => 'publish',
		'post_title'   => 'セレクトブランカタログ',
		'post_content' => '住宅設備・建材を掲載した製品カタログです。',
		'menu_order'   => 1,
	]);

	if (is_wp_error($post_id) || ! $post_id) {
		return;
	}

	update_post_meta($post_id, '_catalog_subtitle', 'LIFE SELECT VOL.18');
	update_post_meta($post_id, '_catalog_link_url', content_url('/uploads/2026/03/selectvol18.pdf'));
	update_post_meta($post_id, '_catalog_button_text', 'カタログを見る');
	update_post_meta($post_id, '_catalog_new_window', '1');

	$attachments = get_posts([
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_attached_file',
		'meta_value'     => '2026/03/selectvol18_th.jpg',
		'fields'         => 'ids',
	]);

	if ($attachments) {
		set_post_thumbnail($post_id, (int) $attachments[0]);
	}

	update_option('heiankenzai_catalog_seed_version', '1', false);
}
add_action('init', 'heiankenzai_seed_catalog_item', 30);

/**
 * Load catalog styles only where they are needed.
 */
function heiankenzai_enqueue_catalog_styles() {
	if (! is_page('catalog')) {
		return;
	}

	$path = get_template_directory() . '/assets/css/catalog.css';
	wp_enqueue_style(
		'heiankenzai-catalog',
		get_template_directory_uri() . '/assets/css/catalog.css',
		[],
		file_exists($path) ? (string) filemtime($path) : wp_get_theme()->get('Version')
	);
}
add_action('wp_enqueue_scripts', 'heiankenzai_enqueue_catalog_styles', 20);

/**
 * Replace the old catalog page blocks with the managed catalog list.
 *
 * @param string $content Original page content.
 * @return string
 */
function heiankenzai_catalog_page_content($content) {
	if (! is_page('catalog') || ! in_the_loop() || ! is_main_query()) {
		return $content;
	}

	$catalogs = new WP_Query([
		'post_type'      => 'catalog_item',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
		'no_found_rows'  => true,
	]);

	ob_start();
	?>
	<section class="hk-catalog" aria-labelledby="hk-catalog-heading">
		<header class="hk-catalog__header">
			<p class="hk-catalog__eyebrow">CATALOG</p>
			<h2 id="hk-catalog-heading">製品カタログ</h2>
			<p>各カタログの画像またはボタンから内容をご覧いただけます。</p>
		</header>

		<?php if ($catalogs->have_posts()) : ?>
			<div class="hk-catalog__list">
				<?php while ($catalogs->have_posts()) : $catalogs->the_post(); ?>
					<?php
					$item_id      = get_the_ID();
					$subtitle     = get_post_meta($item_id, '_catalog_subtitle', true);
					$link_url     = get_post_meta($item_id, '_catalog_link_url', true);
					$button_text  = get_post_meta($item_id, '_catalog_button_text', true) ?: 'カタログを見る';
					$new_window   = '1' === get_post_meta($item_id, '_catalog_new_window', true);
					$target       = $new_window ? ' target="_blank" rel="noopener noreferrer"' : '';
					$fallback_url = get_template_directory_uri() . '/assets/catalog/selectvol18_th.jpg';
					$image_html   = get_the_post_thumbnail($item_id, 'medium_large', [
						'alt'     => get_the_title(),
						'loading' => 'lazy',
					]);
					?>
					<article class="hk-catalog-card">
						<div class="hk-catalog-card__visual">
							<?php if ($link_url) : ?>
								<a href="<?php echo esc_url($link_url); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php echo esc_attr(get_the_title() . 'を開く'); ?>">
							<?php endif; ?>
								<?php
								if ($image_html) {
									echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									echo '<img src="' . esc_url($fallback_url) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
								}
								?>
							<?php if ($link_url) : ?></a><?php endif; ?>
						</div>

						<div class="hk-catalog-card__body">
							<?php if ($subtitle) : ?><p class="hk-catalog-card__subtitle"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
							<h3><?php the_title(); ?></h3>
							<div class="hk-catalog-card__description"><?php echo wp_kses_post(wpautop(get_the_content())); ?></div>
						</div>

						<div class="hk-catalog-card__action">
							<?php if ($link_url) : ?>
								<a class="hk-catalog-card__button" href="<?php echo esc_url($link_url); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<span><?php echo esc_html($button_text); ?></span>
									<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="hk-catalog__empty">現在ご案内できるカタログはありません。</p>
		<?php endif; ?>
	</section>
	<?php
	wp_reset_postdata();

	return (string) ob_get_clean();
}
add_filter('the_content', 'heiankenzai_catalog_page_content', 20);

/**
 * Add the company brochure as the fourth child of the Company menu.
 */
function heiankenzai_add_company_brochure_menu_item() {
	if (get_option('heiankenzai_company_brochure_menu_version') === '2') {
		return;
	}

	$menus   = wp_get_nav_menus();
	$updated = false;

	foreach ($menus as $menu) {
		if ('ヘッダーメニュー' !== trim(wp_strip_all_tags($menu->name))) {
			continue;
		}

		$items = wp_get_nav_menu_items($menu->term_id, ['post_status' => 'publish']);
		if (! $items) {
			continue;
		}

		foreach ($items as $parent) {
			if ('企業情報' !== trim(wp_strip_all_tags($parent->title))) {
				continue;
			}

			$children = array_values(array_filter($items, function ($item) use ($parent) {
				return (int) $item->menu_item_parent === (int) $parent->ID;
			}));

			$brochure_items = array_values(array_filter($children, function ($item) {
				return '会社パンフレット' === trim(wp_strip_all_tags($item->title));
			}));
			$brochure_item  = $brochure_items ? array_shift($brochure_items) : null;

			// Keep only one copy if two requests reached the initial migration at once.
			foreach ($brochure_items as $duplicate) {
				wp_delete_post($duplicate->ID, true);
			}

			$brochure_item_id = wp_update_nav_menu_item($menu->term_id, $brochure_item ? $brochure_item->ID : 0, [
				'menu-item-title'     => '会社パンフレット',
				'menu-item-url'       => get_template_directory_uri() . '/assets/catalog/company-pamphlet_2025.pdf',
				'menu-item-parent-id' => $parent->ID,
				'menu-item-target'    => '_blank',
				'menu-item-status'    => 'publish',
			]);

			if (is_wp_error($brochure_item_id)) {
				continue;
			}

			$items = wp_get_nav_menu_items($menu->term_id, ['post_status' => 'publish']);
			$brochure_item = null;
			$ordered        = [];

			foreach ($items as $item) {
				if ((int) $item->ID === (int) $brochure_item_id) {
					$brochure_item = $item;
					continue;
				}
				$ordered[] = $item;
			}

			if ($brochure_item) {
				$regular_children = array_values(array_filter($ordered, function ($item) use ($parent) {
					return (int) $item->menu_item_parent === (int) $parent->ID;
				}));
				$anchor_index = min(2, max(0, count($regular_children) - 1));
				$anchor_id    = $regular_children ? $regular_children[$anchor_index]->ID : $parent->ID;
				$position  = count($ordered);

				foreach ($ordered as $index => $item) {
					if ((int) $item->ID === (int) $anchor_id) {
						$position = $index + 1;
						break;
					}
				}

				array_splice($ordered, $position, 0, [$brochure_item]);
				foreach ($ordered as $index => $item) {
					wp_update_post([
						'ID'         => $item->ID,
						'menu_order' => $index + 1,
					]);
				}
			}

			$updated = true;
			break 2;
		}
	}

	if ($updated) {
		update_option('heiankenzai_company_brochure_menu_version', '2', false);
	}
}
add_action('init', 'heiankenzai_add_company_brochure_menu_item', 40);
