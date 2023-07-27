<?php
/**
 * 共通クラス.
 *
 */
class CL_Common {

	private static $DEF_DATA = array(
/*===========================================================
 * 　共通　
==========================================================*/
		// 都道府県（パラメータ：数値）
		'pref_data' => array(1=>'北海道',2=>'青森県',3=>'岩手県',4=>'宮城県',5=>'秋田県',6=>'山形県',7=>'福島県',8=>'茨城県',9=>'栃木県',10=>'群馬県',11=>'埼玉県',12=>'千葉県',13=>'東京都',14=>'神奈川県',15=>'新潟県',16=>'富山県',17=>'石川県',18=>'福井県',19=>'山梨県',20=>'長野県',21=>'岐阜県',22=>'静岡県',23=>'愛知県',24=>'三重県',25=>'滋賀県',26=>'京都府',27=>'大阪府',28=>'兵庫県',29=>'奈良県',30=>'和歌山県',31=>'鳥取県',32=>'島根県',33=>'岡山県',34=>'広島県',35=>'山口県',36=>'徳島県',37=>'香川県',38=>'愛媛県',39=>'高知県',40=>'福岡県',41=>'佐賀県',42=>'長崎県',43=>'熊本県',44=>'大分県',45=>'宮崎県',46=>'鹿児島県',47=>'沖縄県'),
		// 都道府県（パラメータ：文字列）
		'pref_str_data' => array(''=>'---お選びください---','北海道'=>'北海道','青森県'=>'青森県','岩手県'=>'岩手県','秋田県'=>'秋田県','宮城県'=>'宮城県','山形県'=>'山形県','福島県'=>'福島県','栃木県'=>'栃木県','群馬県'=>'群馬県','茨城県'=>'茨城県','埼玉県'=>'埼玉県','東京都'=>'東京都','千葉県'=>'千葉県','神奈川県'=>'神奈川県','山梨県'=>'山梨県','長野県'=>'長野県','新潟県'=>'新潟県','富山県'=>'富山県','石川県'=>'石川県','福井県'=>'福井県','静岡県'=>'静岡県','岐阜県'=>'岐阜県','愛知県'=>'愛知県','三重県'=>'三重県','滋賀県'=>'滋賀県','京都府'=>'京都府','大阪府'=>'大阪府','兵庫県'=>'兵庫県','奈良県'=>'奈良県','和歌山県'=>'和歌山県','徳島県'=>'徳島県','香川県'=>'香川県','愛媛県'=>'愛媛県','高知県'=>'高知県','鳥取県'=>'鳥取県','島根県'=>'島根県','岡山県'=>'岡山県','広島県'=>'広島県','山口県'=>'山口県','福岡県'=>'福岡県','佐賀県'=>'佐賀県','長崎県'=>'長崎県','大分県'=>'大分県','熊本県'=>'熊本県','宮崎県'=>'宮崎県','鹿児島県'=>'鹿児島県','沖縄県'=>'沖縄県'),

/*===========================================================
 * 　お問い合わせフォーム　
 ==========================================================*/
		//	お問い合わせ項目
		'inquirytype_data' => array(
			1	=>	'事業・会社に関するお問い合わせ',
			2	=>	'採用に関するお問い合わせ',
			3	=>	'その他',
		),

	);

	/**
	 * コンストラクタ
	 */
	function __construct() {
		// ------------------------------
		// ログ出力設定
		// ------------------------------
		//ini_set("log_errors", "On");
		//ini_set("error_log", "errors.log" );

		// ------------------------------
		// PHP エラーの種類を設定
		// ------------------------------
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				case 'development':
					error_reporting(E_ALL);
					break;

				case 'testing':
					error_reporting(E_ALL & ~E_NOTICE);
					break;

				case 'production':
					error_reporting(0);
					break;

				default:
					exit('The application environment is not set correctly.');
			}
		}

		// ------------------------------
		// タイムゾーン設定
		// ------------------------------
		date_default_timezone_set('Asia/Tokyo');

		// ------------------------------
		// デバイス定義
		// ------------------------------
		//$this->setDefineDevice();
	}



// --------------------------------------------------------------------
// 　定数関連
// --------------------------------------------------------------------
	/**
	 * デバイス定義
	 */
	public function setDefineDevice() {
		require_once APP_REALDIR . 'class/CL_UserAgent.php';
		$ua = new CL_UserAgent();

		if ( $ua->set() === "mobile" ) {
			// スマホ
			define('IS_MOBILE', TRUE);
		}
		else {
			// PC・タブレット
			define('IS_MOBILE', FALSE);
		}
	}

	/**
	 * 定義データ取得
	 *
	 * @param	string	$_name	定義名
	 */
	public function getDefData($_name) {
		if (empty($_name)) return;
		return self::$DEF_DATA[$_name];
	}

	/**
	 * 定義データ取得（ID指定）
	 *
	 * @param	string	$_name	定義名
	 * @param	integer	$_id
	 */
	public function getDefDataStr($_name, $_id) {
		if (empty($_name) || empty($_id)) return;
		return self::$DEF_DATA[$_name][$_id];
	}

	/**
	 * 定数 ⇒ 変数 への展開用
	 *
	 * @param	string	$_defineKey	定数
	 */
	public function getDefineParse($_defineKey) {
		if (empty($_defineKey)) return;
		return $_defineKey;
	}



