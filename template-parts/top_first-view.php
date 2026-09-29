<?php
/**
 * Template Part:top_first-view
 * Place this file under: template-parts/top_first-view.php
 * Call via shortcode or template: get_template_part('template-parts/top_first-view');
 */
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<div class="main-visual position-relative m-0 w-100">
	<div class="kv">
		<?php if ( wp_is_mobile() ) : ?>
		<div class="kv__slider position-absolute">
			<div class="kv__slide" style="background-image:url(<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/top/mv-sp.jpg)"></div>
			<div class="kv__slide" style="background-image:url(<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/top/sub_mv-sp.jpg)"></div>
		</div>
		<h1 class="kv__lines kv--sp" aria-hidden="true">
			<span class="kv-line" style="--d:0;"><span class="kv-line__mask"><span class="kv-line__text">この地域に住み</span></span></span>
			<span class="kv-line" style="--d:240;"><span class="kv-line__mask"><span class="kv-line__text">暮らす人々の</span></span></span>
			<span class="kv-line" style="--d:480;"><span class="kv-line__mask"><span class="kv-line__text">よりよい <span class="kv-hi">住まい</span> の</span></span></span>
			<span class="kv-line" style="--d:720;"><span class="kv-line__mask"><span class="kv-line__text">実現に貢献する</span></span></span>
		</h1>
		<?php else: ?>
		<div class="kv__slider position-absolute">
			<div class="kv__slide" style="background-image:url(<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/top/mv-pc.jpg)"></div>
			<div class="kv__slide" style="background-image:url(<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/top/sub_mv-pc.jpg)"></div>
		</div>
		<h1 class="kv__lines kv--pc" aria-label="この地域に住み暮らす人々の よりよい住まいの 実現に貢献する">
			<span class="kv-line" style="--d:0;"><span class="kv-line__mask"><span class="kv-line__text">この地域に住み暮らす人々の</span></span></span>
			<span class="kv-line" style="--d:300;"><span class="kv-line__mask"><span class="kv-line__text">よりよい <span class="kv-hi">住まい</span> の</span></span></span>
			<span class="kv-line" style="--d:600;"><span class="kv-line__mask"><span class="kv-line__text">実現に貢献する</span></span></span>
		</h1>
		<?php endif; ?>
		<a class="kv__cta" href="<?php echo home_url('/business/'); ?>" aria-label="事業内容へ">事業内容へ</a>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
  jQuery(function($) {
    var $slider = $('.kv__slider');
    var kv = document.querySelector('.kv');

    if ($slider.length) {
      // slick がまだ未定義だと落ちるので存在チェック
      if (typeof $.fn.slick === 'function') {
        $slider.slick({
          autoplay: true,
          autoplaySpeed: 5000,
          fade: true,
          speed: 1500,
          arrows: false,
          dots: false,
          pauseOnHover: false,
          pauseOnFocus: false
        });
      } else {
        // 後から読み込まれる想定なら軽いリトライ
        var tries = 0;
        var timer = setInterval(function(){
          if (typeof $.fn.slick === 'function') {
            clearInterval(timer);
            $slider.slick({
              autoplay: true,
              autoplaySpeed: 5000,
              fade: true,
              speed: 1500,
              arrows: false,
              dots: false,
              pauseOnHover: false,
              pauseOnFocus: false
            });
          }
          if (++tries > 10) clearInterval(timer);
        }, 200);
      }
    }

    if (kv && 'IntersectionObserver' in window) {
      var io = new IntersectionObserver(function(entries) {
        entries.forEach(function(e){
          if (e.isIntersecting) {
            kv.classList.add('is-in');
            io.disconnect();
          }
        });
      }, { threshold: 0.3 });
      io.observe(kv);
    }
  });
</script>
