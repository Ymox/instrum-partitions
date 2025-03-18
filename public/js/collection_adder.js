$(function() {
	const removeButton = function($where) {
		$('>div, >fieldset, >tbody>tr', $where).filter(function(i, retest) {
			return $('.remover', retest).length === 0 && !$(retest).is('.dropzone-info');
		}).css('position', 'relative').children().append('<button type="button" class="btn btn-sm btn-danger remover" style="position: absolute; top: 2px; right: 2px; height: auto; width: auto;"><i class="fa fa-times"></i></button>')
	}
	const addButton = function($where) {
		$where.prev().append(' ').append($('<button>', {type: 'button', 'class': 'btn btn-sm btn-success adder', 'html': '<i class="fa fa-plus-circle"></i>'}));
		removeButton($where);
	}
	$('[data-prototype]').each( function() {
		addButton($(this));
		const observer = new MutationObserver( function(mutations) {
			mutations.forEach( function(mutation) {
				addButton($(mutation.addedNodes[0]).find('[data-prototype]'));
				$('select.searchable', mutation.addedNodes[0]).select2({theme: "bootstrap-5"});
			});
		});
		observer.observe(this, { childList: true });
	});
	$('form').on('click', '.adder', function() {
		const $container = $(this).closest('fieldset').find('[data-prototype]').first();
		let prototype = $container.data('prototype');
		const index = new Date().getTime() % (10 * 60 * 1000);
		prototype = prototype.replace(/__name__(label__)?/ig, index);
		if ($container.data('add-direction') == 'prepend') {
			if ($container.data('dropzone-prototype')) {
				$container.children(':first-child').after(prototype);
			} else {
				$container.prepend(prototype);
			}
		} else {
			if ($container.data('dropzone-prototype')) {
				$container.children(':last-child').before(prototype);
			} else {
				$container.append(prototype);
			}
		}
		removeButton($container);
	});
	$('form').on('click', '.remover', function() {
		$(this).toggleClass('btn-danger btn-light').html('<i class="fa fa-' + ($(this).is('.btn-danger') ? 'times' : 'undo') + '"></i>');
		$(this).closest('div, fieldset').toggleClass('bg-danger text-white').find(':input:not(button)').prop('disabled', !$(this).is('.btn-danger'));
	});
});