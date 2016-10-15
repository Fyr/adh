$(function() {
	$('.more-items .expand-items').click(function () {
		$(this).hide();
		$(this).parent().find('.collapse-items').show();
		$(this).parent().find('div').slideDown('fast');
	});
	$('.more-items .collapse-items').click(function () {
		$(this).parent().find('div').slideUp('fast', function () {
			$(this).parent().find('.collapse-items').hide();
			$(this).parent().find('.expand-items').show();
		});
	});
});