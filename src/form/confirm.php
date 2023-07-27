<?php
if (empty($arrForm)) {
	// パラメータがない場合、フォームTOPへ
	require_once '../app/require.php';
	$cl_common = new CL_Common();

	header("Location: " . ENTRY_URL_INPUT);
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<title>床下断熱サービス | ｜お問い合わせ確認画面</title>
<meta name="description" content="床下に吹き付けるだけで、冬も夏も快適に過ごせる「セスコの床下断熱サービス」。ランニングコストも0円なので、電気代もぐっとおさえられます。">
<meta name="keywords" content="お問い合わせフォーム">
<meta property="og:title" content="床下断熱サービス | ｜お問い合わせ確認画面">
<meta property="og:type" content="article">
<meta property="og:description" content="床下に吹き付けるだけで、冬も夏も快適に過ごせる「セスコの床下断熱サービス」。ランニングコストも0円なので、電気代もぐっとおさえられます。">
<meta property="og:url" content="">
<meta property="og:site_name" content="">
<meta property="og:image" content="">
<link rel="shortcut icon" href="../images/favicon.ico">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="床下断熱サービス | ｜お問い合わせ確認画面">
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
<body id="page-confirm">
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
    <div class="contact__bg contact__bg--num02">
        <h1 class="contact__ttl">入力内容のご確認</h1>
        <div class="contact__inner inner">
            <p class="contact__lead">入力内容をご確認のうえ、送信ボタンを押してください。</p>
            <div class="contact__box contact__box--confirm">
                <form class="contact__form" id="form" action="" method="post">
                    <input type="hidden" name="_uniq_token" value="<?php echo $cl_common->fnc_hx($sessionKey); ?>">
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm">お名前</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hx($arrForm['name01']); ?><?php echo $cl_common->fnc_hx($arrForm['name02']); ?></p>
                        </dd>
                    </dl>
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm">おなまえ(ふりがな)</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hx($arrForm['kana01']); ?><?php echo $cl_common->fnc_hx($arrForm['kana02']); ?></p>
                        </dd>
                    </dl>
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm contact__dt--required">電話番号</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hx($arrForm['tel']); ?></p>
                        </dd>
                    </dl>
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm contact__dt--required">メールアドレス</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hx($arrForm['email']); ?></p>
                        </dd>
                    </dl>
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm">郵便番号</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hx($arrForm['zip']); ?></p>
                        </dd>
                    </dl>
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm">ご住所</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hx($arrForm['addr01']); ?></p>
                        </dd>
                    </dl>
                    <dl class="contact__dl contact__dl--confirm">
                        <dt class="contact__dt contact__dt--confirm">ご要望</dt>
                        <dd class="contact__dd contact__dd--confirm">
                            <p class="contact__data"><?php echo $cl_common->fnc_hxbr($arrForm['inquiry']); ?></p>
                        </dd>
                    </dl>
                    <button type="submit" class="contact__btn01 contact__btn01--num02" id="btn-send">
                        <picture>
                            <source media="(max-width: 768px)" srcset="../images/contact_btn02_sp.webp">
                            <img src="../images/contact_btn02.webp" alt="送信する">
                        </picture>
                    </button>
                    <button type="button" class="contact__btn02" id="btn-back">戻る</button>
                </form>
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