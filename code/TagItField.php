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
	
	var $tagClass = null;
	
	var $tagLabelField = 'Title';
	
	var $deleteUnusedTags = true;
	
	
	//var $valueArray = array();
	
	function __construct($name, $title = null, $value = "", $form = null) {
		

		/*
		if($has_manys = $controller->stat('has_many')) {
			foreach($has_manys as $relation => $value) {
				$name = $relation;
				$sourceClass = $value;
				break;
			}
		}
		*/
		
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
		
		$this->setAttribute('data-settings', Convert::array2json($this->settings));
		$availableTags = $this->getAvailableTags();
		if (count($availableTags) > 0) {
			$this->setAttribute('data-available-tags', implode("::", $availableTags));
		} 
		if ($this->getIsAJAX()) {
			// we have to set this URL at the last minute because $this->Link() does work in a setter
			$this->settings['tagSourceURL'] = $this->Link('suggest');
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
	
	function deleteUnusedTags($boolean) {
		$this->deleteUnusedTags = $boolean;
	}
	function getDeleteUnusedTags() {
		return $this->deleteUnusedTags;
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
	
	function setRelation($class = null, $field = null) {
		if ($class != null) {
			$this->tagClass = $class;
		}
		if ($field != null) {
			$this->tagLabelField = $field;
		}
	}
	
	
	function setAvailableTags($tagArray) {
		$this->availableTags = $tagArray;
	}
	
	/*
		Get a list of tags for pre-populating the autosuggest
	*/
	function getAvailableTags() {
		$tagArray = array();
		if (count($this->availableTags) > 0) {
			$tagArray = $this->availableTags;
		} else if ($tagClass = $this->getTagClass()) {
			$labelField = $this->getTagLabelField();
			if ($labelField) {
	//		$tags = DataObject::get($tagClass);
				$tags = DataList::create($tagClass)->sort($labelField);
				if ($tags) {
					$tagArray = $tags->map($labelField, $labelField)->toArray();
				}
				
			}
			
		}
		
		return $tagArray;
	}
	
	
	
	/*
		find out the class name of the tag objects
	*/
	function getTagClass() {
		if ($this->tagClass != null) {
			return $this->tagClass;
		} else if ($record = $this->form->record) {
			if ($relation = $this->getRelation()) {
				return $relation[1];
			}
		}
		
		return false;
	}
	
	
	function getTagLabelField() {
		return $this->tagLabelField;
	}
	
	/*
		Action for AJAX autocomplete
		@TODO: THIS
	*/
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
	
	function setValue($value, $obj = null) {
		if(!$value && $obj && $obj instanceof DataObject && $obj->many_many($this->name)) {
			$funcName = $this->name;
			$tags = $obj->$funcName()->map('ID', $this->getTagLabelField())->toArray();
			$value = implode($this->getDelimiter(), $tags);
		}
		$this->value = $value;
		return $this;
	}
	
	function saveInto($record) {
		if ($relation = $this->getRelation()) {
			$submittedTags = explode($this->getDelimiter(), $this->value);
			
			$tagClass = $this->getTagClass();
			$tagLabelField = $this->getTagLabelField();
			
			$tagObjects = DataList::create($tagClass)->filter(
				$tagLabelField, $submittedTags
			);
			
			if ($tagObjects->Count() < count($submittedTags)) {
				// filter out the tags that exist already
				$tagsAsKeys = array_flip($submittedTags);
				foreach ($tagObjects as $tag) {
					$label = $tag->{$tagLabelField};
					unset($tagsAsKeys[$label]);
				}
				
				foreach ($tagsAsKeys as $label => $value) {
					$tagObject = new $tagClass();
					$tagObject->$tagLabelField = $label;
					$tagObject->write();
					$tagObjects->add($tagObject);
				}
			}
			
			$relationList = $this->form->record->{$this->name}();
			$oldTags = $relationList->map('ID', $tagLabelField)->toArray();
			$relationList->removeAll();
			$relationList->addMany($tagObjects->toArray());
			
			if ($this->deleteUnusedTags) {
				$deletedTags = array_diff($oldTags, $tagObjects->map('ID', $tagLabelField)->toArray());
				
				if (count($deletedTags) > 0) {
					$relationTable = $relation[4];
					foreach ($deletedTags as $id => $title) {
						$query = new SQLQuery();
						$query->select = array('ID', $tagLabelField);
						$query->from = array($relationTable);
						$query->where = array("ID = ".$id);
						$count = $query->Count();
						if ($count == 0) {
							DataObject::delete_by_id($tagClass, $id);
						}
					}
					
				}
			}
			
		} else if ($record->hasField()) {
			$record->setCastedField($this->name, $this->dataValue());
		} else {
			// @TODO: better error handling
		}
		
		
	}
	
	function getRelation() {
		$relation = false;
		if ($manyMany = singleton($this->form->record->class)->many_many($this->name)) {
			$relation = $manyMany;
		}
		/* else if ($hasMany = singleton($this->form->record->class)->has_many($this->name)) {
			$relation = $hasMany;
		}
		*/
		return $relation;
	}

	
	
}