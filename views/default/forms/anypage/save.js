define(function (require) {

	var elgg = require('elgg');
	require('elgg/ready');

	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax(false);

	var anypage = {};

	anypage.updatePath = function () {
		var $this = $(this);
		var val = $this.val();
		val = val.ltrim('/');
		// we don't have rtrim?
		if (val.lastIndexOf('/') === val.length - 1) {
			val = val.substring(0, val.length - 1);
		}
		val = '/' + val;

		$('a.anypage-updates-on-path-change')
				.attr('href', elgg.normalize_url(val))
				.html(elgg.normalize_url(val));
		$('span.anypage-updates-on-path-change').html(val);
	};

	/**
	 * Normalizes and checks path for conflicts or invalid chars.
	 */
	anypage.checkPath = function () {
		var $pathInput = $(this);
		ajax.action('anypage/check_path', {
			data: {
				path: $pathInput.val(),
				guid: $pathInput.parents('form').find('input[name=guid]').val()
			}
		}).done(function (output) {
			$pathInput.val(output.normalized_path);

			if (output.valid) {
				$('#anypage-notice').html('').hide();
			} else {
				$('#anypage-notice').show().html(output.html);
			}
		});
	};

	$(document).on('change', '#anypage-render-type', function () {
		var $this = $(this);

		switch ($this.val()) {
			case 'view':
				$('#anypage-view-info').show();
				$('#anypage-description').hide();
				$('#anypage-layout').hide();
				break;

			case 'html':
				$('#anypage-view-info').hide();
				$('#anypage-description').show();
				$('#anypage-layout').show();
				break;
		}
	});

	$(document).on('change', '#anypage-path', anypage.updatePath);
	$(document).on('change', '#anypage-path', anypage.checkPath);

	// open in new tab
	$(document).on('click', '.anypage-updates-on-path-change[href]', function (e) {
		e.preventDefault();
		window.open($(this).attr('href'));
	});

	// walled garden / gatekeeper options
	$(document).on('change', 'input[name=visible_through_walled_garden]', function (e) {
		$('input[name=requires_login]:disabled').prop('checked', !$(e.target).prop('checked'));
	});

	return anypage;
});