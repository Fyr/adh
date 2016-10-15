$(function(){
	// Fixes for forms
	$('.form-group.error').addClass('has-error');
	$('.error-message').each(function(){
		var err = $(this).html();
		$(this).parent().find('.col-md-9').append('<span class="small help-block error">' + err + '</span>');
		$(this).remove();
	});

	$('.form-group input, .form-group textarea').focus(function(){
		var $parent = $(this).closest('.form-group');
		$parent.removeClass('has-error').removeClass('error');
		$parent.find('.help-block.error').remove();
	});

	$('.date-picker input[type=hidden]').each(function(){
		var val = Date.fromSqlDate($(this).val()).fullDate('rus');
		$(this).parent().find('input[type=text]').val(val);
	});

	$('.date-picker').datepicker({
		format: 'dd.mm.yyyy',
		autoclose: true,
		language: 'ru',
		// defaultViewDate: {year: 2016, month: 02, day: 27}
	});

	$('.date-picker input[type=text]').change(function(){
		var val = Date.local2sql($(this).val());
		$(this).parent().find('input[type=hidden]').val(val);
	});

});