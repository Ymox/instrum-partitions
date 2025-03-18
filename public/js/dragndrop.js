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
						const appXhr = $.ajaxSettings.xhr();

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
							$target = $container.find('[name$="' + 'file' + ']"]').first();
						}
						const $subform = $target.prev().closest('[data-prototype] > fieldset');
						let nonMultipleName;
						if ($subform.find(':input[name]')[0].name.endsWith('[]')) {
							nonMultipleName = $subform.find(':input[name]')[0].name.substring(0, -3);
						} else {
							nonMultipleName = $subform.find(':input[name]')[0].name;
						}
						const baseName = nonMultipleName.substring(0, nonMultipleName.lastIndexOf('['));
						const regexParts = {};
						for (const field of $subform.find(':input[name]:not(button, [type="hidden"], [type="file"], [type="button"], [type="reset"], [type="submit"], [type="image"])')) {
							const fieldName = field.name.substring(baseName.length + 1, field.name.lastIndexOf(']'));
							tag: switch (field.tagName) {
								case 'SELECT':
									const options = [];
									for (const option of [...$('option', field)].reverse()) {
										if (!option.value) {
											continue;
										}
										options.push('(?<' + fieldName + '$' + option.value + '>' + (option.dataset?.regex || option.text) + ')');
									}
									regexParts[fieldName] = options.join('|');
									break;
								case 'INPUT':
									switch (field.type) {
										case 'checkbox':
											const label = $(field).parent().find('[for="' + field.id + '"]')[0];
											regexParts[fieldName] = '(?<' + fieldName + '>' + (label.dataset?.regex || label.innerText) + ')';
											break tag;
										case 'number':
											regexParts[fieldName] = '(?<' + fieldName + '>' + (field.dataset?.regex || '\\d+') + ')';
											break tag;
										default:
											// no break;
									}
								default:
									regexParts[fieldName] = '(?<' + fieldName + '>)';
									break;
							}
						}
						let regex = $target.data('regex');
						for (const property in regexParts) {
							regex = regex.replace(new RegExp('__' + property + '__', 'g'), regexParts[property]);
						}
						regex = RegExp(regex.replace(/♭/g, 'b').replace(' en ', '(?: en)? '), 'gi');
						$target.val(data.fileName);
						if ($target.prev().find('.downloadable').length) {
							const $downloadBlock = $target.prev().find('.downloadable');
							$downloadBlock.children('a').attr('href', data.filePath);
							$downloadBlock.children('input[type="text"]')
								.val(data.originalName)
								.prop('title', data.originalName);
							$downloadBlock.toggle();
						}
						const fullMatches = regex.exec(data.originalName)?.groups;
						if (fullMatches) {
							const matches = Object.fromEntries(Object.entries(fullMatches).filter(([_, v]) => v != null));
							for (const property in matches) {
								let propertyName;
								let value;
								const propertyParts = property.split('$');
								if (propertyParts.length == 1) {
									propertyName = property;
									value = matches[propertyName]
								} else {
									propertyName = propertyParts[0];
									value = propertyParts[1];
								}
								const field = $subform.find('[name="' + baseName + '[' + propertyName + ']"]')[0];
								switch (field.type) {
									case 'checkbox':
										field.checked = !!value;
									default:
										field.value = value;
								}
							}
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
	$('form .dropzone[data-upload-uri]').addClass('dropzone-active');
	$('form .dropzone.dropzone-active').append($('form .dropzone.dropzone-active').data('dropzone-prototype'));
});