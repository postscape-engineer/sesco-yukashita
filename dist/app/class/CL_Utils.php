<?php
/**
 * 各種ユーティリティクラス.
 *
 */
class CL_Utils {

	/**
	 * PHPのヒアドキュメントで定数利用.
	 *
	 * @param	string	$_constant	定数名
	 * @return	string	定数名
	 */
	public function defineParse($_constant){
		return constant($_constant);
	}



/***********************************
	Form関連
***********************************/
	/**
	 * 取得文字列の変換.
	 *
	 * @param	$_array
	 * @param	$_arrRegistColumn
	 * @return	array
	 */
	public function formConvertParam($_array, $_arrRegistColumn) {
		/*
		 *	文字列の変換
		 *	K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
		 *	C :  「全角ひら仮名」を「全角かた仮名」に変換
		 *	V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
		 *	n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
		 *	a :  全角英数字を半角英数字に変換する
		 */
		// カラム名とコンバート情報
		foreach ($_arrRegistColumn as $data) {
			$arrConvList[ $data["column"] ] = $data["convert"];
		}
		// 文字変換
		foreach ($arrConvList as $key => $val) {
			// POSTされてきた値のみ変換する.
			if (isset($_array[$key]) && strlen($_array[$key]) > 0) {
				$_array[$key] = mb_convert_kana($_array[$key], $val, "utf-8");
			}
		}
		return $_array;
	}

	/**
	 * 郵便番号の補正.
	 *
	 * @param	string	$_param		郵便番号
	 * @return	array	$arrZip		分割後の郵便番号
	 */
	public function fixZip($_param) {

		// ハイフンなしは補正
		if (preg_match("/^\d{7}$/", $_param)) {
			$_param = preg_replace("/^(\d{3})(\d{4})$/", "$1-$2", $_param);
		}

		// ハイフンで分割
		if (preg_match("/([0-9]{3})-([0-9]{4})/", $_param, $matches)) {
			$arrZip[1] = @$matches[1];
			$arrZip[2] = @$matches[2];
		}
		else {
			$arrZip[1] = '000';
			$arrZip[2] = '0000';
		}

		return $arrZip;
	}

	/**
	 * 文字列から改行を除去.
	 *
	 * @param	string	$_param		文字列
	 * @return	string	改行除去後の文字列
	 */
	public function trimLine($_param) {
		return preg_replace("/<.*>|\r\n|\s/", "　", $_param);
	}

	/**
	 * 連続した改行の制御.
	 * 　・n個以上の連続した改行はn個へ
	 * 　・先頭と末尾の改行・空白は除去
	 *
	 * @param string	$_param
	 * @param number	$_num
	 * @param string	$_offset
	 * @return string	改行の制御後の文字列
	 */
	public function replaceTextLine($_param, $_num=3, $_offset="\n\n") {
		return trim(preg_replace("/(\r\n){".$_num.",}|\r{".$_num.",}|\n{".$_num.",}/", $_offset, $_param));
	}

	/**
	 * メールアドレスをエンティティ化.
	 *
	 * @param	string	$_email		メールアドレス
	 * @return	string				エンティティ化したのメールアドレス
	 */
	function converterEmail($_email) {
		return mb_encode_numericentity($_email, array(0x0000, 0xffff, 0, 0xffff), 'UTF-8');
	}

}
?>