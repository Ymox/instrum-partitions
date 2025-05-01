/**
 *
 */
const collectionAdderScriptTag = document.currentScript;
$(function() {
	const removeButton = function($where) {
		$('>div, >fieldset, >tbody>tr', $where).filter(function(i, retest) {
			return $('.remover', retest).length === 0 && !$(retest).is('.dropzone-info');
		}).css('position', 'relative').children().append(collectionAdderScriptTag.dataset.collectionRemoverPrototype)
	};
	const addButton = function($where) {
		$where.prev().append(' ').append(collectionAdderScriptTag.dataset.collectionAdderPrototype);
		removeButton($where);
	}
	$(':not(button)[data-prototype]').each( function() {
		addButton($(this));
		const collectionObserver = new MutationObserver( function(mutations) {
			mutations.forEach( function(mutation) {
				addButton($(mutation.addedNodes[0]).find('[data-prototype]'));
				$('select.searchable', mutation.addedNodes[0]).select2({theme: "bootstrap-5"});
			});
		});
		collectionObserver.observe(this, { childList: true });
	});
	const modalObserver = new MutationObserver( function(mutations) {
		mutations.forEach( function(mutation) {
			addButton($(mutation.addedNodes[3]).find('[data-prototype]'));
		});
	});
	modalObserver.observe(document.getElementsByClassName('modal-content')[0], { childList: true });
	$('form, #Modal').on('click', '.collection-adder', function() {
		const $container = $(this).closest('fieldset, form').find('[data-prototype]').first();
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
	$('form, #Modal').on('click', '.collection-remover', function() {
		const newContent = $(this).data('switch-content');
		const newClass = $(this).data('switch-class');
		const newParentClass = $(this).data('switch-parent-class');
		if (newContent) {
			$(this).data('switch-content', $(this).html());
			$(this).data('active', !$(this).data('active'));
			$(this).toggleClass(newClass).html(newContent);
			$(this).closest('div, fieldset').toggleClass(newParentClass).find(':input:not(button)').prop('disabled', !$(this).data('active'));
		} else {
			$(this).closest('div, fieldset').remove();
		}
	});
});