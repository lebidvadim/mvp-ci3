<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_auth
{
	public $CI;
	public function __construct($par = [])
	{
		if(!empty($par))
		{
			$this->CI = $par;
		}
		else{
			$this->CI =& get_instance();
		}
		$this->CI->data['theme'] = $this->CI->parser;
	}

}
