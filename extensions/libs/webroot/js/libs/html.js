(function($) {
	var HtmlHelper = $.HtmlHelper = {};

	/**
		convert array to url
	*/
	HtmlHelper.url = function(options) {
		var opts = $.extend({}, HtmlHelper.url.defaults, options.url);
		var seperator = '/';
		var returnUrl = [];

		var parts = [];

		returnUrl = Infinitas.base + [
			opts.prefix,
			opts.plugin,
			opts.controller,
			opts.action
		].join('/');

		debug(options.params);
		end = '/';
		$.each(options.params, function(key, value){
			end += key + ':' + value + '/';
		});

		return returnUrl + end;
	};

	HtmlHelper.url.defaults = {
		prefix: Infinitas.params.prefix,
		plugin: Infinitas.params.plugin,
		controller: Infinitas.params.controller,
		action: Infinitas.params.action
	};

	HtmlHelper.getParams = function(that){
		metaData = $('#' + that.attr("id")).metadata();
		metaData.params = {};

		return metaData;
	}

	/**
	 * get data from a controller.
	 *
	 * Requests data from the server and when the data is not
	 */
	HtmlHelper.requestAction = function(metaData, callback) {
		var getUrl = HtmlHelper.url(metaData);
		$.getJSON(
			getUrl + metaData.param + '.json',
			function(returnData) {
				if(returnData !== null) {
					callback(returnData['json'], metaData);
				}
			}
		);
	};
})(jQuery);