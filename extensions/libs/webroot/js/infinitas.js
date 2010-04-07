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
	console.log(data);
}


require(
[
	"require",
	Infinitas.base + "libs/js/libs/metadata.js",
	Infinitas.base + "libs/js/libs/core.js",
	Infinitas.base + "libs/js/libs/form.js",
	Infinitas.base + "libs/js/libs/html.js"
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
	$("*").tooltip({ 
	    track: true, delay: 0, showURL: false, 
	    fixPNG: true, showBody: " :: ", 
	    extraClass: "pretty fancy", left: 5 
	}); 
}

function rowSelect(){
	$("table.listing input").click(function() {
        if ($(this).attr("checked") == true) {
			$(this).parent().parent().removeClass('highlightRowRelated');
        	$(this).parent().parent().toggleClass("highlightRowSelected");
        } else {
        	$(this).parent().parent().removeClass("highlightRowSelected");
        }
	});	

	$('td').click(function(){ 
		var col = $(this).parent().children().index($(this));
		col++;
		if (col > 1){
			var thisClicked = $.trim($(this).text());
			$('table.listing td:nth-child(' + col + ')' ).each(function(index) { 				
				if (thisClicked == $.trim($(this).text())) {
					$(this).parent().removeClass('highlightRowSelected');	
					$(this).parent().addClass('highlightRowRelated');				
				}
				else{
					$(this).parent().removeClass('highlightRowRelated');
				}
			}); 
		}
	});
}