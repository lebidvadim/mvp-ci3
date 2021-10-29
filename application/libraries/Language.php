<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Language {

	private static $props = array();
	private static $instance;
	protected $CI;
	public static $deft_lang;

	private function __construct(){
		$this->CI =& get_instance();
		self::$deft_lang = $this->CI->router->user_lang;
	}

	public static function getInstance(){
		if (empty(self::$instance)) {
			self::$instance = new Language();
		}
		self::setLang();
		return self::$instance;
	}

	private static function setLang(){

		$files = scandir(APPPATH . '/modules');
		foreach ($files as $p => $pa) {
			if($pa != '.' and $pa != '..') {
				$files[$p] = APPPATH . 'modules/' . $pa.'/language/'.self::$deft_lang.'.json';
			}
		}
		if(is_dir(FCPATH.'/modules')) {
			$files_pak = scandir(FCPATH.'/modules');
			foreach ($files_pak as $p => $pa) {
				$str = substr($pa, 0, 3);
				if (!in_array($pa, $files) and $str == 'mod') {
					array_push($files, FCPATH.'modules/'.$pa.'/language/'.self::$deft_lang.'.json');
				}
			}
		}

		$files_new = [];
		foreach ($files as $val){
			if($val != '.' and $val != '..'){
				$files_new[] = $val;
			}
		}
		self::$props = [];
		self::$props[self::$deft_lang] = [];
		foreach ($files_new as $l => $la)
		{
			if(file_exists($la)) {
				$json = file_get_contents($la);
				if(json_decode($json, true))
					self::$props[self::$deft_lang] += json_decode($json, true);
			}
		}
		//pri(self::$props);
	}

	public function search($key, $replace = NULL){
		if (!empty(self::$props[self::$deft_lang])) {
			if (array_key_exists($key, self::$props[self::$deft_lang]))
				$data = self::$props[self::$deft_lang][$key];
			else $data = $key;
		} else $data = $key;

		preg_match_all("'\\[(.+?)\\]'si", $key,$search);
		$search = $replace == NULL ? null : $search[0];

		return $replace != NULL ? str_replace($search, $replace, $data) : $data;
	}
}
