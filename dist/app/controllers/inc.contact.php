<?php
/*************************************************
 * 「お問い合わせフォーム」ページ
 *************************************************/

// ------------------------------
// 初期設定
// ------------------------------
// セッション初期化
$cl_common->initSession();
// セッションキーをセット
$sessionKey = setSessKey();



/***********************************
	フォーム　項目定義
***********************************/
// ------------------------------
// フォーム用マスタデータ取得
// ------------------------------
$arrMasterData = array(
	'inquirytypeData'	=>	$cl_common->getDefData('inquirytype_data'),
);

// ------------------------------
// フォーム　項目
// ------------------------------
$arrFormItem = array(
	'name01' => array(
		'disp_name'	=> 'お名前：姓',
		'length'	=> STEXT_LEN,
		'convert'	=> 'aKV',
		'escape'	=> true,
		'validate'	=> array('NO_SP', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'name02' => array(
		'disp_name'	=> 'お名前：名',
		'length'	=> STEXT_LEN,
		'convert'	=> 'aKV',
		'escape'	=> true,
		'validate'	=> array('NO_SP', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'kana01' => array(
		'disp_name'	=> 'ふりがな：せい',
		'length'	=> STEXT_LEN,
		'convert'	=> 'acHV',
		'escape'	=> true,
		'validate'	=> array('NO_SP', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'kana02' => array(
		'disp_name'	=> 'ふりがな：めい',
		'length'	=> STEXT_LEN,
		'convert'	=> 'acHV',
		'escape'	=> true,
		'validate'	=> array('NO_SP', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'tel' => array(
		'disp_name'	=> '電話番号',
		'length'	=> 13,
		'convert'	=> 'rnask',
		'escape'	=> true,
		'validate'	=> array('EXIST_CHECK', 'NO_SPTAB', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'email' => array(
		'disp_name'	=> 'メールアドレス',
		'length'	=> SMTEXT_LEN,
		'convert'	=> 'a',
		'escape'	=> true,
		'validate'	=> array('EXIST_CHECK', 'EMAIL_CHECK', 'NO_SPTAB', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'zip' => array(
		'disp_name'	=> '郵便番号',
		'length'	=> 8,
		'convert'	=> 'rnask',
		'escape'	=> true,
		'validate'	=> array('NO_SPTAB', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'addr01' => array(
		'disp_name'	=> 'ご住所',
		'length'	=> MTEXT_LEN,
		'convert'	=> 'aKV',
		'escape'	=> true,
		'validate'	=> array('NO_SP', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
	'inquiry' => array(
		'disp_name'	=> 'ご要望',
		'length'	=> MLTEXT_LEN,
		'convert'	=> 'aKV',
		'escape'	=> true,
		'validate'	=> array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'),
		'ini'		=> '',
	),
);



/***********************************
	メイン処理
***********************************/
// ------------------------------
// 処理の分岐
// ------------------------------
// modeの取得
$mode = '';
if (!empty($_POST['mode'])) $mode = $_POST['mode'];

switch ($mode) {
	case 'confirm':
		// セッションキーのチェック
		chkSessKey();

		// 入力データ取得
		$arrForm = getFormParams($_POST);

		// 入力データのエスケープ処理
		$arrForm = $cl_common->__sanitize($arrForm);

		// エラーチェック
		$arrErr = chkFormError($arrForm);
		if ( !$arrErr ) {
			// 確認画面ファイルの読み込み
			include ENTRY_URL_CONFIRM;
			exit();
		}

		$moveform = true;
		break;

	case 'return':
		// セッションキーのチェック
		chkSessKey();

		// 入力データ取得
		$_SESSION[$sessionKey]['params']['mode'] = $mode;
		$arrForm = getFormParams($_SESSION[$sessionKey]['params']);

		$moveform = true;
		break;

	case 'complete':
		// セッションキーのチェック
		chkSessKey();

		// 確認画面からのトークン値とセッションキーの整合性チェック
		$uniq_token = isset($_POST['_uniq_token']) ? $_POST['_uniq_token'] : '';
		if ($sessionKey !== $uniq_token) {
			// 一致しない場合、フォームTOPへ
			redirectToFormTop();
		}

		// 入力データ取得
		$_SESSION[$sessionKey]['params']['mode'] = $mode;
		$arrForm = getFormParams($_SESSION[$sessionKey]['params']);

		// 入力データのエスケープ処理
		$arrForm = $cl_common->__sanitize($arrForm);

		// エラーチェック
		$arrErr = chkFormError($arrForm);
		if ( !$arrErr ) {
			// ------------------------------
			// メール送信処理
			// ------------------------------
			// ----- Send_mail クラスの読み込み -----
			require_once APP_REALDIR . 'class/CL_SendMail.php';
			$cl_sendmail = new CL_SendMail();

			sendMail($arrForm);

			// ------------------------------
			// 完了画面へリダイレクト
			// ------------------------------
			// セッションキーの削除
			unset($_SESSION[$sessionKey]);
			session_destroy();
			// 現在のセッションIDから新しいセッションIDを生成
			session_id(md5(uniqid(rand(), 1)));
			session_start();

			header("Location: " . ENTRY_URL_COMPLETE);
			exit();
		}

		$moveform = true;
		break;

	default:
		// 初期値をセット
		$arrForm = getFormParams(array('mode'=>''));
		break;

}



/***********************************
	関数定義
***********************************/
	/**
	 * 入力データの取得処理.
	 *
	 * @param array $_array 入力パラメータ
	 * @return array $arrForm 入力データ
	 */
	function getFormParams($_array) {
		if (empty($_array)) return;

		global $cl_utils, $sessionKey, $arrFormItem;

		// パラメータ取得
		$arrForm['mode'] = isset($_array['mode']) ? $_array['mode'] : '';
		foreach ($arrFormItem as $key => $val) {
			if (array_key_exists($key, $_array)) {
				$arrForm[$key] = isset($_array[$key]) ? $_array[$key] : '';
			}
			else {
				// 入力データが無ければ初期値をセット
				if (array_key_exists('ini', $val)) {
					$arrForm[$key] = $val['ini'];
				}
				else {
					$arrForm[$key] = '';
				}
			}
		}

		// 入力された取得文字列の変換
		$arrForm = convertFormParams($arrForm);

		// 入力された取得文字列から特定文字列を除去
		$escapeItem = array();
		foreach ($arrFormItem as $key => $val) {
			if (array_key_exists('escape', $val) && $val['escape'] === true) {
				$escapeItem[] = $key;
			}
		}
		$arrForm = removeFormParams($arrForm, $escapeItem);

		// 連続した改行の制御
		if (!empty($arrForm['inquiry'])) {
			$arrForm['inquiry'] = $cl_utils->replaceTextLine($arrForm['inquiry'], 3);
		}

		// フォームデータをセッションへ代入
		$_SESSION[$sessionKey]['params'] = $arrForm;

		return $arrForm;
	}

	/**
	 * 入力された取得文字列の変換処理.
	 *
	 * @param array $_array 入力パラメータ
	 * @return array $arrForm 入力データ
	 */
	function convertFormParams($_array) {
		global $cl_utils, $arrFormItem;

		// ------------------------------
		// 入力フォーム用配列から変換する項目の抽出
		// ------------------------------
		$arr_regist_column = array();
		foreach ($arrFormItem as $key => $val) {
			if (!empty($val['convert'])) {
				$arr_regist_column[] = array(
					'column'	=> $key,
					'convert'	=> $val['convert'],
				);
			}
		}

		$arrForm = $cl_utils->formConvertParam($_array, $arr_regist_column);

		return $arrForm;
	}

	/**
	 * 入力された取得文字列から特定文字列を除去処理.
	 *
	 * @param array $_array 入力パラメータ
	 * @param array $_arrTarget 対象パラメータのキー
	 * @return array $_array 除去後の入力データ
	 */
	function removeFormParams($_array, $_arrTarget) {
		global $cl_utils;

		// ------------------------------
		// 特定文字列を除去（※先頭と末尾の改行・空白も合わせて除去）
		// ------------------------------
		$prohibit_str = array('http://', 'https://', 'ftp://', 'ftps://', 'mailto:', 'javascript:');
		$prohibit_str2 = array('ftp://', 'ftps://', 'mailto:', 'javascript:');
		$tmp_arr_target = array_flip($_arrTarget);
		foreach ($_array as $key => $val) {
			if (array_key_exists($key, $tmp_arr_target)) {
				$_array[$key] = trim(str_replace($prohibit_str, '', $val));
			}
		}

		return $_array;
	}

	/**
	 * エラーチェック処理.
	 *
	 * @param array $_array 入力パラメータ
	 * @return object エラー内容
	 */
	function chkFormError($_array) {
		global $cl_common, $cl_utils, $arrFormItem;
		$objErr = new CL_CheckError($_array);

		foreach ($arrFormItem as $key => $val) {
			if (!empty($val['validate']) && count($val['validate'])) {
// 				if ($key == 'zip01' || $key == 'zip02' || $key == 'addr') {
// 					continue;
// 				}

				$objErr->doFunc(array($val['disp_name'], $key, $val['length']), $val['validate']);
			}
		}

		return $objErr->arrErr;
	}

	/**
	 * メール送信処理.
	 *
	 * @param array $_data 入力パラメータ
	 * @return void
	 */
	function sendMail($_data) {
		if (empty($_data) && !is_array($_data)) return;

		global $cl_common, $cl_sendmail, $arrMasterData;

		// ----- フォーム名設定 -----
		$_data['formname'] = 'contact';

		// ----- 入力データの成形 -----
		// 【お問い合わせ種別】の変換
		$_data['inquirytype'] = $arrMasterData['inquirytypeData'][$_data['inquirytype']];

		// 「<br />」を改行コードに変換
		$_data['inquiry'] = str_replace(array('<br />', '<br>'), "\n", $_data['inquiry']);


		// ------------------------------
		// 入力者宛てのメール送信処理
		// ------------------------------
		// ----- メールアドレスがある場合、送信 -----
		if (!empty($_data['email'])) {
			ob_start();
			include( APP_REALDIR . "templates/{$_data['formname']}/user.php");
			$body_user = ob_get_contents();
			ob_end_clean();

			$mail_data_user = array(
				'from'		=> ENTRY_EMAIL,
				'from_name'	=> ENTRY_EMAIL_FROMNAME,
				'to'		=> $_data['email'],
				'to_name'	=> '', //$_data['name01'],
				'subject'	=> ENTRY_EMAIL_SUBJECT,
				'body'		=> $body_user,
			);
			$cl_sendmail->sendMailEntry($cl_common, $mail_data_user, $_data);
		}

		// ------------------------------
		// 管理者宛てのメール送信処理
		// ------------------------------
		ob_start();
		include( APP_REALDIR . "templates/{$_data['formname']}/admin.php");
		$body_admin = ob_get_contents();
		ob_end_clean();

		if (empty($_data['email'])) {
			// ユーザーのメール入力がない場合、自信のメアドをセット
			$from_email = SYSTEM_MAIL_FROM;
		}
		else {
			$from_email = $_data['email'];
		}

		if (!empty($_data['name01'])) $_data['name01'] = $_data['name01'] . ' 様';

		$mail_data_admin = array(
			'from'		=> $from_email,
			'from_name'	=> $_data['name01'],
			'to'		=> ENTRY_EMAIL,
			'to_name'	=> '',
			'cc'		=> ENTRY_EMAIL_CC,
			'subject'	=> ENTRY_EMAIL_SUBJECT_ADMIN,
			'body'		=> $body_admin,
		);
		$cl_sendmail->sendMailEntryAdmin($cl_common, $mail_data_admin, $_data);
	}

	/**
	 * セッションキーのセット.
	 *
	 * @return string $sskey セッションキー
	 */
	function setSessKey() {
		$sskey = substr(md5(session_id()), 0, 20);
		if (!isset($_SESSION[$sskey])) {
			$_SESSION = array();
			$_POST = array();
			$_SESSION[$sskey] = array();
		}

		return $sskey;
	}

	/**
	 * セッションキーのチェック.
	 *
	 * @return void
	 */
	function chkSessKey() {
		global $sessionKey;

		if (!isset($_SESSION[$sessionKey])) {
			// セッションキーがない場合、フォームTOPへ
			redirectToFormTop();
		}
	}

	/**
	 * フォームTOPへリダイレクト処理.
	 *
	 * @return void
	 */
	function redirectToFormTop() {
		header("Location: " . ENTRY_URL_INPUT);
		exit();
	}

?>