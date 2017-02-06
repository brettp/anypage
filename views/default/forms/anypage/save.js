define(function (require) {

	var elgg = require('elgg');
	require('elgg/ready');

	var anypage = {};

	anypage.init = function () {
		$('#anypage-render-type').change(function () {
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

		$('#anypage-path').bind('input', anypage.updatePath);

		$('#anypage-path').bind('change', anypage.checkPath);

		// open in new tab
		$('a.anypage-updates-on-path-change').click(function (e) {
			e.preventDefault();
			window.open($(this).attr('href'));
		});

		// walled garden / gatekeeper options
		$('input[name=visible_through_walled_garden]').change(function (e) {
			$('input[name=requires_login]:disabled').prop('checked', !$(e.target).prop('checked'));
		});
	};

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
		elgg.action('anypage/check_path', {
			data: {
				'path': $pathInput.val(),
				'guid': $pathInput.parents('form').find('input[name=guid]').val()
			},
			success: function (json) {
				$pathInput.val(json.output.normalized_path);

				if (json.output.valid) {
					$('#anypage-notice').html('').hide();
				} else {
					$('#anypage-notice').show().html(json.output.html);
				}
			}
		});
	};

	anypage.init();

	return anypage;
});