/*
 * jixedbar - Fixed bar.
 * http://code.google.com/p/jixedbar/
 *
 * Version 0.0.2 - December 18, 2009
 *
 * Copyright (c) 2009 Ryan Yonzon, http://ryan.rawswift.com/
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

(function($) {

	// jixedbar plugin
	$.fn.jixedbar = function(options) {
		var constants = {
				constOverflow: "hidden",
				constPosition: "absolute",
				constBottom: "0px"
			};
		var defaults = {
				hoverOpaque: true,
				hoverOpaqueEffect: {enter: {speed: "fast", opacity: 1.0}, leave: {speed: "fast", opacity: 0.5}},
				roundedCorners: true, // only works in FF
				roundedButtons: true // only works in FF
			};
		var options = $.extend(defaults, options);
		var ie6 = ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) == 4) && (navigator.appVersion.indexOf("MSIE 6.0") != -1));

		this.each(function() {
			var obj = $(this);
			var $screen = jQuery(this);
			var fullScreen = $screen.width(); // get screen width
			var centerScreen = (fullScreen/2)*(1); // get screen center

			// set html and body style for jixedbar to work
			$("html").css({"overflow" : "hidden", "height" : "100%"});
			$("body").css({"margin": "0px", "overflow": "auto", "height": "100%"});

			// initialize bar
			$(this).css({
				"overflow": constants['constOverflow'],
				"position": constants['constPosition'],
				"bottom": constants['constBottom']
			});

			// add bar style (theme)
			$(this).addClass("jx-bar");

			// rounded corner style (theme)
			if (defaults.roundedCorners) {
				$(this).addClass("jx-bar-rounded-tl jx-bar-rounded-tr");
			}

			// button style (theme)
			$(this).addClass("jx-bar-button");

			// rounded button corner style (theme)
			if (defaults.roundedButtons) {
				$(this).addClass("jx-bar-button-rounded");
			}

			// calculate and adjust bar to center
			marginLeft = centerScreen-($(this).width()/2);
			$(this).css({'margin-left': marginLeft});

			// fix image vertical alignment and border
			$("img", obj).css({
				"vertical-align": "bottom",
				"border": "#ffffff solid 0px" // no border
			});

			// check for alt attribute and set it as button text
			$(this).find("img").each(function() {
				altName = "&nbsp;" + $(this).attr('alt');
				$(this).parent().append(altName);
			});

			// check of hover effect is enabled
			if (defaults.hoverOpaque) {
				$(this).fadeTo(defaults.hoverOpaqueEffect['leave']['speed'], defaults.hoverOpaqueEffect['leave']['opacity']);
				$(this).bind("mouseenter", function(e){
					$(this).fadeTo(defaults.hoverOpaqueEffect['enter']['speed'], defaults.hoverOpaqueEffect['enter']['opacity']);
			    });
				$(this).bind("mouseleave", function(e){
					$(this).fadeTo(defaults.hoverOpaqueEffect['leave']['speed'], defaults.hoverOpaqueEffect['leave']['opacity']);
			    });
			}

			// create tooltip container
			$("<div />").attr("id", "__jx_tooltip_con__").appendTo("body"); // create div element and append in html body
			$("#__jx_tooltip_con__").css({
				"height": "auto",
				"margin-bottom": $(this).height() + 6, // put spacing between tooltip container and fixed bar
				"margin-left": "0px",
				"width": "100%", // use entire width
				"overflow": constants['constOverflow'],
				"position": constants['constPosition'],
				"bottom": constants['constBottom']
			});

			// hover in and out event handler
			$("li", obj).hover(
				function () { // in/over event
					var elemID = $(this).attr('id'); // get ID (w/ or w/o ID, get it anyway)
					var barTooltipID = elemID + "__tooltip__"; // set a tooltip ID
					var tooltipTitle = $(this).attr('title');

					if (tooltipTitle == '') { // if no 'title' attribute then try 'alt' attribute
						tooltipTitle = $(this).attr('alt'); // this prevents IE from showing its own tooltip
					}

					if (tooltipTitle != '') { // show a tooltip if it is not empty
						// create tooltip wrapper; fix IE6's float double-margin bug
						barTooltipWrapperID = barTooltipID + '_wrapper';
						$("<div />").attr("id", barTooltipWrapperID).appendTo("#__jx_tooltip_con__");
						// create tooltip div element and put it inside the wrapper
						$("<div />").attr("id", barTooltipID).appendTo("#" + barTooltipWrapperID);

						// tooltip default style
						$("#" + barTooltipID).css({
							"float": "left",
							"display": "none"
						});

						// theme for tooltip (theme)
						$("#" + barTooltipID).addClass("jx-bar-button-tooltip");

						// set tooltip text
						$("#" + barTooltipID).html(tooltipTitle);

						// fix tooltip wrapper relative to the associated button
						$("#" + barTooltipWrapperID).css({
							"margin-left": ($(this).offset().left - ($("#" + barTooltipID).width() / 2)) + ($(this).width()/2)
						});

						// show tooltip
						$("#" + barTooltipID).show();
					}
				},
				function () { // out event
					var elemID = $(this).attr('id'); // get ID (whether there is an ID or none)
					var barTooltipID = elemID + "__tooltip__"; // set a tooltip ID
					var barTooltipWrapperID = barTooltipID + '_wrapper';
					$("#" + barTooltipID).remove(); // remove tooltip element
					$("#" + barTooltipWrapperID).remove(); // remove tooltip's element DIV wrapper
				}
			);

			// fix PNG transparency problem in IE6
			if ($.browser.msie && ie6) {
				$(this).find('li').each(function() {
					$(this).find('img').each(function() {
						imgPath = $(this).attr("src");
						altName = $(this).attr("alt");
						srcText = $(this).parent().html();
						$(this).parent().html( // wrap with span element
							'<span style="cursor:pointer;display:inline-block;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + imgPath + '\');">' + srcText + '</span>' + altName
						);
					});
					$(this).find('img').each(function() {
						$(this).attr("style", "filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);"); // show image
					})
				});
			}

			/**
			 * To-do:
			 * 	1. Element click event:
			 * 		$("li", obj).click(function() {
			 * 			// event handler
			 * 		});
			 */

		});

		return this;

	};

})(jQuery);