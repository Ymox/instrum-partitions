/**
 * 
 */
$(function() {
	$('form').on('dragover dragenter',
		'.dropzone',
		function(e) {
			$(e.target).addClass('is-dragover');
			e.preventDefault();
			e.stopPropagation();
		}
	).on('dragleave dragend',
		'.dropzone',
		function(e) {
			$(e.target).removeClass('is-dragover');
			e.preventDefault();
			e.stopPropagation();
		}
	).on('drop',
		'.dropzone',
		function(e) {
			let $target = $(e.target).removeClass('is-dragover');
			e.preventDefault();
			e.stopPropagation();
			let uri;
			if (!(uri = $target.data('upload-uri'))) {
				uri = $target.find('[type="file"][data-upload-uri]').data('upload-uri');
			}
			if (!uri) {
				return;
			}
			for (let i = 0; i < e.originalEvent.dataTransfer.files.length; i++) {
				const file = e.originalEvent.dataTransfer.files[i];
				if (file.type != 'application/pdf') {
					return;
				}
				const formData = new FormData()
				formData.append('file', file);
				$.ajax({
					type: 'post',
					url: uri,
					data: formData,
					xhr: function() {
						let appXhr = $.ajaxSettings.xhr();
		
						if (appXhr.upload) {
							appXhr.upload.addEventListener('progress', function(e) {
								console.log(e.loaded, e.total);
							}, false);
						}
						return appXhr;
					},
					contentType: false,
					processData: false,
					success: function(data) {
						let $target;
						if ($('#' + data.target + '_file').is(':input')) {
							$target = $('#' + data.target + '_file');							
						} else if ($('#' + data.target).prev().find('.adder').length) {
							const $container = $('#' + data.target);
							$container.prev().find('.adder').click();
							$target = $container.find('[name$="' + 'file' + ']"]').first()
						}
						$target.val(data.fileName);
						if ($target.prev().find('.downloadable').length) {
							const $downloadBlock = $target.prev().find('.downloadable');
							$downloadBlock.children('a').attr('href', data.filePath);
							$downloadBlock.children('input[type="text"]')
								.val(data.originalName)
								.prop('title', data.originalName);
							$downloadBlock.toggle();
						}
					},
					complete: function() {
						console.info('complete');
					}
				});
			}
		}
	);
	$('form').on('click', '.downloadable .cleaner', function(e) {
		const $downloadables = $(e.target).closest('.file-link').find('.downloadable');
		$downloadables.toggle();
		const toClean = $downloadables.find('[data-input-clean]').data('inputClean');
		toClean.forEach( function(e) {
			$(e).val(null);
		})
	});
	$('form .dropzone[data-upload-uri]').css({
		"padding-top": "4em",
		"background": "linear-gradient(to bottom, #f0f8ff 3.5em, transparent 3.5em)"
	});
});