<?php
require_once '../app/require.php';
require_once(CONT_REALDIR . 'inc.contact.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<title>床下断熱サービス | お問い合わせ入力画面</title>
<meta name="description" content="床下に吹き付けるだけで、冬も夏も快適に過ごせる「セスコの床下断熱サービス」。ランニングコストも0円なので、電気代もぐっとおさえられます。">
<meta name="keywords" content="お問い合わせフォーム">
<meta property="og:title" content="床下断熱サービス | お問い合わせ入力画面">
<meta property="og:type" content="article">
<meta property="og:description" content="床下に吹き付けるだけで、冬も夏も快適に過ごせる「セスコの床下断熱サービス」。ランニングコストも0円なので、電気代もぐっとおさえられます。">
<meta property="og:url" content="">
<meta property="og:site_name" content="">
<meta property="og:image" content="">
<link rel="shortcut icon" href="../images/favicon.ico">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="床下断熱サービス | お問い合わせ入力画面">
<meta name="twitter:description" content="床下に吹き付けるだけで、冬も夏も快適に過ごせる「セスコの床下断熱サービス」。ランニングコストも0円なので、電気代もぐっとおさえられます。">
<script>
    (function(d) {
      var config = {
        kitId: 'eat3god',
        scriptTimeout: 3000,
        async: true
      },
      h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
    })(document);
  </script>
<link rel="stylesheet" href="../css/style.css">
</head>
<body id="page-input">
<header class="header header--bg">
    <div class="header__inner inner">
        <div class="header__logo">
            <picture>
                <source media="(max-width: 768px)" srcset="../images/logo_sp.webp">
                <img loading="lazy" src="../images/logo.webp" alt="株式会社セスコ">
            </picture>
        </div>
    </div>
</header>
<!-- header -->

<section class="contact">
    <div class="contact__bg">
        <h1 class="contact__ttl">無料点検の申し込み<br>見積もり依頼・お問い合わせ</h1>
        <div class="contact__inner inner">
            <p class="contact__lead">セスコの床下断熱へのお問い合わせ、無料相談・<br class="sp-only">見積もりの依頼などは、次のWebフォームより<br>ご連絡ください。<br class="sp-only">入力いただいた情報はセスコの床下断熱のサービス提供のみに利用させて頂きます。</p>
            <div class="contact__box">
                <form class="contact__form" id="form" action="" method="post">
                    <div class="form-table">
<?php
if (!empty($arrErr)) {
	echo '<div class="errmsg-box">正しく入力されていない項目、または入力必須項目で未入力の項目があります。<br><br>';
	foreach ($arrErr as $k => $v) {
		echo $v;
	}
	echo '</div>';
}
?>
                        <dl class="contact__dl">
                            <dt class="contact__dt">お名前</dt>
                            <dd class="contact__dd">
                                <div class="contact__flex">
                                    <div class="contact__half">
                                        <input validation="optional" type="text" name="name01" class="<?php if ($arrErr['name01']) { echo 'error'; } ?>" id="name01" value="<?php echo $cl_common->fnc_hx($arrForm['name01']); ?>" placeholder="山田">
                                    </div>
                                    <div class="contact__half">
                                        <input validation="optional" type="text" name="name02" class="<?php if ($arrErr['name02']) { echo 'error'; } ?>" id="name02" value="<?php echo $cl_common->fnc_hx($arrForm['name02']); ?>" placeholder="太郎">
                                    </div>
                                </div>
                            </dd>
                        </dl>
                        <dl class="contact__dl">
                            <dt class="contact__dt">おなまえ(ふりがな)</dt>
                            <dd class="contact__dd">
                                <div class="contact__flex">
                                    <div class="contact__half">
                                        <input validation="hiragana" type="text" name="kana01" class="<?php if ($arrErr['kana01']) { echo 'error'; } ?>" id="kana01" value="<?php echo $cl_common->fnc_hx($arrForm['kana01']); ?>" placeholder="やまだ">
                                    </div>
                                    <div class="contact__half">
                                        <input validation="hiragana" type="text" name="kana02" class="<?php if ($arrErr['kana02']) { echo 'error'; } ?>" id="kana02" value="<?php echo $cl_common->fnc_hx($arrForm['kana02']); ?>" placeholder="たろう">
                                    </div>
                                </div>
                            </dd>
                        </dl>
                        <dl class="contact__dl contact__dl--num02">
                            <dt class="contact__dt contact__dt--required">電話番号</dt>
                            <dd class="contact__dd">
                                <input validation="tel" type="tel" name="tel" class="<?php if ($arrErr['tel']) { echo 'error'; } ?>" id="tel" value="<?php echo $cl_common->fnc_hx($arrForm['tel']); ?>" placeholder="08012345678">
                                <p class="contact__alert">-（ハイフン）は不要です。数字のみご入力ください</p>
                            </dd>
                        </dl>
                        <dl class="contact__dl">
                            <dt class="contact__dt contact__dt--required">メールアドレス</dt>
                            <dd class="contact__dd">
                                <input validation="email" type="email" name="email" class="<?php if ($arrErr['email']) { echo 'error'; } ?>" id="email" value="<?php echo $cl_common->fnc_hx($arrForm['email']); ?>" placeholder="abc@def.com">
                            </dd>
                        </dl>
                        <dl class="contact__dl contact__dl--num02">
                            <dt class="contact__dt">郵便番号</dt>
                            <dd class="contact__dd">
                                <div class="contact__half">
                                    <input validation="zip" type="text" name="zip" class="<?php if ($arrErr['zip']) { echo 'error'; } ?>" id="zip" value="<?php echo $cl_common->fnc_hx($arrForm['zip']); ?>" placeholder="1234567">
                                </div>
                                <p class="contact__alert">-（ハイフン）は不要です。数字のみご入力ください</p>
                            </dd>
                        </dl>
                        <dl class="contact__dl">
                            <dt class="contact__dt">ご住所</dt>
                            <dd class="contact__dd">
                                <input type="text" name="addr01" class="<?php if ($arrErr['addr01']) { echo 'error'; } ?>" id="addr01" value="<?php echo $cl_common->fnc_hx($arrForm['addr01']); ?>" placeholder="東京都足立区東伊興1-16-6">
                            </dd>
                        </dl>
                        <dl class="contact__dl contact__dl--num02">
                            <dt class="contact__dt">ご要望</dt>
                            <dd class="contact__dd">
                                <textarea name="inquiry" class="<?php if ($arrErr['inquiry']) { echo 'error'; } ?>" id="inquiry" cols="10" rows="5" placeholder="ご質問・ご要望など、&#10;ご自由に記入してください。"><?php echo $cl_common->fnc_hx($arrForm['inquiry']); ?></textarea>
                            </dd>
                        </dl>
                        <button type="submit" class="contact__btn01" id="btn-confirm">
                            <picture>
                                <source media="(max-width: 768px)" srcset="../images/contact_btn01_sp.webp">
                                <img src="../images/contact_btn01.webp" alt="内容確認">
                            </picture>
                        </button>
                    </div>
                </form>
            </div>
            <div class="contact__low">
                <p class="contact__txt01">お電話でのお問い合わせも承っております。</p>
                <a class="contact__tel" href="tel:0120526006">
                    <picture>
                        <source media="(max-width: 768px)" srcset="../images/contact_img01_sp.webp">
                        <img src="../images/contact_img01.webp" alt="0120526006">
                    </picture>
                </a>
            </div>
        </div>
    </div>
</section>

<footer class="footer footer--bg">
    <div class="footer__inner inner">
        <span class="footer__copy">SESCO co.,ltd. All Rights Reserved.</span>
    </div>
</footer>
<!-- footer -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
<script src="../js/validation.js" defer></script>
</body>
</html>