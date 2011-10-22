(function($) {

	$('input.tagitfield').livequery(function(){
		var $field = $(this);
		
		var settings = {};
		
		var caseSensitive = this.getAttribute('data-casesensitive');
		if (caseSensitive) {
			settings.caseSensitive = caseSensitive;
		}
		
		var allowSpaces = this.getAttribute('data-allowspaces');
		if (allowSpaces) {
			settings.allowSpaces = allowSpaces;
		}
		
		var delimiter = this.getAttribute('data-delimiter');
		if (delimiter) {
			settings.singleFieldDelimiter = delimiter;
		}
		
		$field.tagit(settings);
		
		
	});
})(jQuery);