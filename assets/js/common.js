jQuery(function($){ 

  /*-------------------------------------------*/
  /* jsでサイトのURL・テーマのパスを使えるようにする
  /*-------------------------------------------*/
  // var wp_temp_uri = tmp_path.temp_uri;
  // var wp_home_url = tmp_path.home_url;

  /*-------------------------------------------*/
  /* スムーススクロール
  /*-------------------------------------------*/
  var HeaderHeight = $('#header').outerHeight();
  var speed = 100;
	$('a[href^="#"]').on('click', function() {
    $(this).off('click');
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top - HeaderHeight;
		$('body,html').animate({scrollTop:position}, speed, 'swing');
		return false;
  });

  $(document).ready(function(){
    var urlHash = location.hash;
    if(urlHash) {
        hashposi = $(urlHash).offset().top - HeaderHeight;
        setTimeout(function () {
          $('body,html').animate({scrollTop:hashposi}, speed, 'swing');
        }, 100);
    }
  });

  /*-------------------------------------------*/
  /* アニメーション
  /*-------------------------------------------*/
  $(function () {
    if ($('.anime').length) {
        scrollAnimation();
    }
    function scrollAnimation() {
        $(window).scroll(function () {
            $(".anime").each(function () {
                let position = $(this).offset().top,
                    scroll = $(window).scrollTop(),
                    windowHeight = $(window).height();

                if (scroll > position - windowHeight + 200) {
                    $(this).addClass('is-animated');
                }
            });
        });
    }
    $(window).trigger('scroll');
  });

  $('.leftAnime').each(function(){ 
    var elemPos = $(this).offset().top-50;
    var scroll = $(window).scrollTop();
    var windowHeight = $(window).height();
    if (scroll >= elemPos - windowHeight){
      $(this).addClass("slideAnimeLeftRight");
      $(this).children(".leftAnimeInner").addClass("slideAnimeRightLeft");
    }else{
      $(this).removeClass("slideAnimeLeftRight");
      $(this).children(".leftAnimeInner").removeClass("slideAnimeRightLeft");
      
    }
  });

  $(function() {
    const targets = $('.anime_zoom_merit');
    if(!targets.length) return;

    $(window).scroll(function () {
        const slideBorder = $(this).scrollTop() + ($(this).outerHeight() * 0.7);
        targets.each(function() {
            if(slideBorder > $(this).offset().top) {
                $(this).addClass('active');
            }
        });
    });
  });

  /*-------------------------------------------*/
  /* ポップアップ
  /*-------------------------------------------*/
  // デフォルト
  $(document).on('click','.modal_trigger', function(){
    var modal_box = $(this).next('.modal_box');
    modal_box.fadeIn(); // モーダルを表示する
    $('body').addClass('overflow-hidden');
  });

  // ポップアップを閉じる
  $(document).on('click','.modal_close , .modal_bg', function(){
    $('.modal_box').fadeOut(); // モーダルを非表示にする
    $('body').removeClass('overflow-hidden');
  });
  $(document).on('click','.js-modal_trigger', function(){
    var modal_box = $(this).next('.js-modal_box');
    modal_box.fadeIn(); // モーダルを表示する
    $('body').addClass('overflow-hidden');
  });
  $(document).on('click','.js-modal_close , .js-modal_bg', function(){
    $('.js-modal_box').fadeOut(); // モーダルを非表示にする
    $('body').removeClass('overflow-hidden');
  });

  // メニュー用
  $(document).on('click','#js-sitemap_trigger', function(){
    $('#js-sitemap_modal').fadeIn();
    $('body').addClass('overflow-hidden');
  });
  $(document).on('click','#js-search_trigger', function(){
    $('#js-search_modal').fadeIn();
    $('body').addClass('overflow-hidden');
  });
  $(document).on('click','#js-search_trigger_pc', function(){
    $('#js-search_modal_pc').fadeIn();
    $('body').addClass('overflow-hidden');
  });

  /*-------------------------------------------*/
  /* アコーディオン
  /*-------------------------------------------*/
  // 上から下へ表示
  $('.acor-menu').on('click', function() {
    $(this).toggleClass('open');
    $(this).next('.acor-menu-child').slideToggle();
  });

  /*-------------------------------------------*/
  /* アーカイブ ページネーション
  /*-------------------------------------------*/
  if ($('.pnavi').length) {
    $("a.page-numbers").each( function(index, element) {
        var pageNumbers = $(element).attr('href');
        if (pageNumbers == '') {
          $(element).attr('href', location.pathname);
        }
    });
  }

  /*-------------------------------------------*/
  /* SPメニュー
  /*-------------------------------------------*/
  $('.js-menu_child_open').on('click', function(event) {
    event.preventDefault();
    $(this).toggleClass('js-open');
    $(this).next('.js-menu_child').toggleClass('js-open');
  });
  $('.js-ac-parent-left').on('click', function() {
    $(this).parent().toggleClass('js-open');
    $(this).toggleClass('js-open');
    $(this).next('.js-ac-child-left').toggleClass('js-open');
  });

  if (window.matchMedia('(min-width:768px)').matches) {
    $('.nav-page_link').addClass('open');
    $('.nav-page_link__btn').addClass('open');
    $('.nav-page_link__btn').next('.ac-child-left').addClass('open');
  }  
  $(window).scroll(function () {
    var scrollAmount = $(window).scrollTop();
    if (scrollAmount > 0) {
      $('body').addClass('scrolled');
      $('.nav-page_link').removeClass('open');
      $('.nav-page_link__btn').removeClass('open');
      $('.nav-page_link__btn').next('.ac-child-left').removeClass('open');
    } else {
      $('body').removeClass('scrolled');
    }
  });
  $('.ac-parent-left').on('click', function() {
    $(this).parent().toggleClass('open');
    $(this).toggleClass('open');
    $(this).next('.ac-child-left').toggleClass('open');
  });

  // アニメーション
  document.addEventListener('DOMContentLoaded', () => {
    const ioTargets = document.querySelectorAll('.ms-lines[data-io]');

    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('.ms-lines, .slideup-fade').forEach(el => el.classList.add('is-in'));
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          const container = e.target;
          container.classList.add('is-in');

          const delays = [...container.querySelectorAll('.ms-line')].map(line => {
            return parseInt(getComputedStyle(line).getPropertyValue('--d') || '0', 10);
          });
          const maxDelay = delays.length ? Math.max(...delays) : 0;
          const extra = 1000;

          const ctas = container.parentElement.querySelectorAll('.slideup-fade');
          setTimeout(() => {
            ctas.forEach((cta, i) => {
              setTimeout(() => {
                cta.classList.add('is-in');
              }, i * 120);
            });
          }, maxDelay + extra);

          io.unobserve(container);
        }
      });
    }, { threshold: .3 });

    ioTargets.forEach(el => io.observe(el));
  });

});
