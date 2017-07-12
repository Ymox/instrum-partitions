$(document).ready( function() {
	$('[data-prototype]').each( function() {
		var prototype = $(this).data('prototype');
		$(this).prev().append(' ').append($('<button>', {type: 'button', 'class': 'btn btn-sm btn-success adder', 'html': '<i class="fa fa-plus-circle"></i>'}));
	});
	$('form').on('click', '.adder', function() {
	    var $container = $(this).closest('div').find('[data-prototype]');
	    var prototype = $container.data('prototype');
	    var index = $('>div, >tbody>tr', $container).length;
	    prototype = prototype.replace(/__name__(label__)?/ig, index);
	    $container.append(prototype);
	    $('>div, >tbody>tr', $container).filter(function() {
	    	return $('>.remover', this).length === 0;
	    }).css('position', 'relative').append('<button class="btn btn-sm btn-danger remover" style="position: absolute; top: 0px; right: 0px;">✖</button>')
	});
	$('form').on('click', '.remover', function(e) {
		$(e.target).closest('div').remove();
	});
});