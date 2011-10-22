<?php
/*

	add FMTagField class, either in 'class' => 'text fmtagfield' . ($this->extraClass() ? $this->extraClass() : ''),
		or via addExtraClass()
*/
class TagItField extends TextField {
	
	var $delimiter = ',';
	var $allowSpaces = false;
	
	
	//var $dataDelimiter = ',';
	
	var $valueArray = array();
	
	function __construct($name, $title = null, $value = "", $maxLength = null, $form = null) {
		$this->maxLength = $maxLength;
		
		parent::__construct($name, $title, $value, $form);
	}
	
	
	function includeRequirements() {
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-ui/jquery.ui.core.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-ui/jquery.ui.widget.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-ui/jquery.ui.autocomplete.js");
		
		Requirements::javascript("tagitfield/thirdparty/tag-it/tag-it.js");
		Requirements::javascript("tagitfield/javascript/TagItField.js");
		
		Requirements::css(THIRDPARTY_DIR."/jquery-ui-themes/smoothness/jquery.ui.all.css");
		Requirements::css("tagitfield/thirdparty/tag-it/jquery.tagit.css");
	}
	
	function Field() {
		$this->includeRequirements();
	
		$attributes = array(
			'type' => 'text',
			'class' => 'text tagitfield ' . ($this->extraClass() ? $this->extraClass() : ''),
			'id' => $this->id(),
			'name' => $this->Name(),
			'value' => $this->Value(),
			'tabindex' => $this->getTabIndex(),
			'maxlength' => ($this->maxLength) ? $this->maxLength : null,
			'size' => ($this->maxLength) ? min( $this->maxLength, 30 ) : null 
		);
		
		if($this->disabled) {
			$attributes['disabled'] = 'disabled';
		}
		
		$attributes['data-delimiter'] = $this->delimiter;
		$attributes['data-allowspaces'] = $this->allowSpaces;
		
		return $this->createTag('input', $attributes);
	}
	
	
	function setDelimiter($string) {
		$this->delimiter = $string;
	}
	function getDelimiter() {
		return $this->delimiter;
	}
	
	function setAllowSpaces($string) {
		$this->allowSpaces = $string;
	}
	function getAllowSpaces() {
		return $this->allowSpaces;
	}

	/*
	function setDataDelimiter($string) {
		$this->dataDelimiter = $string;
	}
	function getDataDelimiter() {
		return $this->dataDelimiter;
	}
	*/
	
	
	function setValueX($value) {
		$this->valueArray = explode();
		
		return parent::setValue($value);
	}
	
	function dataValueX() {
		
	}
	
	
	
	
}