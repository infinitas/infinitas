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
	// better test http://benalman.com/projects/javascript-debug-console-log/
	if(typeof console.log != 'undefined') {
		console.log(data);
	}
}

if(typeof Infinitas.params.prefix == 'undefined'){
	Infinitas.params.prefix = '';
}

switch(Infinitas.params.prefix) {
	case 'admin':
		$(document).ready(function() {
			$('.accordions').accordion();
			$.FormHelper.foucusOnFirst();
			setupAjaxDropdowns();
			setupRowSelecting();
			setupDatePicker();
			setupAjaxPagination();
			setupRowDetails();
			setupHrefToggle();
			setupTabs();
			dateToggle();

			modulePositionSort();

			$('.colorpicker').colorpicker().on('changeColor', function(ev) {
				var colour = ev.color.toHex(),
					$this = $(this);
				$($('input', $this.parent())).val(colour.split('#')[1]);
				$this.css({'background-color': colour});
			});

			$(document).bind('keydown', 'ctrl+s', function(event) {
				if(event.ctrlKey && event.which == 83) { // ctrl+s
					$form = $('form').first();
					$.ajax({
						type: $form.attr('method'),
						url: $form.attr('href'),
						data: $form.serialize(),
						success: function() {
							alert('saved');
						}
					});
					return false;
				}
			});

			$.FormHelper.checkboxToggleAll('[id*="All"]:checkbox');

			$(".trigger").click(function(){
				$this = $(this);
				$this.siblings(".panel").toggle("fast");
				$(".trigger").removeClass('active').siblings(".panel").hide();
				$this.toggleClass("active");
				return false;
			});

			$('#PaginationOptionsPaginationLimit').change(function(){
				$('#PaginationOptions').submit();
			});

			if(!$('.filter-form').length) {
				$('.massActions .filter').remove();
			}
			$('#searchForm').click(function(){
				$('.filter-form').toggle();
				return false;
			});

			$('button').on('click', function() {
				var $this = $(this);
				if ($this.val() == 'cancel') {
					var inputs = $('input, select, textarea', $this.closest('form'));
					$.each(inputs, function(k, v) {
						$(v).removeAttr('required');
					});
				}
			})
		});
		break;

	default:
		$(document).ready(function(){
			setupTabs();

			$.FormHelper.passwordStrength('input.password');
			//setupStarRating();

			///$("#accordion").accordion({collapsible: true});
			setupAjaxDropdowns();
			setupHrefToggle();
		});
		break;
}

function modulePositionSort() {
	var moduleIndex = null,
		moduleSorter = $("ul.module-positions").sortable({
		connectWith: "ul.module-positions",
		items: 'div.module',
		placeholder: "ui-state-highlight",
		handle: '.reorder',
		start: function(event, ui) {
			var item = $(ui.item);
			$('a', item).hide();
			moduleIndex = $(ui.item).index();
		},
		stop: function(event, ui) {
			var item = $(ui.item),
				newIndex = $(ui.item).index(),
				changed = moduleIndex != newIndex;

			$('a', item).show();
			if(changed) {
				$('span', item).addClass('label-info');
			}
		},
		change: function(event, ui) {
			var item = $(ui.item);
		}
	}).disableSelection();

	var moduleButtons = $('.module-type a');
	moduleButtons.on('click', function() {
		var $this = $(this),
			modules = $("div.module", moduleSorter),
			selectedGroup = $this.data('group');

		moduleButtons.removeClass('disabled');
		$this.addClass('disabled');
		if(selectedGroup == 'all') {
			modules.show();
			return false;
		}

		modules.hide();
		modules.filter('.group-' + selectedGroup).show();
		return false;
	});
}

function setupTabs() {
	var $tabs = $('.tabs');
	if($tabs.length) {
		$tabs.tabs()
	}
}

function dateToggle() {
	$('div.date').hover(function() {
		$('span', this).toggle();
	}, function() {
		$('span', this).toggle();
	});
}

function setupHrefToggle() {
	$('a.toggle-target').live('click', function(){
		var target = $(this).data('target');

		if(target) {
			$(target).toggle();
			return false;
		}
	});
}

function setupRowDetails() {
	$('tr.parent .toggle').click(function() {
		$(this).parent().parent().next().toggle();
		return false;
	});
}

function setupKeyboardShortcuts() {
}
/** core code */
/**
 *
 * @access public
 * @return void
 **/
function setupAjaxDropdowns(){
	$selectMulti = $('.ajaxSelectPopulate');
	$selectMulti.live('change', function(){
		var $this = $(this);
		var $formId = '#' + $(this).closest('form').attr('id');
		metaData = $.HtmlHelper.getParams($this);

		$.FormHelper.emptySelect(metaData);
		if ($this.val().length != 0) {
			metaData.postData = $($formId).serialize();
			$.HtmlHelper.submit(metaData, $.FormHelper.input);
		}
	});
}

function setupRowSelecting(){
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

function setupStarRating() {
	var $rating = $('.star-rating');
	metaData = $.HtmlHelper.getParams($rating);
	pr(metaData);
	url = $.HtmlHelper.url(metaData);

	$('.coreRatingBox').empty();
	$rating.rater(
		url + metaData.url.id,
		{
			curvalue: metaData.currentRating
		}
	);
}

function setupDatePicker() {
	var currentDate;
	var now = new Date(); now.setDate(now.getDate());

	/**
	 * Start dates
	 */
	var date1 = $("#" + Infinitas.model + "StartDate");
	if(date1.length){
		currentDate = now;
		if(date1.val() != '') {
			currentDate = date1.val().split('-');
			currentDate = new Date (currentDate[0], currentDate[1]-1, currentDate[2]);
		}

		startDate = $("#" + Infinitas.model + "DatePickerStartDate").calendarPicker({
			"date": currentDate,
			callback: function(cal){
				date1.val(cal.mysqlDate);
			}
		});
	}

	/**
	 * end dates
	 */
	var date2 = $("#" + Infinitas.model + "EndDate");
	if(date2.length){
		currentDate = now;
		if(date2.val() != ''){
			currentDate = date2.val().split('-');
			currentDate = new Date (currentDate[0], currentDate[1]-1, currentDate[2]);
		}

		endDate = $("#" + Infinitas.model + "DatePickerEndDate").calendarPicker({
			"date": currentDate,
			callback: function(cal){
				date2.val(cal.mysqlDate);
			}
		});
	}

	/**
	 * general dates
	 */
	var date3 = $("#" + Infinitas.model + "Date");
	if(date3.length){
		currentDate = now;
		if(date3.val() != '') {
			currentDate = date3.val().split('-');
			currentDate = new Date (currentDate[0], currentDate[1]-1, currentDate[2]);
		}

		date = $("#" + Infinitas.model + "DatePickerDate").calendarPicker({
			"date": new Date (currentDate[0], currentDate[1]-1, currentDate[2]),
			callback: function(cal){
				date3.val(cal.mysqlDate);
			}
		});
	}
}

function setupAjaxPagination() {
	$link = $('a.ajax');
	$.HtmlHelper.loading('showMore');
	$link.live('click', function(event){
		$('.showMore').remove();
		$.ajax({
			url: $(this).attr('href'),
		  	success: function(data, textStatus, XMLHttpRequest){
				data = $('<div>'+data+'</div>').find('.list').children();
				$('.list').append(data);
				data = '';
				$.HtmlHelper.loading('', false);
			}
		});
		return false;
	});
}