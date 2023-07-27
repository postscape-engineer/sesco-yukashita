<?php
/**
 * Send_mail クラス.
 *
 */
class CL_SendMail {

	var $MAIL;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		// Qdmailをロード
		require_once APP_REALDIR . 'libraries/qdmail.php';
		// Qdsmtpをロード
		require_once APP_REALDIR . 'libraries/qdsmtp.php';

		$this->MAIL = new Qdmail();
	}

	/**
	 * Qdmailを利用してメールを送信処理
	 * □備考：
	 * 複数宛先の配信は未対応
	 *
	 * @param	array	$_data = array(
	 * 	'from'				=> '配信元',
	 * 	'from_name'			=> '配信元日本語名',
	 * 	'to'				=> '宛先',
	 * 	'to_name'			=> '宛先日本語名',
	 * 	'reply_to'			=> '返信先replyto',
	 * 	'reply_to_name'		=> '返信先replyto日本語名',
	 * 	'subject'			=> 'タイトル',
	 * 	'body'				=> '本文',
	 * );
	 * @param	bool	$_html	HTMLメールか
	 *
	 * @return	bool	成功：TRUE／失敗：FALSE
	 */
	private function _sendmail(&$cl_common, $_data = array(), $_html = false) {

		// 半角カナを全角に変換（文字化け対策）
		$_data['from_name']	= mb_convert_kana($_data['from_name'], "KV");
		$_data['to_name']	= mb_convert_kana($_data['to_name'], "KV");
		$_data['body']		= mb_convert_kana($_data['body'], "KV");

		// Qdmail　文字化け対策
		$this->MAIL->charset('UTF-8', 'base64');

		// エラー非表示
		$this->MAIL->errorDisplay(false);

		// ヘッダーに送るべき情報が本文として表示されてしまう問題の対応
		$this->MAIL->lineFeed("\n");

		$this->MAIL->from($_data['from'], $_data['from_name']);
		$this->MAIL->to($_data['to'], $_data['to_name']);
		$this->MAIL->subject($_data['subject']);

		if (!empty($_data['cc'])) {
			if (is_array($_data['cc'])) {
				$this->MAIL->cc($_data['cc']['address'], $_data['cc']['name']);
			}
			else {
				$this->MAIL->cc($_data['cc']);
			}
		}

		if (!empty($_data['bcc'])) {
			if (is_array($_data['bcc'])) {
				$this->MAIL->bcc($_data['bcc']['address'], $_data['bcc']['name']);
			}
			else {
				$this->MAIL->bcc($_data['bcc']);
			}
		}

		if (!empty($_data['reply_to'])) {
			$this->MAIL->replyto($_data['reply_to'], isset($_data['reply_to_name']) ? $_data['reply_to_name'] : null);
		}

		if (!$_html) {
			// テキストメール
			$this->MAIL->text($_data['body']);
		}
		else {
			// HTMLメール利用時は、自動テキスト生成機能をOFFにする
			$this->MAIL->autoBoth(false);
			$this->MAIL->html($_data['body']);
		}

		// メール送信実行
		if (!$this->MAIL->send()){
			// メール送信失敗

			// エラー処理
			$err_message = print_r($this->MAIL->errorStatment(false),true);
			$cl_common->output_log("--Qdmail側のエラー：メール送信に失敗しました --------------------------------\n");
			$cl_common->output_log(date('Y/m/d H:i:s', time()) . "\n");
			$cl_common->output_debug($err_message);

			// 管理者へメールで知らせる場合
// 			@mail('Toアドレス','ErrorQdmail',$err_message,'From: Fromアドレス');

			return false;
		}

		return true;
	}



	/**
	 * 【フォーム】のメール送信処理.（ユーザーへのメール送信）
	 *
	 * @param	object	$cl_common	共通クラス
	 * @param	array	$_mail_data
	 * @param	array	$_data
	 * @return	void
	 */
	function sendMailEntry(&$cl_common, $_mail_data, $_data) {

		if (!@$this->_sendmail($cl_common, $_mail_data)) {
			$agent = $_SERVER['HTTP_USER_AGENT'];
			$cl_common->output_log("-- 【フォーム（{$_data['formname']}）】：入力者へのメール送信に失敗しました --------------------------------\n");
			$cl_common->output_log(date('Y/m/d H:i:s', time()) . "\n");
			$cl_common->output_log($agent . "\n");
			$cl_common->output_debug($_mail_data);
			$cl_common->output_debug($_data);
		}

	}

	/**
	 * 【フォーム】のメール送信処理.（管理者へのメール送信）
	 *
	 * @param	object	$cl_common	共通クラス
	 * @param	array	$_mail_data
	 * @param	array	$_data
	 * @return	void
	 */
	function sendMailEntryAdmin(&$cl_common, $_mail_data, $_data) {

		// 成功、失敗問わずログ出力
		if (!@$this->_sendmail($cl_common, $_mail_data)) {
			$cl_common->output_log("-- 【フォーム（{$_data['formname']}）】：管理者へのメール送信に失敗しました --------------------------------\n");
		}
		else {
			$cl_common->output_log("-- 【フォーム（{$_data['formname']}）】：管理者へのメール送信に成功しました --------------------------------\n");
		}

		$agent = $_SERVER['HTTP_USER_AGENT'];
		$cl_common->output_log(date('Y/m/d H:i:s', time()) . "\n");
		$cl_common->output_log($agent . "\n");
		$cl_common->output_debug($_mail_data);
		$cl_common->output_debug($_data);

	}

}
?>