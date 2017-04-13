<?php

class URLify
{
	private $_alphabet = null; 
	private $_db = null;
	private $_data = null;
	private $_output = null;

	function __construct($action, $data, $db)
	{
		$this->_output = '';
		$this->_alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$this->_db = $db;
		$this->_data = $data;

		switch($action){
			case 'create':
				$this->_output = $this->_create_new_shortlink();
				break;
			case 'stats':
				$this->_output = $this->_db->stats();
				break;
			case 'get':
				if(isset($data['short_url']) && $content = $this->_db->get($data['short_url'])){
					$this->_output = ['text' => $content];
				}
				else{
					die_json('Invalid short url');
				}
				break;
			case 'remove':
				if(isset($data['short_url']) && $this->_db->remove($data['short_url']) === true){
					$this->_output = ['success' => 'Content has ben deleted'];
				}
				else{
					die_json($this->_db->remove($data['short_url']));
				}
				break;
		}
	}

	public function output($format='array'){
		return $format == 'json' ? json_encode($this->_output) : $this->_output;
	}

	private function _create_new_shortlink(){
		$hash = get_param('short_url');
		$text = get_param('text');

		if ( !$hash || ($hash != '' && $this->_db->is_set($hash)) ) {
			$hash = $this->_generate_new_hash();
		}

		return $this->_db->add($hash, $text);
	}

	private function _generate_new_hash(){
		date_default_timezone_set(TIMEZONE);

		$hashids = new Hashids\Hashids(HASH_SALT, 1, $this->_alphabet);
		$datestr = date('ymd') + rand(10000, 100000000);
		$hash = $hashids->encrypt($datestr);
		
		if ($this->_db->is_set($hash)) {
			return $this->_generate_new_hash();
		}

		return $hash;
	}

}

?>