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
	 * Loading image for ajax.
	 *
	 * This can create and detroy a loading image depending on what is passed.
	 *
	 * @param string target the target where the loading icon will be displayed or removed from.
	 * @param bool status if it should create or destroy the icon.
	 */
	HtmlHelper.loading = function(target, create){
		if (!create) {
			$('#' + target).next().remove();
		}
		else{
			$('#' + target).after('<img class="loadingImage" src="' + Infinitas.base + 'img/core/icons/notifications/loading.gif" alt="loading" width="16px"/>');
		}
	}

	/**
	 * get data from a controller.
	 *
	 * Requests data from the server and when the data is not
	 */
	HtmlHelper.requestAction = function(metaData, callback) {
		HtmlHelper.loading(metaData.target, true);
		var getUrl = HtmlHelper.url(metaData);
		$.getJSON(
			getUrl + metaData.param + '.json',
			function(returnData) {
				if(returnData !== null) {
					callback(returnData['json'], metaData);
					HtmlHelper.loading(metaData.target, false);
				}
			}
		);
	};
})(jQuery);