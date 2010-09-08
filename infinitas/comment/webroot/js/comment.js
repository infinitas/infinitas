$(document).ready(function(){
	$('.comment textarea').focus(function(){
		$(this).css('height', '50px');
		$(this).siblings('input.submit').show();
	});

	$('.comment textarea').blur(function(){
		if($(this).val() == ''){
			$(this).siblings('input.submit').hide();
			$(this).css('height', '15px');
		}
	});
});