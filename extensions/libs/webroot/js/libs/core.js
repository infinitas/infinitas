(function($) {
	var Core = $.Core = {};

	/**
	 * get config values.
	 */
	Core.config = function(key) {
		parts = key.split('.');
		if (parts.length == 1) {
			return Infinitas.config[key];
		}
		else if (parts.length == 2) {
			return Infinitas.config[parts[0]][parts[1]];
		}
		else if (parts.length == 3) {
			return Infinitas.config[parts[0]][parts[1]][parts[2]];
		}
	};

	/**
		import files
	*/
	Core.import = function(file, metaData, callback) {
		require(
		[
			"require",
			file
		],
		function(require) {
			callback(metaData);
		});
	};

	/**
		get that var type
	*/
	Core.type = function(data){
		if (jQuery.isArray(data)) {
			return 'array';
		}
		else if (jQuery.isEmptyObject(data)){
			return 'emptyObject';
		}
		else if (jQuery.isFunction(data) || typeof data == 'function'){
			return 'function';
		}
		else if (jQuery.isPlainObject(data)){
			return 'plainObject';
		}
		else if (jQuery.isXMLDoc(data)){
			return 'xmlDoc';
		}

		return typeof data;

		//functions, arrays and plain objects
	}
})(jQuery);