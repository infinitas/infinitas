$(document).ready(function() {
	var inputs = $('input[name="data[MenuItem][_type]"]');
	if (!inputs.length) {
		return;
	}

	var checked = $('input:checked', inputs.parent());

	if (!checked.length) {
		return;
	}

	switchUrlType($(checked).val());
	$(inputs).on('change', function() {
		switchUrlType($(this).val());
	})
});

function switchUrlType(value) {
	if (value == 'internal') {
		$('div.external').hide();
		$('div.internal').show();
	} else {
		$('div.external').show();
		$('div.internal').hide();
	}
}