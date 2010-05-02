/**
 * popup alert box
 * @access public
 * @return void
 **/
function pr(data){
	alert(data);
}

/**
 * write data to console
 * @access public
 * @return void
 **/
function debug(data){
	if(typeof console.log != 'undefined') {
		console.log(data);
	}
}


require(
[
	"require",
	Infinitas.base + "libs/js/libs/core.js",
	Infinitas.base + "libs/js/libs/form.js",
	Infinitas.base + "libs/js/libs/html.js",
	Infinitas.base + "libs/js/libs/number.js",

	Infinitas.base + "libs/js/3rd/metadata.js",
	Infinitas.base + "libs/js/3rd/date.js",
	Infinitas.base + "libs/js/3rd/image_drop_down.js",

	Infinitas.base + "libs/js/3rd/jquery_ui.js",

],
function(require) {
	render();
});


/**
 *
 * @access public
 * @return void
 **/
function render(){
	$(document).ready(function(){
		urlDropdownSelects();
		doToolTips();

		rowSelect();

		$.FormHelper.checkboxToggleAll();

		datePicker();
	});
}


/** core code */
/**
 *
 * @access public
 * @return void
 **/
function urlDropdownSelects(){
	/**
	 * Check for plugin dropdown changes
	 */
	$('.pluginSelect').change(function(){
		if ($(this).val().length != 0) {
			metaData = $.HtmlHelper.getParams($(this));
			metaData.params.plugin = $(this).val();
			$.HtmlHelper.requestAction(metaData, $.FormHelper.input);
		}
	});

	/**
	 * Check for controller dropdown changes
	 */
	$('.controllerSelect').change(function(){
		if ($(this).val().length != 0) {
			metaData = $.HtmlHelper.getParams($(this));
			metaData.params.plugin     = $('.pluginSelect').val();
			metaData.params.controller = $(this).val();
			$.HtmlHelper.requestAction(metaData, $.FormHelper.input);
		}
	});
}

function doToolTips(){
	$("[title]:not(.textarea *)").tooltip({
	    track: true, delay: 0, showURL: false,
	    fixPNG: true, showBody: " :: ",
	    extraClass: "pretty fancy", left: 5, top: -5
	});
}

function rowSelect(){
	$("table.listing input:checkbox").change(function() {
		var $this = $(this);

        if ($this.attr("checked") == true) {
			$this
				.parents('tr')
				.removeClass('highlightRowRelated')
				.addClass("highlightRowSelected");
        } else {
        	$this.parents('tr').removeClass("highlightRowSelected");
        }
	});

	$('td').click(function(){
		var $this = $(this)
		var col = $this.prevAll().length+1;

		if (col > 1){
			var thisClicked = $.trim($this.text());

			$('table.listing td:nth-child(' + col + ')' ).each(function() {
				var $_this = $(this);

				if (thisClicked == $.trim($_this.text())) {
					$_this.parent().removeClass('highlightRowSelected');
					$_this.parent().addClass('highlightRowRelated');
				}
				else{
					$_this.parent().removeClass('highlightRowRelated');
				}
			});
		}
	});
}

function datePicker() {
	var startDate;
	var endDate;
	var date;

	startDate = $("#" + Infinitas.model + "DatePickerStartDate").calendarPicker({
		callback: function(cal){
			$("#" + Infinitas.model + "StartDate").val(cal.mysqlDate);
		}
	});

	endDate = $("#" + Infinitas.model + "DatePickerEndDate").calendarPicker({
		callback: function(cal){
			$("#" + Infinitas.model + "EndDate").val(cal.mysqlDate);
		}
	});

	date = $("#" + Infinitas.model + "DatePickerDate").calendarPicker({
		callback: function(cal){
			$("#" + Infinitas.model + "Date").val(cal.mysqlDate);
		}
	});

	//$("#" + Infinitas.model + "Start, #" + Infinitas.model + "End").parent().toggle();
}