// --------------------------------------------------------------------
// 　補正関連
// --------------------------------------------------------------------
	/**
	 * 半角英数チェック
	 *
	 * @param	string	$_str
	 * @return	boolean	true ／ false
	 */
	public function fnc_chk_hankaku($_str) {
		if (preg_match("/^[a-zA-Z0-9]+$/", $_str)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 文字列修正
	 *
	 * @param	string	$_str	修正前文字列
	 * @return	string	$_str	修正後文字列
	 */
	public function fnc_correct_str($_str) {
		$_str = str_replace("'", "", $_str);
		$_str = str_replace("\\", "", $_str);
		return $_str;
	}

	/**
	 * 特殊文字を HTML エンティティに変換する.
	 *
	 * @param	string	$_str		変換される文字列
	 * @param	string	$_flags		フラグ
	 * @param	string	$_charset	エンコーディング
	 * @return	string	変換後の文字列
	 */
	public function fnc_hx($_str, $_flags = ENT_QUOTES, $_charset = 'UTF-8') {
		//if (empty($_str)) return;
		if (!isset($_str)) return;

		return htmlspecialchars($_str, $_flags, $_charset);

//		$str = htmlspecialchars($_str, $_flags, $_charset);
//		$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);
//
//		return $str;
	}

	/**
	 * 特殊文字を HTML エンティティに変換する.（brタグを追加する）
	 *
	 * @param	string	$_str		変換される文字列
	 * @param	string	$_flags		フラグ
	 * @param	string	$_charset	エンコーディング
	 * @return	string	変換後の文字列
	 */
	public function fnc_hxbr($_str, $_flags = ENT_QUOTES, $_charset = 'UTF-8') {
		return nl2br(htmlspecialchars($_str, $_flags, $_charset));
	}

	/**
	 * 入力データのエスケープ処理
	 *
	 * @param	array	$_array
	 * @return	mixed
	 */
	public function __sanitize($_array) {
		return $this->__sanitizeEx($_array);
	}

	/**
	 * 入力データのエスケープ処理（カスタム用）
	 *
	 * 　※POSTされたデータに対して、以下の処理実行する
	 * 　　・HTMLタグの除去
	 *
	 * @param string|array $_data Data to sanitize
	 * @return mixed Sanitized data
	 */
	public function __sanitizeEx($_data) {
		if (empty($_data)) {
			return $_data;
		}

		if (is_array($_data)) {
			foreach ($_data as $key => $val) {
				$_data[$key] = $this->__sanitizeEx($val);
			}
			return $_data;
		}

		// HTMLタグの除去
		$_data = strip_tags($_data, '');

		// 入力文字列のエスケープ処理
		//$_data = $this->fnc_hx($_data);

		return $_data;
	}



// --------------------------------------------------------------------
// 　その他
// --------------------------------------------------------------------
	/**
	 * セッション初期化・開始
	 *
	 */
	public function initSession() {

		mb_language(MB_LANGUAGE);
		mb_internal_encoding(ENC_PHP);
		mb_regex_encoding(ENC_PHP);

		ini_set('session.cache_limiter', 'none');
		ini_set('session.cookie_lifetime', 0);
		session_start();
	}

	/**
	 * アプリケーションログ出力
	 *
	 * @param	string	$_message
	 * @return	void
	 */
	public function output_log($_message) {
		$path = LOG_REALDIR . FILE_LOG_TXT;
		$fp = fopen( $path, 'a');
		fwrite($fp, $_message);
		fclose($fp);

		// ログテーション実装
		$this->logRotation(MAX_LOG_QUANTITY, MAX_LOG_SIZE, $path);
	}

	/**
	 * アプリケーションログ　デバッグ内容出力
	 *
	 * @param	array	$_str
	 * @return	void
	 */
	public function output_debug($_str) {
		$path = LOG_REALDIR . FILE_LOG_TXT;
		$fp = fopen( $path, 'a+');
		if (is_array($_str)) {
			ob_start();
			var_dump($_str);
			$buf = ob_get_contents();
			ob_end_clean();
			fwrite($fp, $buf);
		}
		fclose($fp);

		// ログテーション実装
		$this->logRotation(MAX_LOG_QUANTITY, MAX_LOG_SIZE, $path);
	}

	/**
	 * ログローテーション機能
	 *
	 * @param	integer	$max_log	最大ファイル数
	 * @param	integer	$max_size	最大サイズ
	 * @param	string	$path		ファイルパス
	 * @return	void
	 */
	public function logRotation($max_log, $max_size, $path) {
		if (!file_exists($path)) return;
		if (filesize($path) <= $max_size) return;

		$path_max = "$path.$max_log";
		if (file_exists($path_max)) {
			$res = unlink($path_max);
			if (!$res) return;
		}

		for ($i = $max_log; $i >= 2; $i--) {
			$path_old = "$path." . ($i - 1);
			$path_new = "$path.$i";
			if (file_exists($path_old)) {
				rename($path_old, $path_new);
			}
		}

		rename($path, "$path.1");
	}

}
?>