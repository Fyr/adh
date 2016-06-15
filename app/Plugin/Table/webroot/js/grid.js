$(function(){
	$('.dataTable > thead > tr > th').each(function(){
		var $a = $('a', this);
		if ($a.length) {
			var href = $a.prop('href');
			$(this).html($a.html());
			$(this).click(function () {
				window.location.href = href;
			});
		}
	});

	$('.dataTable th.checkboxes input[type=checkbox]').change(function(){
		var checked = $(this).prop('checked');
		var $table = $(this).closest('table.dataTable');
		if ($(this).prop('checked')) {
			$('td.checkboxes .checker span', $table).addClass('checked');
		} else {
			$('td.checkboxes .checker span', $table).removeClass('checked');
		}
		$('td.checkboxes input', $table).prop('checked', checked);
		$('td.checkboxes input', $table).change();
	});
});