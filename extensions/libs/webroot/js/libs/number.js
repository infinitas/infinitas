/*
* @Copyright (c) 2010 Ricardo Andrietta Mendes - eng.rmendes@gmail.com
*
* How to use it:
* var formated_value = $().number_format(final_value);
*
* Advanced:
* var formated_value = $().number_format(final_value, {
* 	numberOfDecimals:3,
* 	decimalSeparator: '.',
* 	thousandSeparator: ',',
* 	symbol: 'R$'
* });
*/
(function($) {
	var NumberHelper = $.NumberHelper = {};

	/**
	 * Returns a formatted-for-humans file size.
	 *
	 * @param integer $length Size in bytes
	 * @return string Human readable size
	 * @access public
	 */
	NumberHelper.toReadableSize = function (size) {
		switch (true) {
			case size < 1024:
				return size + "Bytes";

			case (size / 1024) < 1024:
				return $.NumberHelper.format(size / 1024, {numberOfDecimals: 0}) + " KB";

			case (size / 1024 / 1024) < 1024:
				return $.NumberHelper.format(size / 1024 / 1024, {numberOfDecimals: 2}) + " MB";

			case (size / 1024 / 1024 / 1024) < 1024:
				return $.NumberHelper.format(size / 1024 / 1024 / 1024, {numberOfDecimals: 2}) + " GB";

			default:
				return $.NumberHelper.format(size / 1024 / 1024 / 1024 / 1024, {numberOfDecimals: 2}) + " TB";
		}
	}

	/**
	 * Formats a number into a percentage string.
	 *
	 * @param float number A floating point number
	 * @param integer precision The precision of the returned number
	 * @return string Percentage string
	 */
	NumberHelper.toPercentage = function(number, precision) {
		precision = $.Core.type(precision) != 'undefined' ? precision : 2;

		return NumberHelper.format(number, {numberOfDecimals: precision}) + '%';
	}

	NumberHelper.format = function(numero, params){
		var sDefaults = {
			numberOfDecimals: 2,
			decimalSeparator: '.',
			thousandSeparator: ',',
			symbol: ''
		}

		var options = jQuery.extend(sDefaults, params);

		var number = numero;
		var decimals = options.numberOfDecimals;
		var dec_point = options.decimalSeparator;
		var thousands_sep = options.thousandSeparator;
		var currencySymbol = options.symbol;

		var exponent = "";
		var numberstr = number.toString ();
		var eindex = numberstr.indexOf ("e");

		if (eindex > -1){
			exponent = numberstr.substring (eindex);
			number = parseFloat (numberstr.substring (0, eindex));
		}

		if (decimals != null){
			var temp = Math.pow (10, decimals);
			number = Math.round (number * temp) / temp;
		}

		var sign = number < 0 ? "-" : "";
		var integer = (number > 0 ?
			Math.floor (number) :
			Math.abs (Math.ceil (number))).toString ();

		var fractional = number.toString ().substring (integer.length + sign.length);
		dec_point  = dec_point != null ? dec_point : ".";
		fractional = ((decimals != null) && (decimals > 0)) || (fractional.length > 1) ? (dec_point + fractional.substring (1)) : "";

		if ((decimals != null) && (decimals > 0)){
			for (i = fractional.length - 1, z = decimals; i < z; ++i){
				fractional += "0";
			}
		}

		thousands_sep = ((thousands_sep != dec_point) || (fractional.length == 0)) ? thousands_sep : null;

		if ((thousands_sep != null) && (thousands_sep != "")){
			for (i = integer.length - 3; i > 0; i -= 3){
				integer = integer.substring (0 , i) + thousands_sep + integer.substring (i);
			}
		}

		if (options.symbol == ''){
			return sign + integer + fractional + exponent;
		}

		else{
			return currencySymbol + ' ' + sign + integer + fractional + exponent;
		}
	}
})(jQuery);