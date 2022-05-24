$(function() {
	const addButton = function($where) {
		$where.prev().append(' ').append($('<button>', {type: 'button', 'class': 'btn btn-sm btn-success adder', 'html': '<i class="fa fa-plus-circle"></i>'}));
		$('>div, >fieldset, >tbody>tr', $where).filter(function() {
			return $('.remover', $where).length === 0;
		}).css('position', 'relative').children().append('<button type="button" class="btn btn-sm btn-danger remover" style="position: absolute; top: 2px; right: 2px; height: auto; width: auto;"><i class="fa fa-times"></i></button>')
	}
	$('[data-prototype]').each( function() {
		addButton($(this));
		const observer = new MutationObserver( function(mutations) {
			mutations.forEach( function(mutation) {
				addButton($(mutation.addedNodes[0]).find('[data-prototype]'));
				$('select', mutation.addedNodes[0]).select2({theme: "bootstrap-5"});
			});
		});
		observer.observe(this, { childList: true });
	});
	$('form').on('click', '.adder', function() {
		const $container = $(this).closest('fieldset').find('[data-prototype]').first();
		let prototype = $container.data('prototype');
		const index = new Date().getTime() % (10 * 60 * 1000);
		prototype = prototype.replace(/__name__(label__)?/ig, index);
		$container.prepend(prototype);
		$('>div, >fieldset, >tbody>tr', $container).filter(function() {
			return $('.remover', this).length === 0;
		}).css('position', 'relative').children().append('<button type="button" class="btn btn-sm btn-danger remover" style="position: absolute; top: 2px; right: 2px; height: auto; width: auto;"><i class="fa fa-times"></i></button>');
	});
	$('form').on('click', '.remover', function() {
		$(this).toggleClass('btn-danger btn-light').html('<i class="fa fa-' + ($(this).is('.btn-danger') ? 'times' : 'undo') + '"></i>');
		$(this).closest('div').toggleClass('bg-danger text-white').find(':input:not(button)').prop('disabled', !$(this).is('.btn-danger'));
	});
});