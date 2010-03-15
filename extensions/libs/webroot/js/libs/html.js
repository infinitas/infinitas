/*(function($) {
	$.fn.html = function(options) {
		var opts = $.extend({}, $.fn.html.defaults, options);
		alert('abc');
		// the plugin functionality goes in here
	}

	$.fn.html.defaults = {
	}
})(jQuery);*/

(function($) {
	//
	// plugin definition
	//
	$.fn.HtmlHelper = function(options) {
		// build main options before element iteration
		var opts = $.extend({}, $.fn.HtmlHelper.defaults, options);
		// iterate and reformat each matched element
		return this.each(function() {
			$this = $(this);
			alert('yey');
		});
	};

	/*
		convert array to url
	*/
	$.fn.HtmlHelper.url = function(options) {
		var opts = $.extend({}, $.fn.HtmlHelper.url.defaults, options);
		var seperator = '/';
		var returnUrl = [];
		$.each(opts, function(key, value){
			if (key == 'action') {
				opts[key] = value;
			}
			else{
				opts[key] = value + '/';
			}

			if (opts[key] == '/') {
				opts[key] = '';
			}
		});

		returnUrl = Infinitas.base + opts.prefix + opts.plugin + opts.controller + opts.action;

		return returnUrl + '/.json';
	};

	$.fn.HtmlHelper.url.defaults = {
		prefix: Infinitas.params.prefix,
		plugin: Infinitas.params.plugin,
		controller: Infinitas.params.controller,
		action: Infinitas.params.action
	};

	//
	// define and expose our format function
	//
	$.fn.HtmlHelper.requestAction = function(metaData) {
		var getUrl    = $.fn.HtmlHelper.url(metaData.url);
		$.getJSON(
			getUrl,
			{param: metaData.param},
			function(returnData) {
				if(returnData !== null) {
					return returnData;
				}
				return false;
			}
		);
	};

	//
	// plugin defaults
	//
	$.fn.HtmlHelper.defaults = {
	};
})(jQuery);
$Html = $.fn.HtmlHelper;

$(document).ready(function(){
	$('.json').change(function(){
		if ($(this).val().length != 0) {
			metaData = $('#' + $(this).attr("id")).metadata();
			data = $Html.requestAction(metaData);
			console.log(data);
			//$Html.input(data);
		}
	});
});