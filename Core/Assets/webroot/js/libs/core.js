;(function($) {
	var Core = $.Core = {};

	/**
	 * get config values.
	 */
	Core.config = function(key) {
		parts = key.split('.');
		var _return = Infinitas.config;

		var done = false;
		$.each(parts, function(k, v) {
			if(!done) {
				if(typeof _return[v] == 'undefined') {
					_return = null;
					return;
				}

				if(typeof _return[v] == 'object') {
					_return = _return[v];
				} else if(typeof _return[v] == 'string') {
					_return = _return[v];
					done = true;
				}
			}
		});

		return _return;
	};

	/**
		import files
	*/
	Core.importJs = function(file, metaData, callback) {
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
		if ($.isArray(data)) {
			return 'array';
		}
		else if ($.isEmptyObject(data)){
			return 'emptyObject';
		}
		else if ($.isFunction(data) || (typeof data == 'function')){
			return 'function';
		}
		else if ($.isPlainObject(data)){
			return 'plainObject';
		}
		else if ($.isXMLDoc(data)){
			return 'xmlDoc';
		}

		return typeof data;

		//functions, arrays and plain objects
	}
})(jQuery);


/*
bindWithDelay jQuery plugin
Author: Brian Grinstead
MIT license: http://www.opensource.org/licenses/mit-license.php

http://github.com/bgrins/bindWithDelay
http://briangrinstead.com/files/bindWithDelay

Usage:
	See http://api.jquery.com/bind/
	.bindWithDelay( eventType, [ eventData ], handler(eventObject), timeout, throttle )

Examples:
	$("#foo").bindWithDelay("click", function(e) { }, 100);
	$(window).bindWithDelay("resize", { optional: "eventData" }, callback, 1000);
	$(window).bindWithDelay("resize", callback, 1000, true);
*/

(function($) {
	$.fn.bindWithDelay = function( type, data, fn, timeout, throttle ) {

		if ( $.isFunction( data ) ) {
			throttle = timeout;
			timeout = fn;
			fn = data;
			data = undefined;
		}

		// Allow delayed function to be removed with fn in unbind function
		fn.guid = fn.guid || ($.guid && $.guid++);

		// Bind each separately so that each element has its own delay
		return this.each(function() {

			var wait = null;

			function cb() {
				var e = $.extend(true, { }, arguments[0]);
				var ctx = this;
				var throttler = function() {
					wait = null;
					fn.apply(ctx, [e]);
				};

				if (!throttle) { clearTimeout(wait); wait = null; }
				if (!wait) { wait = setTimeout(throttler, timeout); }
			}

			cb.guid = fn.guid;

			$(this).bind(type, data, cb);
		});


	}
})(jQuery);