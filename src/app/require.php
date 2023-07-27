<?php
/*
 * ロード処理
 */

/** APPディレクトリのパス */
define('APP_REALDIR', rtrim(realpath(rtrim(realpath(dirname(__FILE__)), '/\\') . '/'), '/\\') . '/');

/** HTDOCSディレクトリのパス */
define('HTDOCS_REALDIR', APP_REALDIR . '../');

/** CONTROLLERSディレクトリのパス */
define('CONT_REALDIR', APP_REALDIR . 'controllers/');
/** LOGディレクトリのパス */
define('LOG_REALDIR', APP_REALDIR . 'logs/');

// CONFIGファイルの読み込み
require_once APP_REALDIR . 'config/config.php';
// 共通クラス読み込み
require_once APP_REALDIR . 'class/CL_Common.php';
// ユーティリティクラス読み込み
require_once APP_REALDIR . 'class/CL_Utils.php';
// エラーチェッククラス読み込み
require_once APP_REALDIR . 'class/CL_CheckError.php';


// ------------------------------
// クラス初期化
// ------------------------------
$cl_common = new CL_Common();
$cl_utils = new CL_Utils();

?>