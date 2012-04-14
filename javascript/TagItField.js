(function($) {

	$('input.tagitfield').livequery(function(){
		var $field = $(this);
		
		// in case we want to set defaults in the JS
		var settings = {
			onTagAdded: function(event, tag) {
				// this is here to stop the error caused by rewriteHashlinks in TabSet.js (which assumes <a>s in <ul>s have href attributes and breaks if they don't)
				$('.tagit-close', tag).attr('href', 'javascript:void(0)');
			}
		};
		
		var settingsString = this.getAttribute('data-settings');
		
		if (settingsString) {
			$.extend(settings, jQuery.parseJSON(settingsString));
		}
		
				
		var tagsString = this.getAttribute('data-available-tags');
		if (tagsString != null) {
			settings.availableTags = tagsString.split('::');
		}
		
		if (settings.tagSourceURL != null) {
			settings.tagSource = function(search, showChoices) {
				$.ajax({
					url: settings.tagSourceURL,
					data: {
						search: search.term,
						ajax: 1
					},
					success: function(choices) {
						showChoices(choices);
					}
				});
			};
		}
		
		$field.tagit(settings);
				
	});
})(jQuery);