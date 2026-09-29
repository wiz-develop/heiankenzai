<?php
/**
 * One-time manufacturer block ordering migration.
 *
 * The migration rearranges complete Gutenberg Column blocks, so each logo,
 * link and image remains a normal editable block in the WordPress editor.
 *
 * @package Blocksy
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Manufacturer identifiers in Japanese syllabary order.
 *
 * @return array
 */
function heiankenzai_manufacturer_order() {
	return [
		'aica.co.jp',             // アイカ工業
		'ikuta.co.jp',            // イクタ
		'woodone.co.jp',          // ウッドワン
		'eidai.com',              // 永大産業
		'cleanup.jp',             // クリナップ
		'kmew.co.jp',             // ケイミュー
		'corona.co.jp',           // コロナ
		'jfe-rockfiber.co.jp',    // JFEロックファイバー
		'j-anshin.co.jp',         // 住宅あんしん保証
		'joto.com',               // 城東テクノ
		'daikin.co.jp',           // ダイキン工業
		'daiken.jp',              // 大建工業
		'takagi.co.jp',           // タカギ
		'takara-standard.co.jp',  // タカラスタンダード
		'jp.toto.com',            // TOTO
		'toclas.co.jp',           // トクラス
		'nankaiplywood.co.jp',    // 南海プライウッド
		'nichiha.co.jp',          // ニチハ
		'noritz.co.jp',           // ノーリツ
		'noda-co.jp',             // ノダ
		'housetec.co.jp',         // ハウステック
		'panasonic.co.jp/phs',    // パナソニック ハウジングソリューションズ
		'paloma.co.jp',           // パロマ
		'fukuvi.co.jp',           // フクビ化学工業
		'isover.co.jp',           // マグ・イゾベール
		'yoshino-gypsum.com',     // 吉野石膏
		'lixil.co.jp',            // LIXIL
		'rinnai.co.jp',           // リンナイ
		'ykkap.co.jp',            // YKK AP
	];
}

/**
 * Resolve a complete manufacturer Column block to its requested position.
 *
 * @param array $column Gutenberg Column block.
 * @param int   $fallback Stable fallback position.
 * @return int
 */
function heiankenzai_manufacturer_rank($column, $fallback) {
	$markup = serialize_block($column);

	foreach (heiankenzai_manufacturer_order() as $rank => $identifier) {
		if (false !== stripos($markup, $identifier)) {
			return $rank;
		}
	}

	return count(heiankenzai_manufacturer_order()) + $fallback;
}

/**
 * Sort the columns inside one desktop or mobile manufacturer group.
 *
 * Row wrappers and their original column counts are preserved.
 *
 * @param array $group Manufacturer Group block, passed by reference.
 * @return bool Whether this group changed.
 */
function heiankenzai_sort_manufacturer_group(&$group) {
	$rows        = [];
	$row_counts  = [];
	$columns     = [];
	$original    = [];

	foreach ($group['innerBlocks'] as $row_index => $row) {
		if ('core/columns' !== $row['blockName'] || empty($row['innerBlocks'])) {
			continue;
		}

		$rows[]                 = $row_index;
		$row_counts[$row_index] = count($row['innerBlocks']);
		foreach ($row['innerBlocks'] as $column) {
			$original[] = serialize_block($column);
			$columns[]  = $column;
		}
	}

	if (count($columns) < 2) {
		return false;
	}

	$sortable = [];
	foreach ($columns as $index => $column) {
		$sortable[] = [
			'block' => $column,
			'rank'  => heiankenzai_manufacturer_rank($column, $index),
			'index' => $index,
		];
	}

	usort($sortable, function ($left, $right) {
		if ($left['rank'] === $right['rank']) {
			return $left['index'] <=> $right['index'];
		}
		return $left['rank'] <=> $right['rank'];
	});

	$sorted_blocks = array_map(function ($item) {
		return $item['block'];
	}, $sortable);
	$sorted_markup = array_map('serialize_block', $sorted_blocks);

	if ($original === $sorted_markup) {
		return false;
	}

	$offset = 0;
	foreach ($rows as $row_index) {
		$count = $row_counts[$row_index];
		$group['innerBlocks'][$row_index]['innerBlocks'] = array_slice($sorted_blocks, $offset, $count);
		$offset += $count;
	}

	return true;
}

/**
 * Walk a Gutenberg tree and sort every PC/mobile manufacturer group.
 *
 * @param array $blocks Gutenberg blocks, passed by reference.
 * @return bool Whether any block changed.
 */
function heiankenzai_sort_manufacturer_blocks(&$blocks) {
	$changed = false;

	foreach ($blocks as &$block) {
		$class_name = isset($block['attrs']['className']) ? (string) $block['attrs']['className'] : '';
		if (false !== strpos(' ' . $class_name . ' ', ' pro_bnr-list ')) {
			$changed = heiankenzai_sort_manufacturer_group($block) || $changed;
		}

		if (! empty($block['innerBlocks'])) {
			$changed = heiankenzai_sort_manufacturer_blocks($block['innerBlocks']) || $changed;
		}
	}
	unset($block);

	return $changed;
}

/**
 * Update the front page and Product page once, retaining Gutenberg markup.
 */
function heiankenzai_migrate_manufacturer_order() {
	$version = '1';
	if (get_option('heiankenzai_manufacturer_order_version') === $version) {
		return;
	}

	$front_page_id = (int) get_option('page_on_front');
	$product_page  = get_page_by_path('product');
	$page_ids      = array_filter([
		$front_page_id,
		$product_page ? (int) $product_page->ID : 0,
	]);

	if (count($page_ids) < 2) {
		return;
	}

	global $wpdb;
	$updated_pages = 0;

	foreach (array_unique($page_ids) as $page_id) {
		$post = get_post($page_id);
		if (! $post || 'page' !== $post->post_type) {
			continue;
		}

		$blocks = parse_blocks($post->post_content);
		if (! heiankenzai_sort_manufacturer_blocks($blocks)) {
			$updated_pages++;
			continue;
		}

		$new_content = serialize_blocks($blocks);
		wp_save_post_revision($page_id);
		$result = $wpdb->update(
			$wpdb->posts,
			[
				'post_content'      => $new_content,
				'post_modified'     => current_time('mysql'),
				'post_modified_gmt' => current_time('mysql', true),
			],
			['ID' => $page_id],
			['%s', '%s', '%s'],
			['%d']
		);

		if (false !== $result) {
			clean_post_cache($page_id);
			$updated_pages++;
		}
	}

	if ($updated_pages === count(array_unique($page_ids))) {
		update_option('heiankenzai_manufacturer_order_version', $version, false);
	}
}
add_action('init', 'heiankenzai_migrate_manufacturer_order', 50);
