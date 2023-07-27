<?php
/**
 * エラーチェッククラス.
 *
 */
class CL_CheckError {
	var $arrErr = array();
	var $arrParam;

	/**
	 * コンストラクタ
	 */
	public function __construct($array = "") {
		if($array != "") {
			$this->arrParam = $array;
		} else {
			$this->arrParam = $_POST;
		}
	}

	function doFunc($value, $arrFunc) {
		foreach ( $arrFunc as $key ) {
			$this->$key($value);
		}
	}

	/**
	 * 必須入力の判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象
	 * @return void
	 */
	function EXIST_CHECK( $value ) {
		if (isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		if (!is_array($this->arrParam[$value[1]]) && strlen($this->arrParam[$value[1]]) == 0 ){
			$this->arrErr[$value[1]] = "※ " . $value[0] . "をご入力ください。<br />";
		} else if (is_array($this->arrParam[$value[1]]) && count($this->arrParam[$value[1]]) == 0) {
			$this->arrErr[$value[1]] = "※ " . $value[0] . "を選択してください。<br />";
		}
	}

	/**
	 * スペース、タブの判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象
	 * @return void
	 */
	function SPTAB_CHECK( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		if(strlen($this->arrParam[$value[1]]) != 0 && preg_match("/^[ 　\t\r\n]+$/", $this->arrParam[$value[1]])){
			$this->arrErr[$value[1]] = "※ " . $value[0] . "にスペース、タブ、改行のみの入力はできません。<br />";
		}
	}

	/**
	 * スペース、タブの判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象
	 * @return void
	 */
	function NO_SPTAB( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		if(strlen($this->arrParam[$value[1]]) != 0 && preg_match("/[　 \t\r\n]+/u", $this->arrParam[$value[1]])){
			$this->arrErr[$value[1]] = "※ " . $value[0] . "にスペース、タブ、改行は含めないでください。<br />";
		}
	}

	/**
	 * スペースの判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象
	 * @return void
	 */
	function NO_SP( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		if(strlen($this->arrParam[$value[1]]) != 0 && preg_match("/[\t\r\n]+/u", $this->arrParam[$value[1]])){
			$this->arrErr[$value[1]] = "※ " . $value[0] . "にタブ、改行は含めないでください。<br />";
		}
	}

	/**
	 * 必須選択の判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象
	 * @return void
	 */
	function SELECT_CHECK( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		if( strlen($this->arrParam[$value[1]]) == 0 ){
			$this->arrErr[$value[1]] = "※ " . $value[0] . "を選択してください。<br />";
		}
	}

	/**
	 * 最大文字数制限の判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象文字列  value[2] = 最大文字数(半角も全角も1文字として数える)
	 * @return void
	 */
	function MAX_LENGTH_CHECK( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		// 文字数の取得
		if( mb_strlen($this->arrParam[$value[1]]) > $value[2] ) {
			$this->arrErr[$value[1]] = "※ " . $value[0] . "は" . $value[2] . "字以下で入力してください。<br />";
		}
	}

	/**
	 * 最小文字数制限の判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象文字列 value[2] = 最小文字数(半角も全角も1文字として数える)
	 * @return void
	 */
	function MIN_LENGTH_CHECK( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		// 文字数の取得
		if( mb_strlen($this->arrParam[$value[1]]) < $value[2] ) {
			$this->arrErr[$value[1]] = "※ " . $value[0] . "は" . $value[2] . "字以上で入力してください。<br />";
		}
	}

	/**
	 * 数字の判定.
	 *
	 * @param array $value value[0] = 項目名 value[1] = 判定対象文字列
	 * @return void
	 */
	function NUM_CHECK( $value ) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);
		if ( $this->numelicCheck($this->arrParam[$value[1]]) ) {
			$this->arrErr[$value[1]] = "※ " . $value[0] . "は数字で入力してください。<br />";
		}
	}

	/**
	 * メールアドレス形式の判定.
	 *
	 * @param array value[0] = 項目名 value[1] = 判定対象メールアドレス
	 * @return void
	 */
	function EMAIL_CHECK( $value ){
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);

		$wsp				= '[\x20\x09]';
		$vchar				= '[\x21-\x7e]';
		$quoted_pair		= "\\\\(?:$vchar|$wsp)";
		$qtext				= '[\x21\x23-\x5b\x5d-\x7e]';
		$qcontent			= "(?:$qtext|$quoted_pair)";
		$quoted_string		= "\"$qcontent*\"";
		$atext				= '[a-zA-Z0-9!#$%&\'*+\-\/\=?^_`{|}~]';
		$dot_atom_text		= "$atext+(?:[.]$atext+)*";
		$dot_atom			= $dot_atom_text;
		$local_part			= "(?:$dot_atom|$quoted_string)";
		$domain				= $dot_atom;
		$addr_spec			= "${local_part}[@]$domain";

		$dot_atom_loose		= "$atext+(?:[.]|$atext)*";
		$local_part_loose	= "(?:$dot_atom_loose|$quoted_string)";
		$addr_spec_loose	= "${local_part_loose}[@]$domain";

		$regexp = "/\A${addr_spec_loose}\z/";

		if(strlen($this->arrParam[$value[1]]) > 0 && !preg_match($regexp, $this->arrParam[$value[1]])) {
			$this->arrErr[$value[1]] = "※ " . $value[0] . "の形式が不正です。<br />";
		}
	}

	/**
	 * 日付チェック.
	 *
	 * @param array value[0] = 項目名 value[1] = YYYY value[2] = MM value[3] = DD
	 * @return void
	 */
	function CHECK_DATE($value) {
		if(isset($this->arrErr[$value[1]])) {
			return;
		}
		$this->createParam($value);

		if ($this->arrParam[$value[1]] > 0 || $this->arrParam[$value[2]] > 0 || $this->arrParam[$value[3]] > 0) {
			if (!(strlen($this->arrParam[$value[1]]) > 0 && strlen($this->arrParam[$value[2]]) > 0 && strlen($this->arrParam[$value[3]]) > 0)) {
				$this->arrErr[$value[1]] = "※ " . $value[0] . "はすべての項目を選択してください。<br />";
			} else if ( ! checkdate($this->arrParam[$value[2]], $this->arrParam[$value[3]], $this->arrParam[$value[1]])) {
				$this->arrErr[$value[1]] = "※ " . $value[0] . "が正しくありません。<br />";
			}
		}
	}

	/**
	 * 未定義の $this->arrParam に空要素を代入.
	 *
	 * @access private
	 * @param array $value 配列
	 * @return void
	 */
	function createParam($value) {
		foreach ($value as $key) {
			if (is_string($key) || is_int($key)) {
				if (!isset($this->arrParam[$key]))  $this->arrParam[$key] = "";
			}
		}
	}

	/**
	 * 値が数字のみかチェック.
	 *
	 * @access private
	 * @param string $string チェックする文字列
	 * @return boolean 値が10進数の数値表現のみ true
	 */
	function numelicCheck($string) {
		$string = (string) $string;
		return strlen($string) > 0 && !ctype_digit($string);
	}
}
?>
