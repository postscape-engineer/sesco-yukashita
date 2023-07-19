/**
 * Validation
 */

var fncCheckSubmit = function(mode) {
	if( mode ) {
		var frm = '#form';
		$('<input type="hidden" id="mode" name="mode" value="">').prependTo(frm);
		$('#mode').val(mode);
		$(frm).submit();
		return true;
	}
	return false;
}

$(function(){

	if ($('#page-input').length) {
		var minaFormValidation = new ValidationClass('#form', '#btn-confirm', '#accept');
		minaFormValidation.init();

//		if (_moveform) {
//			$(window).on('load', function(event) {
//				var _pos = $('#contact').offset().top + 1;
//				$('html,body').animate({scrollTop: _pos}, 0, 'swing');
//			});
//		}
	}

	if ($('#page-confirm').length) {
		$('#btn-back').on('click', function() {
			fncCheckSubmit('return');
			return false;
		});
		$('#btn-send').on('click', function() {
			fncCheckSubmit('complete');
			return false;
		});
	}

});

ValidationClass = (function() {

	function ValidationClass(formID, btnSubmitID, acceptID){

		this.targetID = formID;
		this.btnSubmit = btnSubmitID;
		this.accept = acceptID;

		this.SCROLL_TOP_MARGIN = 200;
		this.BALLOON_TOP_MARGIN = 30;
		this.BALOON_LEFT_MARGIN = -10;

		this.REQUIRED_ERR_MSG = '入力が必要です。';
		this.SELECT_ERR_MSG = '選択してください。';
		this.CHECK_ERR_MSG = 'チェックしてください。';
		this.NUMERIC_ERR_MSG = '入力は数字です。';
		this.KATAKANA_ERR_MSG = 'カタカナで入力してください。';
		this.HIRAGANA_ERR_MSG = 'ひらがなで入力してください。';
		this.PHONE_ERR_MSG = '半角数字のみ10～11桁で入力してください。';
		this.ZIP_ERR_MSG = '半角数字のみ7桁で入力してください。';
		this.EMAIL_ERR_MSG = 'メールアドレスの形式が正しくありません。';
		this.PASSWORD_ERR_MSG = '半角英数字で入力してください。';
		this.URL_ERR_MSG = 'URLの形式が正しくありません。'

	};

	ValidationClass.prototype.disabled = function() {

		var self = this;

		//$(self.btnSubmit).attr('disabled', true);

		$(self.accept).click(function() {
			if ($(this).prop('checked') == true) {
				$(self.btnSubmit).removeAttr('disabled').addClass('true');
			} else {
				$(self.btnSubmit).attr('disabled', true).removeClass('true');
			}
		});

	};

	ValidationClass.prototype.init = function() {

		var self = this;

		$.each(
			$('form input, form textarea'),
			function(i){
				if ( this.type == 'checkbox' || this.type == 'hidden' ){
					return true;
				};
				var _valid = $(this).attr('validation');

				if (typeof _valid != 'undefined' && _valid != '' ||
					this.id.indexOf('name',0) == 0 || this.id == 'address' )
				{
					self.checkInputValueRequired(this, _valid);
				}
			}
		);

		self.disabled();
		self.selectbox();
		self.radio();
		self.checkbox();
		self.submit();

	};

	ValidationClass.prototype.checkInputValueRequired = function(target, validation) {

		var self = this;

		var _validation = validation === undefined ? '' : validation;

		$(target).on('change blur', function(){
			var val = $(this).val();
			var error_msg = '';

			if ( val == '' ){
				//値が空白の時そのバリデーションが、カタカナか任意入力以外の時にエラーメッセージ
				if ( _validation == 'hiragana' || _validation == 'optional' || _validation == 'zip' ){
				}
				else {
					error_msg = self.REQUIRED_ERR_MSG;
				}
			} else {
				switch (_validation) {
					case 'katakana':
						//フォームにカタカナ以外が記入されている時
						if ( !$(this).val().match(/^[ァ-ヶ 　ー]+$/) ){
							error_msg = self.KATAKANA_ERR_MSG;
						}
						break;
					case 'hiragana':
						//フォームにひらがな以外が記入されている時
						if ( !$(this).val().match(/^[ぁ-ん 　ー]+$/) ){
							error_msg = self.HIRAGANA_ERR_MSG;
						}
						break;
					case 'number':
						//フォームに半角全角数字以外が記入されている時
						if ( !$(this).val().match(/^([0-9０-９])+$/) ){
							error_msg = self.NUMERIC_ERR_MSG;
						}
						break;
					case 'tel':
						//フォームに記入された値を「半角数字」、「ハイフンなし」にして書き換える。
						$hankaku = $(this).val().replace(/[０-９]/g, function (s) {return String.fromCharCode(s.charCodeAt(0) - 65248);}).replace(/[‐－―ー-]/g, '').replace(/[^\d\-]/g, '');;
						$(this).val($hankaku);
						//0~9の10,11文字かどうか
						if ( !$(this).val().match(/^([0-9]{10,11})+$/) ){
							error_msg = self.PHONE_ERR_MSG;
						}
						break;
					case 'zip':
						//フォームに記入された値を「半角数字」、「ハイフンなし」にして書き換える。
						$hankaku = $(this).val().replace(/[０-９]/g, function (s) {return String.fromCharCode(s.charCodeAt(0) - 65248);}).replace(/[‐－―ー-]/g, '').replace(/[^\d\-]/g, '');;
						$(this).val($hankaku);
						//0~9の7文字かどうか
						if ( !$(this).val().match(/^([0-9]{7})+$/) ){
							error_msg = self.ZIP_ERR_MSG;
						}
						break;
					case 'email':
						// http://www.codeproject.com/Tips/492632/Email-Validation-in-JavaScript
						//◯◯◯@◯◯◯.◯◯の形になっているかの確認
						var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
						if ( !$(this).val().match(filter) ){
							error_msg = self.EMAIL_ERR_MSG;
						} else {
							//今現在のtargetがconfirm(確認用)の場合
							if (target.id == 'e_mail_confirm'){
								//メールアドレスとメールアドレス確認用が同じ値かを判定
								if ( $('#e_mail').val() !== $('#e_mail_confirm').val() ) {
									error_msg += 'メールアドレス欄と同じアドレスを入力してください。';
								} else {
									error_msg = '';
								}
							}
						}
						break;
					case 'password':
						//半角英数字以外の文字が入っていないか
						if ( $(this).val().match( /[^0-9a-zA-Z_]+/ ) ){
							error_msg = self.PASSWORD_ERR_MSG;
						} else {
							if ( $(this).val().length < 6 ) {
								error_msg = '6文字以上で入力してください。';
							} else {
								if ( $(this).val().length > 12 ) {
									error_msg = '12文字以下で入力してください。';
								} else {
									//パスワードとパスワード確認用が同じ値かを判定
									if (target.id == 'password_confirm'){
										if ( $('#password').val() !== $('#password_confirm').val() ) {
											error_msg += '同じパスワードを入力してください。';
										} else {
											error_msg = '';
										}
									}
								}
							}
						}
						break;
					case 'url':

						var filter = /^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?/;
						if ( !$(this).val().match(filter) ){
							error_msg = self.URL_ERR_MSG;
						}
						break;
				}
			};

			if ($(this).next('p.error-msg-balloon').length > 0){
				$(this).next('p.error-msg-balloon').remove();
			}
			if ($(this).hasClass('error')){
				$(this).removeClass('error');
			}

			var _domloc = $(this).position();

			if (error_msg != '') {
				var _left = _domloc.left - self.BALOON_LEFT_MARGIN;
				var _balloon = $('<p class="error-msg-balloon"><span>' + error_msg + '</span></p>').css({
					'position':'absolute',
					'top': ( _domloc.top - self.BALLOON_TOP_MARGIN ) + 'px',
					'display':'none',
					'left': _left + 'px'
				});
				if ($(this).next('.error-msg-balloon').length > 0) {
					$(this).next('.error-msg-balloon').remove();
				}

				$(this).addClass('error');
				$(this).after(_balloon);
				_balloon.show();
			};
		});

	};

	ValidationClass.prototype.selectbox = function(form) {

		var self = this;

		$('form select.require-select').on('blur', function(e){
			if ( $(this).val() == '') {
				var error_msg = self.SELECT_ERR_MSG;
				var _domloc = $(this).parent().position();
				var _balloon = $('<p class="error-msg-balloon"><span>' + error_msg + '</span></p>').css({
					'position':'absolute',
					'top': ( _domloc.top - self.BALLOON_TOP_MARGIN ) + 'px',
					'left': ( _domloc.left - self.BALOON_LEFT_MARGIN ) + 'px'
				});

				if ($(this).parent().parent().find('.error-msg-balloon').length > 0){
					$(this).parent().parent().find('.error-msg-balloon').remove();
				}

				$(this).addClass('error');
				$(this).parent().after(_balloon);

			} else {
				if ($(this).parent().parent().find('p.error-msg-balloon').length > 0){
					$(this).parent().parent().find('p.error-msg-balloon').remove();
				}
				if ($(this).hasClass('error')){
					$(this).removeClass('error');
				}
			}
		});

	};

	ValidationClass.prototype.radio = function(form) {

		var self = this;

		$('form .require-radio input[type=radio]').on('change blur', function(e){
			var elemName = $(this).attr('name');
			var elemParentId = $(this).closest('.require-radio').attr('id');

			if ( !$(':radio[name="'+elemName+'"]:checked').val() ) {
				var error_msg = self.CHECK_ERR_MSG;
				var _domloc = $('#'+elemParentId).position();
				var _balloon = $('<p class="error-msg-balloon"><span>' + error_msg + '</span></p>').css({
					'position':'absolute',
					'top': ( _domloc.top - self.BALLOON_TOP_MARGIN ) + 'px',
					'left': ( _domloc.left - self.BALOON_LEFT_MARGIN ) + 'px'
				});

				if ($('#'+elemParentId).find('.error-msg-balloon').length > 0){
					$('#'+elemParentId).find('.error-msg-balloon').remove();
				}

				$('#'+elemParentId).addClass('error');
				$('#'+elemParentId).append(_balloon);

			} else {
				if ($('#'+elemParentId).find('p.error-msg-balloon').length > 0){
					$('#'+elemParentId).find('p.error-msg-balloon').remove();
				}
				if ($('#'+elemParentId).hasClass('error')){
					$('#'+elemParentId).removeClass('error');
				}
			}
		});

	};

	ValidationClass.prototype.checkbox = function(form) {

		var self = this;

		$('form .require-checkbox input[type=checkbox]').on('change blur', function(e){
			var errFlg = false;
			var elemName = $(this).attr('name');
			var elemParentId = $(this).closest('.require-checkbox').attr('id');
			var cnt = $(':checkbox[name="'+elemName+'"]:checked').length;
			if ( cnt < 1) {
				errFlg = true;
			}

			if ( errFlg ) {
				var error_msg = self.CHECK_ERR_MSG;
				var _domloc = $('#'+elemParentId).position();
				var _balloon = $('<p class="error-msg-balloon"><span>' + error_msg + '</span></p>').css({
					'position':'absolute',
					'top': ( _domloc.top - self.BALLOON_TOP_MARGIN ) + 'px',
					'left': ( _domloc.left - self.BALOON_LEFT_MARGIN ) + 'px'
				});

				if ($('#'+elemParentId).find('.error-msg-balloon').length > 0){
					$('#'+elemParentId).find('.error-msg-balloon').remove();
				}

				$('#'+elemParentId).addClass('error');
				$('#'+elemParentId).append(_balloon);

			} else {
				if ($('#'+elemParentId).find('p.error-msg-balloon').length > 0){
					$('#'+elemParentId).find('p.error-msg-balloon').remove();
				}
				if ($('#'+elemParentId).hasClass('error')){
					$('#'+elemParentId).removeClass('error');
				}
			}
		});

	};

	ValidationClass.prototype.submit = function() {

		var self = this;

		$(self.targetID).submit(function(e){

			var hasError = false;
			var firstErrorPosition = 0;

			$(self.targetID + " input, " + self.targetID + " textarea, " + self.targetID + " select").blur();

			//$('form select, form input, form textarea').each(
			$('form select, form input, form textarea, #category-list, #document-list').each(
				function(i){
					hasError = $(this).hasClass('error');
					if (hasError){
						firstErrorPosition = $(this).offset().top - self.SCROLL_TOP_MARGIN;
						return false;
					}
				}
			);

			if (hasError){
				var ScrollSpeed = 1000;
				$('body,html').animate({scrollTop: firstErrorPosition}, ScrollSpeed, 'swing');
				return false;
			} else {
				$('<input type="hidden" id="mode" name="mode" value="">').prependTo(self.targetID);
				$('#mode').val('confirm');
				return true;
			}
		});
	};

	return ValidationClass;

})();