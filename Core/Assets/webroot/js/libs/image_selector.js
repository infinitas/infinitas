/**
 * Infinitas image selector
 *
 * Converts multi select checkboxes into image list that is selectable.
 */
(function($) {
	$.widget("infinitas.imageSelector", {
		/**
		 * These options will be used as defaults
		 */
		options: {
		},

		/**
		 * Convert multi select checkboxes into image selector
		 */
		_create: function() {
			var $this = this,
				element = $(this.element),
				images = element.find('input[type="checkbox"]'),
				imageIds = [],
				path = element.data('path');

			$this._search(element);
			element.append('<ul class="thumbnails imageSelector"></ul>');

			$.each(images, function(k, v) {
				v = $(v);
				var image = {
					id: v.attr('value'),
					checkbox: v.attr('id'),
					label: v.parent().find('label[for="' + v.attr('id') + '"]').html()
				};
				image.path = path + image.id + '/thumb_' + image.label;
				$(v).parent().hide();
				$this._addMarkup(element, image);
				imageIds.push(image);
			});

			$this._delayedImageLoad();
			$(window).scroll($this._delayedImageLoad);

			element.find('a').on('click', function() {
				var $this = $(this),
					checkbox = $('#' + $this.data('checkbox')),
					wasChecked = checkbox.attr('checked');
				checkbox.attr('checked', !wasChecked);
				if(wasChecked) {
					$this.parent().removeClass('active');
				} else {
					$this.parent().addClass('active');
				}
				return false;
			});
			return this;
		},

		/**
		 * Search for images
		 *
		 * list images that match the passed term, if the search is cleared all images are shown.
		 *
		 * @param jQuery element the main image select container
		 */
		_search: function(element) {
			element.append('<div class="input text"><input id="imageSelectorSearch"/></div>');
			var $this = this,
				search = element.find('#imageSelectorSearch');
			search.bindWithDelay('keyup', function() {
				var term = search.val();
				if(term.length > 0) {
					element.find('li').show().filter(function() {
						return !$(this).attr('class').match(new RegExp(term));
					}).hide();
				} else if(term.length == 0) {
					element.find('li').show();
				}
				$this._delayedImageLoad();
			}, 200);
		},

		/**
		 * Generate the markup for the select list
		 *
		 * The name of the image is split up and added as classes so there can be basic search filter
		 * on the images.
		 *
		 * Image src is set as a data attribute so the images can be loaded when viewed.
		 *
		 * @param jQuery element the main image select container
		 * @param object image the image details
		 */
		_addMarkup: function(element, image) {
			return element.find('ul.imageSelector').append(
				'<li class="span1 ' + image.label.replace(/\W/g, ' ') + '">' +
					'<a href="#" class="thumbnail" data-checkbox="' + image.checkbox + '">' +
						'<img src="#" data-src="' + image.path + '" class="not-loaded" /><span>' + image.label + '</span>' +
					'</a>' +
				'</li>'
			);
		},

		/**
		 * Load images that are in view
		 *
		 * This will get all the images in view that have not been loaded and add the src from the
		 * data attribute to the actual src causing the image to load.
		 */
		_delayedImageLoad: function() {
			var docViewTop = $(window).scrollTop();
			var docViewBottom = docViewTop + $(window).height();

			$.each($('img.not-loaded:visible'), function(k, v) {
				v = $(v);
				var elemTop = v.offset().top,
					elemBottom = elemTop + v.height();
				if ((elemBottom <= docViewBottom) && (elemTop >= docViewTop)) {
					v.attr('src', v.data('src')).removeClass('not-loaded');
				}
			});
		},

		/**
		 * Set options
		 */
		_setOption: function(key, value) {
			switch(key) {
				case 'length':
					break;
			}
			$.Widget.prototype._setOption.apply(this, arguments)
		},

		/**
		 * Use the destroy method to reverse everything your plugin has applied
		 */
		destroy: function() {
			$.Widget.prototype.destroy.call(this);
		}
	});
})(jQuery);