<?php
/*

	- tag sources
		- prefetch automatically
		- prefetch manually supply tags
		- AJAX automatically
		- AJAX manual URL
		- none
	
*/
class TagItField extends TextField {
	
	//var $dataDelimiter = ',';
	var $settings = array(
		'allowSpaces' => true,
		'caseSensitive' => false,
		'singleFieldDelimiter' => ',',
		'placeholderText' => null,
		'tagSourceURL' => null
	);
	
	var $useAJAX = false;
	
	var $availableTags = array();
	
	//var $valueArray = array();
	
	function __construct($name, $title = null, $value = "", $form = null) {
		parent::__construct($name, $title, $value, null, $form);
	}
	
	
	function includeRequirements() {
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-ui/jquery.ui.core.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-ui/jquery.ui.widget.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-ui/jquery.ui.autocomplete.js");
		
		Requirements::javascript("tagitfield/thirdparty/tag-it/tag-it.js");
		Requirements::javascript("tagitfield/javascript/TagItField.js");
		
	//		Requirements::css(THIRDPARTY_DIR."/jquery-ui-themes/smoothness/jquery.ui.all.css");
		Requirements::css("tagitfield/thirdparty/tag-it/jquery.tagit.css");
		Requirements::css("tagitfield/css/TagItField.css");
	}
	
	
	function Type() {
		return 'tagitfield';
	}
	
	function Field() {
		// we have to set this URL at the last minute because $this->Link() does work in a setter
		if ($this->getIsAJAX()) {
			$this->settings['tagSourceURL'] = $this->Link('suggest');
		}
		$this->setAttribute('data-settings', Convert::array2json($this->settings));
		$availableTags = $this->getAvailableTags();
		if (count($availableTags) > 0) {
			$this->setAttribute('data-available-tags', implode("::", $availableTags));
		}
		
		$html = parent::Field();
		$this->includeRequirements();
		
		return $html;
	}
	
	function setDelimiter($string) {
		$this->settings['singleFieldDelimiter'] = $string;
	}
	function getDelimiter() {
		return $this->settings['singleFieldDelimiter'];
	}
	
	function setPlaceholder($string) {
		$this->settings['placeholderText'] = $string;
	}
	function getPlaceholder() {
		return $this->settings['placeholderText'];
	}
	
	function setAllowSpaces($boolean) {
		$this->settings['allowSpaces'] = $boolean;
	}
	function getAllowSpaces() {
		return $this->settings['allowSpaces'];
	}
	
	function setCaseSensitive($boolean) {
		$this->settings['caseSensitive'] = $boolean;
	}
	function getCaseSensitive() {
		return $this->settings['caseSensitive'];
	}
	
	function setTagSource($string) {
		$this->settings['tagSourceURL'] = $string;
	}
	function getTagSource() {
		return $this->settings['tagSourceURL'];
	}
	
	function useAJAX($boolean) {
		$this->useAJAX = $boolean;
		/*
		if ($boolean) {
			$this->settings['tagSourceURL'] = $this->Link('suggest');
		} else {
			$this->settings['tagSourceURL'] = null;
		}
		*/
	}
	function getIsAJAX() {
		return $this->useAJAX;
	//	return ($this->settings['tagSourceURL'] != null);
	}
	
	

	function setConfig($key, $value) {
		$this->settings[$key] = $value;
	}
	
	function getConfig($key) {
		return $this->settings[$key];
	}
	
	
	function setAvailableTags($tagArray) {
		$this->availableTags = $tagArray;
	}

	function getAvailableTags() {
		return $this->availableTags;
	}
	
	
	function suggest($request) {
		$searchString = $request->requestVar('search');
		
		$output = array();
		$output[] = 'test';
		$output[] = 'testing';
		$output[] = $searchString;
		
		$response = new SS_HTTPResponse(Convert::array2json($output));
		$response->addHeader('Content-Type', 'application/json');
		return $response;
	}
	
	function getTagsFromSource() {
		$output = array();
		$output[] = 'test';
		$output[] = 'testing';
		
		return $output;
	}
	
	
	/*
	function setValueX($value) {
		$this->valueArray = explode();
		
		return parent::setValue($value);
	}
	
	function dataValueX() {
		
	}
	*/
	
	
	
}