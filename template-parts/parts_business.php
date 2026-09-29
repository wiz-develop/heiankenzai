<?php
/**
 * Template Part: parts_business
 * Place this file under: template-parts/parts_business.php
 * Call via shortcode or template: get_template_part('template-parts/parts_business');
 */
?>
<style>
/* 追加のCSS: 写真が右から左へスライドする動き */
@media (min-width: 992px) {
    /* 初期状態: 右にずれて透明 */
    .ao-slide-right {
        opacity: 0;
        transform: translateX(50px);
        transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.8s ease;
        will-change: transform, opacity;
    }
    /* 発火時: 元の位置へ */
    .ao-slide-right.is-in {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
<div class="business-section mb-0">
    <div class="business-section__area">
        <!-- Hero/Lead -->
        <div class="business-section__area__header text-center text-white mb-5 mb-lg-6 ao-fade" data-ao>
            <h2 class="biz-title h1 fw-bold">京都の暮らしに寄り添う<span class="accent">住まいづくりのパートナー</span></h2>
            <p class="en-tit fw-bold text-uppercase letter-space-2 my-3">BUSINESS</p>
            <p class="biz-lead mt-4 mb-0">平安建材は、京都の街並みや文化に調和した住まいづくりを支えていきます。<br class="d-none d-md-inline">京都を中心に地域に根ざした企業として、よりよい材料とサービスで暮らしを豊かにします。</p>
        </div>
        <div class="row g-4 justify-content-center biz-icons" data-ao-group>
            <div class="col-6 col-sm-4 col-lg-2">
                <a href="#kyo-kurasi">
                    <div class="icon-card ao-pop" data-ao data-ao-order="1">
                        <div class="icon-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/icon_kyokurasi.png" class="img-fluid">
                        </div>
                        <div class="icon-caption mt-2"><div class="caption-main">『京ぐらし』<br class="sp">ネットワーク</div></div>
                        <div class="arrow-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/link-arrow.png">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <a href="#grants-support">
                    <div class="icon-card ao-pop" data-ao data-ao-order="2">
                        <div class="icon-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/icon_grants-support.png" class="img-fluid">
                        </div>
                        <div class="icon-caption mt-2"><div class="caption-main">補助金・助成金の<br>申請サポート</div></div>
                        <div class="arrow-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/link-arrow.png">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <a href="#insurance-support">
                    <div class="icon-card ao-pop" data-ao data-ao-order="4">
                        <div class="icon-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/icon_insurance-support.png" class="img-fluid">
                        </div>
                        <div class="icon-caption mt-2"><div class="caption-main">瑕疵保険責任保険<br>（瑕疵保険）の<br>申請サポート</div></div>
                        <div class="arrow-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/link-arrow.png">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <a href="#group-delivery">
                    <div class="icon-card ao-pop" data-ao data-ao-order="3">
                        <div class="icon-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/icon_ground-survey.png" class="img-fluid">
                        </div>
                        <div class="icon-caption mt-2"><div class="caption-main">自社（グループ）配送</div></div>
                        <div class="arrow-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/link-arrow.png">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <a href="#in-house">
                    <div class="icon-card ao-pop" data-ao data-ao-order="5">
                        <div class="icon-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/icon_design.png" class="img-fluid">
                        </div>
                        <div class="icon-caption mt-2"><div class="caption-main">自社施工<br>（外装、断熱、太陽光）</div></div>
                        <div class="arrow-wrap">
                            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images//business/icon/link-arrow.png">
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- コンテンツブロック：『京ぐらし』ネットワーク -->
        <div id="kyo-kurasi" class="row g-0 align-items-stretch biz-row">
            <div class="col-12 col-lg-6 order-lg-1 ao-slide-left" data-ao>
                <div class="biz-panel h-100 d-flex flex-column justify-content-center text-white px-4 px-md-5">
                    <h3 class="fw-bold mb-4 text-center">『京ぐらし』ネットワーク</h3>
                    <div class="biz-panel__icon icon-wrap">
                        <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/icon/icon_kyokurasi.png" class="img-fluid">
                    </div>
                    <div class="biz-panel__about">
                        <p class="h5 fw-bold my-3 text-center">町家を未来へ、<br class="sp">快適で安心な暮らしに</p>
                        <p class="mb-4">
                            京都の気候や地震に対応できない町家の問題を解決するため、『京ぐらし』ネットワークに賛同して頂いた地元の工務店、メーカー、設計事務所、宅建事業者が一丸となり、住宅性能の向上や京都の景観に沿ったデザインなどをトータルでご提案しています。
                        </p>
                        <a href="http://kyogurashi.com/network/" target="_blank" class="btn btn-outline-light rounded-pill px-4">公式ホームページへ</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 order-lg-2 ao-flow-left" data-ao>
                <div class="biz-photo h-100">
                <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/img_kyokurasi.jpg" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>

        <!-- 補助金・助成金 -->
        <div id="grants-support" class="row g-0 align-items-stretch biz-row">
            <div class="col-12 col-lg-6 ao-slide-left" data-ao>
                <div class="biz-panel h-100 d-flex flex-column justify-content-center text-white px-4 px-md-5">
                    <h3 class="fw-bold mb-4 text-center">補助金・助成金の<br>申請サポート</h3>
                    <div class="biz-panel__icon icon-wrap">
                        <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/icon/icon_grants-support.png" class="img-fluid">
                    </div>
                    <div class="biz-panel__about">
                        <p class="h5 fw-bold my-3 text-center">商品販売だけが<br class="sp">サービスではありません</p>
                        <p class="mb-4">
                            補助金の申請は、申請内容を理解したり申請の可否判断が難しく、申請者様にとっては非常にストレスとなっています。<br>
                            そこで、申請者様からのご相談や各種証明書の発行もお手伝いさせて頂いております。<br>
                            また、フルサポートの場合は行政書士や社労士への斡旋も可能です。
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 ao-flow-left" data-ao>
                <div class="biz-photo h-100">
                <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/img_grants-support.jpg" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>

        <!-- 瑕疵担保責任保険の申請サポート-->
        <div id="insurance-support" class="row g-0 align-items-stretch biz-row">
            <div class="col-12 col-lg-6 ao-slide-left" data-ao>
                <div class="biz-panel h-100 d-flex flex-column justify-content-center text-white px-4 px-md-5">
                    <h3 class="fw-bold mb-4 text-center">瑕疵保険責任保険（瑕疵保険）の<br>申請サポート</h3>
                    <div class="biz-panel__icon icon-wrap">
                        <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/icon/icon_insurance-support.png" class="img-fluid">
                    </div>
                    <div class="biz-panel__about">
                        <p class="h5 fw-bold my-3 text-center">瑕疵保険加入は住宅供給者の義務です</p>
                        <p class="mb-4">
                            住宅瑕疵担保履行法により新築住宅を引渡す建設業者及び宅建業者には瑕疵担保責任を確実に履行するための保険等による資力確保措置（修繕費用等に充当）が義務化されています。<br>
                            その瑕疵保険の取次を弊社が行っており、住宅資材の販売前から携わっております。
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 ao-flow-left" data-ao>
                <div class="biz-photo h-100">
                    <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/img_insurance-support.jpg" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>

        <!-- 自社（グループ）配送 -->
        <div id="group-delivery" class="row g-0 align-items-stretch biz-row">
            <div class="col-12 col-lg-6 ao-slide-left" data-ao>
                <div class="biz-panel h-100 d-flex flex-column justify-content-center text-white px-4 px-md-5">
                    <h3 class="fw-bold mb-4 text-center">自社（グループ）配送</h3>
                    <div class="biz-panel__icon icon-wrap">
                        <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/icon/icon_ground-survey.png" class="img-fluid">
                    </div>
                    <div class="biz-panel__about">
                        <p class="h5 fw-bold my-3 text-center">住宅資材のプロ</p>
                        <p class="mb-4">
                            建材商社である我社の配送部門が運送事業許可（緑ナンバー）を取得し、住宅資材のプロとして安全は基より親切・丁寧・着実にご要望の場所までお届けします。
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 ao-flow-left" data-ao>
                <div class="biz-photo h-100">
                    <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/img_ground-survey.jpg" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>

        <!-- 自社施工（外装、断熱、太陽光）-->
        <div id="in-house" class="row g-0 align-items-stretch biz-row">
            <div class="col-12 col-lg-6 ao-slide-left" data-ao>
                <div class="biz-panel h-100 d-flex flex-column justify-content-center text-white px-4 px-md-5">
                    <h3 class="fw-bold mb-4 text-center">自社施工<br>（外装、断熱、太陽光）</h3>
                    <div class="biz-panel__icon icon-wrap">
                        <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/icon/icon_design.png" class="img-fluid">
                    </div>
                    <div class="biz-panel__about">
                        <p class="h5 fw-bold my-3 text-center">商品販売と工事を併せることで<br class="sp">人材不足対策に貢献</p>
                        <p class="mb-4">
                            住宅資材のプロが商品のご提案、選定アドバイス、施工方法等をお伝えします。<br>
                            また、施工者手配に苦慮することなく商品と施工をご依頼頂けます。
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 ao-flow-left" data-ao>
                <div class="biz-photo h-100">
                <img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/images/business/img_in-house.jpg" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
(() => {
  const section = document.querySelector('.business-section');
  if (!section) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    section.querySelectorAll('.biz-panel, .biz-photo, .biz-icons .icon-card, .ao-slide-right')
      .forEach(el => el.classList.add('is-in'));
    return;
  }

  // ===== アイコン群の設定 =====
  const iconGroup = section.querySelector('.biz-icons');
  if (iconGroup) {
    iconGroup.querySelectorAll('.icon-card').forEach((el, i) => {
      el.setAttribute('data-ao', '');
      el.style.setProperty('--ao-delay', `${i * 80}ms`);
    });
  }

  // ===== 共通アニメーションターゲットの設定 =====
  // HTML側でクラスを振っているので、ここでは遅延のみ管理
  const targets = section.querySelectorAll('[data-ao]');
  
  const io = new IntersectionObserver((entries) => {
    entries.forEach(ent => {
      if (ent.isIntersecting) {
        ent.target.classList.add('is-in');
        io.unobserve(ent.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -10% 0px' });

  targets.forEach(t => io.observe(t));
})();
</script>
