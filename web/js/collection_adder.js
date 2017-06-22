/**
 * 
 */
$(document).ready( function() {
	$('[data-prototype]').each( function() {
		var prototype = $(this).data('prototype');
		$(this).prev().append(' ').append($('<button>', {type: 'button', 'class': 'btn btn-sm btn-success adder', 'html': '<i class="fa fa-plus-circle"></i>'}));
	});
	$('form').on('click', '.adder', function() {
	    var $container = $(this).closest('div').find('[data-prototype]');
	    var prototype = $container.data('prototype');
	    var index = $('>div, >tbody>tr', $container).length;
	    var regexLabel = new RegExp('(' + name[0] + '(\\\]\\\[|_)|>)__name__label__', 'ig');
	    var regexName = new RegExp('(' + name[0] + '(\\\]\\\[|_)|>)__name__(label__)?', 'ig');
	    prototype = prototype.replace(regexLabel, '$1' + (index + 1));
	    prototype = prototype.replace(regexName, '$1' + index);
	    $container.append(prototype);
	});
});