<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends MX_Controller {

	public $logged_in;
	public $is_admin;
	public $data;
	public $setting;
	public $user;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->library(['modAuth/ion_auth','session']);
		$this->load->model(['modSettings/M_settings','modPages/M_pages','modUsers/M_users']);
		$this->setting = $this->M_settings->get(['where' => ['id' => 1], 'result' => 'row']);
		$this->logged_in = $this->ion_auth->logged_in();
		$this->is_admin = $this->ion_auth->is_admin();
		$this->data['logged_in'] = $this->logged_in;
		$this->data['theme'] = $this->parser;

		if ($this->logged_in === true)
			$this->user = $this->ion_auth->get_user();
		else {
			//redirect(site_url(LOGIN_PAGE), 'location');
			$this->user = NULL;
		}
		/*if ($this->is_admin === true)
			redirect(site_url('admin'), 'location');
		else
			if($this->logged_in === true)
				redirect(site_url('cabinet'), 'location');*/

		$this->data['user'] = $this->user;

		if ($this->session->flashdata('message') != '')
			$this->data['message'] = $this->session->flashdata('message');
		if ($this->session->flashdata('error') != '')
			$this->data['error'] = $this->session->flashdata('error');

    }
    public function index($page = '', $page_id = '', $page_dop1 = '', $page_dop2 = '')
	{
		$check_lib = check_lib($page);
		if($page == '')
			$page_arr = ['home' => 1,'status' => 1];
		else
			$page_arr = ['name' => $page,'status' => 1];
		if($this->setting->status == 1 or $this->is_admin)
		{
			if ($this->M_pages->get_num(['where' => $page_arr]) == 1)
			{
				$pag = $this->M_pages->get(['where' => $page_arr, 'result' => 'row']);
				$this->data['title'] = $this->setting->title . ' - ' . $pag->title;
				$this->data['content'] = $pag->text;
			}
			elseif ( $check_lib === true)
			{
				$rest = substr($page, 0, 3);
				if($rest == 'mod') {
					$page = lcfirst(substr($page, 3));
				}
				$this->load->module('mod' . ucfirst($page));
				$mod = 'mod' . $page;
				if (method_exists($this->$mod, 'load_si'))
					$content = $this->$mod->load_si($page, $page_id, $page_dop1, $page_dop2);
				else
					$content = message(__('Создайте метод load_si в Mod' . ucfirst($page) . '.'), 3);
				$this->data['content'] = $content;
			}
			else
				$this->data['content'] = $this->parser->parse('404', $this->data, true);

			$this->parser->parse('site/main', $this->data);
		}
		else
			$this->parser->parse('off', $this->data);

	}
	public function ajax($page)
	{
		$mes = '';
		$this->load->library('ajax',$this);
		switch ($page)
		{
			case 'noregion':
				$this->ajax->noregion();
				break;
			default: $mes = __('Такой страницы нету.');
		}
		return $mes;
	}
}
