<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends MX_Controller
{
	public function index()
	{
		$this->load->library('migration');

		if ($this->migration->latest() === FALSE)
		{
			show_error($this->migration->error_string());
		}
	}

}