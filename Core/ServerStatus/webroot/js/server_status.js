$(document).ready(function() {
	$('.server_status.admin_info').prepend('<div class="tags"></div>');
	var a = $('h1.no-gap a'),
		tags = [],
		$tags = $('.server_status.admin_info .tags');

	$.each(a, function(k, v) {
		v = $(v);
		var data = {
			anchor: v.attr('name'),
			value: v.html()
		};
		tags.push(data);

		v.parent().attr('id', data.anchor);
		$tags.append('<a href="#' + data.anchor + '" class="btn">' + data.value + '</a>')
	});
});