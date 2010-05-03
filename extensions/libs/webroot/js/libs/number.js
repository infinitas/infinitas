(function($) {
	var NumberHelper = $.NumberHelper = {};

	/**
	 * Formats a number into a currency format.
	 *
	 * ### Options
	 *
	 * - `before` - The currency symbol to place before whole numbers ie. '$'
	 * - `after` - The currency symbol to place after decimal numbers ie. 'c'.
	 * - `zero` - The text to use for zero values, can be a string or a number. ie. 0, 'Free!'
	 * - `numberOfDecimals` - Number of decimal places to use. ie. 2
	 * - `thousandSeparator` - Thousands separator ie. ','
	 * - `decimalSeparator` - Decimal separator symbol ie. '.'
	 * - `negative` - Symbol for negative numbers. If equal to '()', the number will be wrapped with ( and )
	 *
	 * @param float number the number to convert to currency
	 * @param object the params to pass
	 *
	 * return string the zero string or the currecy converted string.
	 */
	NumberHelper.currency = function(number, params){
		var sDefaults = {
			numberOfDecimals: 2,
			decimalSeparator: '.',
			thousandSeparator: ',',
			symbol: '',
			before: $.Core.config('Currency.unit'),
			after: $.Core.config('Currency.unit_after'),
			zero: 'Free!',
			negative: '()'
		}

		var options = jQuery.extend(sDefaults, params);

		number = $.NumberHelper.format(number, params);

		switch(number) {
			case number == 0:
				return options.zero;
				break;

			default:
					number = options.before + number + options.after;
					switch(number) {
						case number < 0:
							if(options.negative == '()') {
								return '(' + number + ')';
							}
							break;

						default:
							return number;
							break;
					}
				break;
		}

		return $.NumberHelper.format(number, params);
	}

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

	/**
	 * format a number.
	 *
	 * format numbers setting the number of decimal places and the seperators.
	 *
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