$(document).ready(function() {
	$('.comments a.reply').on('click', function() {
		$($('.comments form input:visible').first()).focus();
		return false;
	});

	$('.comments a.perma-link').on('mouseover', function() {
		var $this = $(this);
		if ($this.data('link')) {
			return false;
		}
		$this.parent().append('<span class="permalink">' + $this.attr('href') + '</span>');
		$this.data('link', 1);
		return false;
	});
});