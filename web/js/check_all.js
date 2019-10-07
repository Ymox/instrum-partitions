/**
 *
 */
$(document).ready( function() {
	$('.check-all').change( function() {
		const target = $(this).data('target');
		$(target).prop('checked', $(this).prop('checked'));
	});
	$('.check-all').each( function() {
		const target = $(this).data('target');
		$(target).on('change', function() {
			const $parent = $($(this).data('parent'));
			if ($(target + ':checked').length == $(target).length) {
				$parent.prop('indeterminate', false);
				$parent.prop('checked', true);
			} else if ($(target + ':checked').length == 0) {
				$parent.prop('indeterminate', false);
				$parent.prop('checked', false);				
			} else {
				$parent.prop('checked', false);
				$parent.prop('indeterminate', true);
			}
		});
	});
});