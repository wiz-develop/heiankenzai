<?php
// -----------------------------
// カテゴリー動的取得
// -----------------------------
$terms = get_terms([
  'taxonomy'   => 'category',
  'hide_empty' => true,   // 投稿の無いカテゴリを隠す（出したいなら false）
  'orderby'    => 'name',
  'order'      => 'ASC',
]);

$per_page = 5; // ★「全て」を含め各タブの件数

// card-post.php の場所（template-parts 優先）
$card_tpl = locate_template(['template-parts/card-post.php', 'card-post.php']);
?>

<ul class="top-news__tabs nav nav-tabs" role="tablist" aria-label="お知らせカテゴリ">
  <li class="nav-item" role="presentation">
    <button type="button" class="nav-link top-news__tab js-news-tab is-active active" data-topic="all" role="tab" aria-selected="true">全て</button>
  </li>
  <?php foreach ($terms as $term): ?>
    <li class="nav-item" role="presentation">
      <button type="button" class="nav-link top-news__tab js-news-tab"
              data-topic="<?php echo esc_attr($term->slug); ?>"
              role="tab" aria-selected="false">
        <?php echo esc_html($term->name); ?>
      </button>
    </li>
  <?php endforeach; ?>
</ul>

<div class="top-news__panels">
  <?php
  // -----------------------------
  // パネル：全て（最新 $per_page）
  // -----------------------------
  $q_all = new WP_Query([
    'post_type'           => 'post',
    'posts_per_page'      => $per_page,
    'ignore_sticky_posts' => true,
  ]);
  ?>
  <div class="js-news-panel" data-topic="all">
    <?php if ($q_all->have_posts()): ?>
      <?php while ($q_all->have_posts()): $q_all->the_post(); $post_id = get_the_ID(); ?>
        <div class="js-news-item">
          <?php if ($card_tpl) { include $card_tpl; } else { ?>
            <article><time><?php echo esc_html(get_the_date('Y.m.d',$post_id)); ?></time>
              <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a></article>
          <?php } ?>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else: ?>
      <p class="top-news__empty">現在お知らせはありません。</p>
    <?php endif; ?>
  </div>

  <?php
  // -----------------------------
  // パネル：各カテゴリ（各 $per_page 件）
  // -----------------------------
  foreach ($terms as $term):
    $q_term = new WP_Query([
      'post_type'           => 'post',
      'posts_per_page'      => $per_page,
      'ignore_sticky_posts' => true,
      'tax_query' => [[
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $term->term_id,
        'include_children' => true, // 親タブで子カテゴリも含める
      ]],
    ]);
  ?>
    <div class="js-news-panel" data-topic="<?php echo esc_attr($term->slug); ?>" hidden>
      <?php if ($q_term->have_posts()): ?>
        <?php while ($q_term->have_posts()): $q_term->the_post(); $post_id = get_the_ID(); ?>
          <div class="js-news-item">
            <?php if ($card_tpl) { include $card_tpl; } else { ?>
              <article><time><?php echo esc_html(get_the_date('Y.m.d',$post_id)); ?></time>
                <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a></article>
            <?php } ?>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else: ?>
        <p class="top-news__empty">このカテゴリーにはまだ記事がありません。</p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const tabs   = Array.from(document.querySelectorAll('.js-news-tab'));
  const panels = Array.from(document.querySelectorAll('.js-news-panel'));

  function show(slug){
    tabs.forEach(b => {
      const on = b.dataset.topic === slug;
      b.classList.toggle('is-active', on);
      b.classList.toggle('active', on); 
      b.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panels.forEach(p => {
      p.hidden = (p.dataset.topic !== slug);
    });
  }

  tabs.forEach(b => b.addEventListener('click', e => {
    e.preventDefault();
    show(b.dataset.topic);
  }));

  show('all'); // 初期表示
});
</script>
<style>
    .js-news-panel[hidden]{ display:none !important; }
    .top-news__empty{ padding:16px 0; color:#666; }
</style>