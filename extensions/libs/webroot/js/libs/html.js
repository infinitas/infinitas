(function($) {
	var HtmlHelper = $.HtmlHelper = {};

	/**
		convert array to url
	*/
	HtmlHelper.url = function(options) {
		var opts = $.extend({}, HtmlHelper.url.defaults, options.url);
		var returnUrl = [];

		returnUrl = Infinitas.base + [
			opts.prefix,
			opts.plugin,
			opts.controller,
			opts.action
		].join('/');

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
	 * Remove cols from a table.
	 */
	HtmlHelper.removeCol = function(col){
	    if(!col){
	    	return false;
	    }

	    pr(col);

	    $('tr td:eq('+col+'), tr th:eq('+col+')').hide();
	    return this;
	};

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
			$('.loadingImage').remove();
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

	HtmlHelper.slideshow = function(selector, callback){
		var currentPosition = 0;
		var slideWidth = 450;
		var slides = $('.slide');
		var numberOfSlides = slides.length;

		// manageControls: Hides and Shows controls depending on currentPosition
		function manageControls(position){
			// Hide left arrow if position is first slide
			if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
			// Hide right arrow if position is last slide
			if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show() }
		}

		// Remove scrollbar in JS
		$('#slidesContainer').css('overflow', 'hidden');

		// Wrap all .slides with #slideInner div
		slides.wrapAll('<div id="slideInner"></div>')
		// Float left to display horizontally, readjust .slides width
		.css({
		'float' : 'left',
		'width' : slideWidth
		});

		// Set #slideInner width equal to total width of all slides
		$('#slideInner').css('width', slideWidth * numberOfSlides);

		// Insert controls in the DOM
		$('#slideshow')
		.prepend('<span class="control" id="leftControl">Clicking moves left</span>')
		.append('<span class="control" id="rightControl">Clicking moves right</span>');

		// Hide left arrow control on first load
		manageControls(currentPosition);

		// Create event listeners for .controls clicks
		$('.control').bind('click', function(){
			// Determine new position
			currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;

			// Hide / show controls
			manageControls(currentPosition);
			// Move slideInner using margin-left
			$('#slideInner').animate({
				'marginLeft' : slideWidth*(-currentPosition)
			});
		});
	}
})(jQuery);