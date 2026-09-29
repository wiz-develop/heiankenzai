<?php
/**
 * Reusable Post Card
 * Fields:
 *  - Category badge: background-color from CFS term meta 'bg_color', text is white
 *  - Date (Y.m.d)
 *  - Title
 *
 * Usage: get_template_part('template-parts/card', 'post');
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$post_id = isset($post_id) ? (int) $post_id : get_the_ID();

$show_new = isset($show_new) ? (bool) $show_new : is_front_page(); // フロントのみ表示
$is_new   = ( current_time('timestamp') - get_post_time('U', true, $post_id) ) < ( 7 * DAY_IN_SECONDS );

// メインカテゴリ取得（先頭を採用）
$cats = get_the_category( $post_id );
$cat  = $cats ? $cats[0] : null;

// CFS でカテゴリの色を取得（term_{$term_id} がポイント）
$cat_bg = '';
if ( $cat ) {
    $cat_bg = get_field( 'cat_bg', 'term_' . $cat->term_id );
}
if ( ! $cat_bg ) {
    $cat_bg = '#6c757d'; // fallback
}

// 日付
$date_iso = get_the_date( 'c', $post_id );
$date_out = get_the_date( 'Y.m.d', $post_id );
?>

<article class="single-post">
  <a class="text-decoration-none d-md-block d-lg-flex" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
     <div class="single-post__header d-flex align-items-center gap-2 mb-1 col-12 col-lg-3">
        <?php if ( $show_new && $is_new ) : ?>
          <span class="new-label rounded-0 text-danger">NEW</span>
        <?php endif; ?>
        <time class="text-muted" datetime="<?php echo esc_attr( $date_iso ); ?>">
        <?php echo esc_html( $date_out ); ?>
        </time>
        <?php if ( $cat ) : ?>
        <span class="badge rounded-0 p-2" style="background-color: <?php echo esc_attr( $cat_bg ); ?>; color: #ffffff;">
            <?php echo esc_html( $cat->name ); ?>
        </span>
        <?php endif; ?>
    </div>
    <h3 class="mb-0 col-12 col-lg-9"><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
  </a>
</article>
