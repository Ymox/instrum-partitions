/**
 * 
 */
$(document).ready( function() {
	$('select.addable').each( function() {
		var uri = $(this).data('uri');
		$(this).prev().append(' ').append(
			$('<a>', {href: uri || '#', 'data-target': '#Modal', 'data-toggle': 'modal', 'html': '<i class="fa fa-plus-circle"></i>', target: '_blank', 'class': 'btn btn-sm btn-outline-success'})
		);
	});
	$(document).on('show.bs.modal', function(e) {
		$('#Modal').data('fieldTarget', '#' + $(e.relatedTarget).parent().attr('for'));
		$('.modal-content').load($(e.relatedTarget).attr('href'));
	});
	$(document).on('hidden.bs.modal', function(e) {
		$(e.target).find('.modal-content').empty();
	});
});