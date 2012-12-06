/**
 * Load module config when a new module is selected
 */
$('#ModuleModule').on('change', function() {
	$.post('/admin/modules/modules/config', $(this).closest('form').serialize(), function(data) {
		$('.module-config').html(data);
	});
});