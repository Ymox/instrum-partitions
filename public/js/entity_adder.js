/**
 *
 */
const entityAdderPrototype = document.currentScript.dataset.entityAdderPrototype;
$(function() {
	const addButton = function($addable) {
		const uri = $addable.data('uri');
		const $label = $addable.prev();
		if (!$label.find('a').length) {
			$label.append(' ').append(
				$(entityAdderPrototype.replace(/__uri__/, uri))
			);
		}
	};

	$('select.addable').each( function() {
		addButton($(this));
	});

	$('[data-prototype]').each( function() {
		const observer = new MutationObserver( function(mutations) {
			mutations.forEach( function(mutation) {
				addButton($(mutation.addedNodes[0]).find('select.addable'));
			});
		});
		observer.observe(this, { childList: true });
	});

	$(document).on('show.bs.modal', function(e) {
		$('#Modal').data('fieldTarget', '#' + $(e.relatedTarget).parent().attr('for'));
		$('.modal-content').load($(e.relatedTarget).attr('href'));
	});
	$(document).on('hidden.bs.modal', function(e) {
		const target = $(e.target).find('.modal-content');
		target.html($(target).data('emptyContent'));
	});
});