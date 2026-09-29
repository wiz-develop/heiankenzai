<?php
/**
 * Template Part: Latest News Ticker (1 item view, slide-up when multiple)
 * Place this file under: template-parts/latest-news-ticker.php
 * Call via shortcode or template: get_template_part('template-parts/latest-news-ticker');
 */

if ( ! defined('ABSPATH') ) exit;

// ====== 設定 ======
$post_type   = 'post';
$max_posts   = 5;         // 表示最大件数
$fresh_days  = 7;         // 何日以内を「新着」とみなすか
$fresh_by    = 'date';    // 'date' = 公開日 / 'modified' = 更新日
$interval_ms = 4000;      // 切替間隔
$duration_ms = 500;       // スライド時間

// まず最新1件の“鮮度”を確認（古ければ全体を出さない）
$latest = new WP_Query([
  'post_type'           => $post_type,
  'posts_per_page'      => 1,
  'ignore_sticky_posts' => 1,
  'no_found_rows'       => true,
]);
if ( ! $latest->have_posts() ) return;

$latest->the_post();

// 公開日 or 更新日のタイムスタンプを取得
if ( $fresh_by === 'modified' ) {
  $latest_ts = get_post_modified_time( 'U', true ); // 更新日（GMT基準）
} else {
  $latest_ts = get_post_time( 'U', true );          // 公開日（GMT基準）
}
wp_reset_postdata();

$limit_ts = current_time('timestamp', true) - ( DAY_IN_SECONDS * $fresh_days ); // 基準もGMTに合わせる
if ( $latest_ts < $limit_ts ) return; // 期限切れ → 何も出力しない

// -------- 最新が新鮮なら、複数件を取得してティッカーを出す --------
$q = new WP_Query([
  'post_type'           => $post_type,
  'posts_per_page'      => $max_posts,
  'ignore_sticky_posts' => 1
]);

$items = [];
if ( $q->have_posts() ) :
  while ( $q->have_posts() ) : $q->the_post();
    $post_id = get_the_ID();
    $cats    = get_the_category( $post_id );
    $cat     = $cats ? $cats[0] : null;

    // カテゴリ背景色（ACF例：'cat_bg'）
    $cat_bg = '#6c757d';
    if ( $cat ) {
      $v = get_field('cat_bg', 'term_' . $cat->term_id);
      if ( $v ) $cat_bg = $v;
    }

    $items[] = [
      'title'  => get_the_title( $post_id ),
      'link'   => get_permalink( $post_id ),
      'cat'    => $cat ? $cat->name : 'NEWS',
      'cat_bg' => $cat_bg,
      'date'   => get_the_date('Y.m.d', $post_id)
    ];
  endwhile;
  wp_reset_postdata();
endif;

if ( empty($items) ) return;

$uid = 'ln-' . wp_generate_uuid4();
?>

<div id="<?php echo esc_attr($uid); ?>" class="latest-news-ticker">
  <div class="ln-row">
    <?php
      // 左バッジは 1 件目のカテゴリ色で表示（要件に合わせて固定「NEWS」にしてもOK）
      $badge_bg = $items[0]['cat_bg'];
      $badge_tx = '#ffffff';
    ?>
    <div class="latest-news__feed-tit">
        <span class="d-block en-tit">NEWS</span>
    </div>

    <div class="ln-feed" role="region" aria-live="polite">
        <?php foreach ($items as $i => $it) :
            $ty = ($i === 0) ? '0%' : 'calc(100% + 4px)'; 
        ?>
        <div class="ln-item latest-news_item" style="transform: translateY(<?php echo esc_attr($ty); ?>);">
            <a class="ln-title d-flex align-items-center justify-content-between"
                href="<?php echo esc_url( $it['link'] ); ?>">
                <div class="latest-news_about d-flex align-items-center">
                <div class="latest-news__label d-flex align-items-center">
                    <span class="text-muted small"><?php echo esc_html( $it['date'] ); ?></span>
                    <span class="ln-badge"
                        style="background:<?php echo esc_attr( $it['cat_bg'] ); ?>; color:#fff;">
                    <?php echo esc_html( $it['cat'] ); ?>
                    </span>
                </div>
                <?php echo esc_html( $it['title'] ); ?>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
(function(){
  const root   = document.getElementById('<?php echo esc_js($uid); ?>');
  if (!root) return;
  const items  = Array.from(root.querySelectorAll('.ln-item'));
  if (items.length <= 1) return; // 1件のみならスライドしない

  const interval = <?php echo (int) $interval_ms; ?>;
  const dur      = <?php echo (int) $duration_ms; ?>;
  const OFF      = 'calc(100% + 4px)';
  const OFF_UP   = 'calc(-100% - 4px)';
  let   cur      = 0;
  let   timer;

  // 初期高さをアイテム高に合わせる
  const feed = root.querySelector('.ln-feed');
  const itemH = items[0].getBoundingClientRect().height || 36;
  feed.style.height = itemH + 'px';
  items.forEach(el => {
    el.style.transition = 'transform ' + dur + 'ms ease';
  });

  function step(){
    const next = (cur + 1) % items.length;
    // 現在を上へ、次を下→中央へ
    items[cur].style.transform  = 'translateY(-100%)';
    items[next].style.transform = 'translateY(0%)';

    // 終わったら、退避させた要素を最下段に戻す
    setTimeout(() => {
      items[cur].style.transition = 'none';
      items[cur].style.transform  = 'translateY(100%)';
      // リフローしてから遷移復活
      void items[cur].offsetWidth;
      items[cur].style.transition = 'transform ' + dur + 'ms ease';
      cur = next;
    }, dur);
  }

  function play(){ timer = setInterval(step, interval); }
  function stop(){ clearInterval(timer); }

  // ホバーで一時停止（タッチは無視）
  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', play);

  // Reduced motion のときは自動再生しない
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (!reduced.matches) play();
})();
</script>
