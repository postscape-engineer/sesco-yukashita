<?php
/**
 * システム関連_設定
 */

//====================================
// 共通部
//====================================
/** 言語設定 */
define('MB_LANGUAGE', 'ja');
/** 内部エンコーディング */
define('ENC_PHP', 'UTF-8');
/** PHP エラーの種類を設定 */
define('ENVIRONMENT', 'production');

/** サイトのベース（/） */
$base_path = dirname(dirname(dirname(__FILE__)));
$base_path = str_replace(array('\\'), '/', $base_path);
define('BASE_URL', str_replace($_SERVER['DOCUMENT_ROOT'], '', $base_path).'/');

//====================================
// メール送信関連　共通
//====================================
/** システムMailの送り主 */
// define('SYSTEM_MAIL_FROM', 'info@sesco.co.jp');
define('SYSTEM_MAIL_FROM', 'saki.ogawa@postscape.jp');


//====================================
// 【お問い合わせ】フォーム関連
//====================================
/** 【お問い合わせ】URL */
define('ENTRY_URL_INPUT', './');
define('ENTRY_URL_CONFIRM', 'confirm.php');
define('ENTRY_URL_COMPLETE', 'thanks.php');
/** 【お問い合わせ】受付Email */
define('ENTRY_EMAIL', SYSTEM_MAIL_FROM);
/** 【お問い合わせ】受付Email（CC） */
define('ENTRY_EMAIL_CC', '');
/** 【お問い合わせ】受付Email（BCC） */
define('ENTRY_EMAIL_BCC', '');
/** 【お問い合わせ】受付Email　FromName */
define('ENTRY_EMAIL_FROMNAME', '株式会社セスコ');
/** 【お問い合わせ】受付Email（件名：ユーザ向け） */
define('ENTRY_EMAIL_SUBJECT', '床下断熱サービスへのお問い合わせありがとうございます。');
/** 【お問い合わせ】受付Email（件名：管理者向け） */
define('ENTRY_EMAIL_SUBJECT_ADMIN', 'お問い合わせを受付ました【床下断熱サービス】');

/**
 * サイト関連_設定
 */
//====================================
// 共通部（PC・スマホ用）
//====================================
/*** ブラウザのキャッシュ対応用 ***/
define ('CACHE_VER', '?230726');

//====================================
// FORM用定義
//====================================
/** 短いテキスト項目の文字数 */
define('STEXT_LEN', 50);
define('SMTEXT_LEN', 100);
/** 長いテキスト項目の文字数 */
define('MTEXT_LEN', 200);
/** 中文の文字数 */
define('MLTEXT_LEN', 1000);
/** 長文の文字数 */
define('LTEXT_LEN', 3000);
/** URLの文字数 */
define('URL_LEN', 1024);
/** 郵便番号1 */
define('ZIP01_LEN', 3);
/** 郵便番号2 */
define('ZIP02_LEN', 4);
/** 電話番号総数 */
define('TEL_LEN', 13);
/** 数値用桁数 */
define('INT_LEN', 9);

//====================================
// その他
//====================================
/** エラー時表示色 */
define('ERR_COLOR', 'background-color:#ffe8e8;');
/** ログファイル最大数(ログテーション) */
define('MAX_LOG_QUANTITY', 5);
/** 1つのログファイルに保存する最大容量(byte) */
define('MAX_LOG_SIZE', '1000000');
/** フォームデータ　ログファイル */
define('FILE_LOG_TXT', 'output_log.txt');

?>