<?php

class usmdb{

	private $_total_chars;
	private $_total_mb;
	private $_dict;

	function __construct(){
		$this->_total_chars = 0;
		$this->_total_mb = 0;
		$this->_dict = [];
	}

	public function json(){
		return json_encode($this->_dict);
	}

	public function add($key, $value){
		$validity = $this->_validate_for_insert($key, $value);

		if(!$validity['is_valid']){
			return ["error" => $validity['errors']];
		}

		$this->_total_chars += strlen($value);
		$this->_total_mb += (mb_strlen($value, '8bit') + mb_strlen($key, '8bit'));
		$this->_dict[$key] = $value;

		return ["short_url" => $key, "text" => $this->_dict[$key]];
	}

	public function get($key){
		return $this->is_set($key) ? $this->_dict[$key] : false;
	}

	public function remove($key){
		if(!$this->is_set($key)){
			return ["error" => "Short url does not exist"];
		}

		$el = $this->_dict[$key];

		unset($this->_dict[$key]);

		$this->_total_chars -= strlen($el);
		$this->_total_mb -= (mb_strlen($el, '8bit') + mb_strlen($key, '8bit'));

		return true;
	}

	public function is_set($key){
		return isset($this->_dict[$key]);
	}

	public function stats(){
		$out = [];
		$out['total_snippets'] = count($this->_dict);
		$out['total_size_in_mb'] = ($this->_total_mb/1048576);
		$out['total_characters_stored'] = $this->_total_chars;
		return $out;
	}

	private function _validate_for_insert($key, $value){
		$ret = ['is_valid' => false, 'errors' => ''];

		//text cannot be empty
		if($value == ''){
			$ret['errors'] = "Text is required";
			return $ret;
		}

		//key cannot be less or more than 6 chars
		if(strlen($key) != 6){
			$ret['errors'] = "Short url should be 6 characters";
			return $ret;
		}

		//check if already exists
		if($this->is_set($key)){
			$ret['errors'] = "Short url already exists";
			return $ret;
		}

		return ['is_valid' => true];
	}
}