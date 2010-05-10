(function($) {
	var FormHelper = $.FormHelper = {};

	/**
	 * generate a form input
	 */
	FormHelper.input = function(data, metaData) {
		$('#' + metaData.target).empty();
		if ($.Core.type(data) == 'string') {
		}

		$.FormHelper.select(data, metaData);
	};

	/**
	 * generate a select dropdown
	 */
	FormHelper.select = function(data, metaData) {
		var options = '';
		$.each(data, function(index, name) {
			if(($.Core.type(name) == 'plainObject') || ($.Core.type(name) == 'array')) {
				options += '<optgroup label="' + index + '">';
				$.each(name, function(sub_index, sub_name) {
					options += '<option value="' + sub_index + '">' + sub_name + '</option>';
				});
				options += '</optgroup>';
			}
			else {
				options += '<option value="' + index + '">' + name + '</option>';
			}

		});
		$('#' + metaData.target).empty().html(options);
	};

	/**
	 * toggle checkboxes
	 */
	FormHelper.checkboxToggleAll = function() {
		var tog = false;
		var toggleId = '#' + Infinitas.model + 'All';
		$(toggleId).click(function(){
			$("input:checkbox[not:"+toggleId+"]").attr("checked",!tog).change();
			tog = !tog;
		});
	};

	/**
	 * image dropdown
	 */
	FormHelper.imageDropdown = function(fieldId) {
		$("#" + fieldId).msDropDown();
	};
})(jQuery);