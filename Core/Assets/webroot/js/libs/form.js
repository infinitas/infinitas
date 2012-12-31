;(function($) {
	var FormHelper = $.FormHelper = {};

	/**
	 * Focus on the first available text field, used in admin to make the
	 * overall usability a bit better.
	 *
	 * @access public
	 * @return void
	 **/
	FormHelper.foucusOnFirst = function(){
		$("input:text:visible:first").focus();
	}

	/**
	 * generate a form input
	 */
	FormHelper.input = function(data, metaData) {
		$('#' + metaData.target).empty();
		if ($.Core.type(data) == 'string') {
		}

		$.FormHelper.select(data, metaData);
	};

	/**
	 * generate a select dropdown
	 */
	FormHelper.select = function(data, metaData) {
		var options = '<option value="">' + $.Core.config('Website.empty_select') + '</option>';
		$.each(data, function(index, name) {
			if(($.Core.type(name) == 'plainObject') || ($.Core.type(name) == 'array')) {
				options += '<optgroup label="' + index + '">';
				$.each(name, function(sub_index, sub_name) {
					options += '<option value="' + sub_index + '">' + sub_name + '</option>';
				});
				options += '</optgroup>';
			}
			else {
				options += '<option value="' + index + '">' + name + '</option>';
			}

		});
		$('#' + metaData.target).empty().html(options);
	};

	/**
	 * generate a select dropdown
	 */
	FormHelper.emptySelect = function(metaData) {
		$('#' + metaData.target).empty();
	};

	/**
	 * toggle checkboxes
	 */
	FormHelper.checkboxToggleAll = function(selector) {
		var tog = false;

		$.each($(selector), function(k, v) {
			$(v).live('click', function() {
				$(this).parents('table')
					.find('input[type="checkbox"]')
					.not($(this))
					.attr("checked", !tog)
					.trigger('change');
				tog = !tog;
			});
		});
	};

	/**
	 * image dropdown
	 */
	FormHelper.imageDropdown = function(fieldId) {
		$("#" + fieldId).msDropDown();
	};

	FormHelper.passwordStrength = function(selector) {
		function passwordScore(pwd) {
			if (pwd.length == 0) {
				return 0;
			}

			var nScore=0, nLength=0, nAlphaUC=0, nAlphaLC=0, nNumber=0, nSymbol=0, nMidChar=0, nUnqChar=0,
				nRepChar=0, nRepInc=0, nConsecAlphaUC=0, nConsecAlphaLC=0, nConsecNumber=0, nConsecSymbol=0,
				nConsecCharType=0, nSeqAlpha=0, nSeqNumber=0, nSeqSymbol=0, nSeqChar=0;
			var nMultMidChar=2, nMultConsecAlphaUC=2, nMultConsecAlphaLC=2, nMultConsecNumber=2;
			var nMultSeqAlpha=3, nMultSeqNumber=3, nMultSeqSymbol=3;
			var nMultLength=4, nMultNumber=4;
			var nMultSymbol=6;
			var nTmpAlphaUC="", nTmpAlphaLC="", nTmpNumber="", nTmpSymbol="";
			var sAlphas = "abcdefghijklmnopqrstuvwxyz";
			var sNumerics = "01234567890";
			var sSymbols = ")!@#$%^&*()";


			nScore = parseInt(pwd.length * nMultLength);
			nLength = pwd.length;
			var arrPwd = pwd.replace(/\s+/g,"").split(/\s*/);
			var arrPwdLen = arrPwd.length;

			for (var a=0; a < arrPwdLen; a++) {
				if (arrPwd[a].match(/[A-Z]/g)) {
					if (nTmpAlphaUC !== "" && (nTmpAlphaUC + 1) == a) {
						nConsecAlphaUC++;
						nConsecCharType++;
					}
					nTmpAlphaUC = a;
					nAlphaUC++;
				} else if (arrPwd[a].match(/[a-z]/g)) {
					if (nTmpAlphaLC !== "" && (nTmpAlphaLC + 1) == a) {
						nConsecAlphaLC++;
						nConsecCharType++;
					}
					nTmpAlphaLC = a;
					nAlphaLC++;
				} else if (arrPwd[a].match(/[0-9]/g)) {
					if (a > 0 && a < (arrPwdLen - 1)) {
						nMidChar++;
					}
					if (nTmpNumber !== "" && (nTmpNumber + 1) == a) {
						nConsecNumber++;
						nConsecCharType++;
					}
					nTmpNumber = a;
					nNumber++;
				} else if (arrPwd[a].match(/[^a-zA-Z0-9_]/g)) {
					if (a > 0 && a < (arrPwdLen - 1)) {
						nMidChar++;
					}
					if (nTmpSymbol !== "" && (nTmpSymbol + 1) == a) {
						nConsecSymbol++;
						nConsecCharType++;
					}
					nTmpSymbol = a;
					nSymbol++;
				}

				var bCharExists = false;
				for (var b = 0; b < arrPwdLen; b++) {
					if (arrPwd[a] == arrPwd[b] && a != b) {
						bCharExists = true;
						nRepInc += Math.abs(arrPwdLen / (b-a));
					}
				}
				if (bCharExists) {
					nRepChar++;
					nUnqChar = arrPwdLen-nRepChar;
					nRepInc = (nUnqChar) ? Math.ceil(nRepInc/nUnqChar) : Math.ceil(nRepInc);
				}
			}

			/* Check for sequential alpha string patterns (forward and reverse) */
			for (var s = 0; s < 23; s++) {
				var sFwd = sAlphas.substring(s,parseInt(s+3));
				var sRev = sFwd.strReverse();
				if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) {
					nSeqAlpha++;
					nSeqChar++;
				}
			}

			/* Check for sequential numeric string patterns (forward and reverse) */
			for (var s = 0; s < 8; s++) {
				var sFwd = sNumerics.substring(s,parseInt(s+3));
				var sRev = sFwd.strReverse();
				if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) {
					nSeqNumber++;
					nSeqChar++;
				}
			}

			for (var s = 0; s < 8; s++) {
				var sFwd = sSymbols.substring(s,parseInt(s+3));
				var sRev = sFwd.strReverse();
				if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) {
					nSeqSymbol++;
					nSeqChar++;
				}
			}

			if (nAlphaUC > 0 && nAlphaUC < nLength) {
				nScore = parseInt(nScore + ((nLength - nAlphaUC) * 2));
			}
			if (nAlphaLC > 0 && nAlphaLC < nLength) {
				nScore = parseInt(nScore + ((nLength - nAlphaLC) * 2));
			}
			if (nNumber > 0 && nNumber < nLength) {
				nScore = parseInt(nScore + (nNumber * nMultNumber));
			}
			if (nSymbol > 0) {
				nScore = parseInt(nScore + (nSymbol * nMultSymbol));
			}
			if (nMidChar > 0) {
				nScore = parseInt(nScore + (nMidChar * nMultMidChar));
			}

			// Only Letters
			if ((nAlphaLC > 0 || nAlphaUC > 0) && nSymbol === 0 && nNumber === 0) {
				nScore = parseInt(nScore - nLength);
			}

			// Only Numbers
			if (nAlphaLC === 0 && nAlphaUC === 0 && nSymbol === 0 && nNumber > 0) {
				nScore = parseInt(nScore - nLength);
			}

			// Same character exists more than once
			if (nRepChar > 0) {
				nScore = parseInt(nScore - nRepInc);
			}

			// Consecutive Uppercase Letters exist
			if (nConsecAlphaUC > 0) {
				nScore = parseInt(nScore - (nConsecAlphaUC * nMultConsecAlphaUC));
			}

			// Consecutive Lowercase Letters exist
			if (nConsecAlphaLC > 0) {
				nScore = parseInt(nScore - (nConsecAlphaLC * nMultConsecAlphaLC));
			}

			// Consecutive Numbers exist
			if (nConsecNumber > 0) {
				nScore = parseInt(nScore - (nConsecNumber * nMultConsecNumber));
			}

			// Sequential alpha strings exist (3 characters or more)
			if (nSeqAlpha > 0) {
				nScore = parseInt(nScore - (nSeqAlpha * nMultSeqAlpha));
			}

			// Sequential numeric strings exist (3 characters or more)
			if (nSeqNumber > 0) {
				nScore = parseInt(nScore - (nSeqNumber * nMultSeqNumber));
			}

			// Sequential symbol strings exist (3 characters or more)
			if (nSeqSymbol > 0) {
				nScore = parseInt(nScore - (nSeqSymbol * nMultSeqSymbol));
			}

			return nScore;
		}

		var element = $(selector),
			bar = '<div class="progress progress-striped active password">' +
				'<div class="bar password-strength" style="width: 0%;"></div>' +
			'</div>';

		element.after(bar);
		element.on('keyup', function() {
			var $this = $(this),
				$bar = $('div.password-strength', $this.parent()),
				score = passwordScore($this.val());

			$bar.removeClass('bar-success')
				.removeClass('bar-warning')
				.removeClass('bar-danger')
				.addClass('bar-danger');
			if (score > 80) {
				$bar.addClass('bar-success');
			} else if (score > 40) {
				$bar.addClass('bar-warning');
			}
			$bar.css('width', score + '%');

			if (!score) {
				$bar.parent().hide();
			} else {
				$bar.parent().show();
			}
		});
	};
})(jQuery);

String.prototype.strReverse = function() {
	var newstring = "";
	for (var s=0; s < this.length; s++) {
		newstring = this.charAt(s) + newstring;
	}
	return newstring;
};