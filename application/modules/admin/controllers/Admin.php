<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller
{
	public $menu_config;
	public $logged_in;
	public $is_admin;
	public $data;
	public $setting;
	public $user;

    public function __construct()
    {
        parent::__construct();

		$this->load->library(['admin/menu','session','modAuth/ion_auth']);
		$this->menu_config = $this->menu->load();
		$this->data['menu'] = $this->menu_config;

		$this->load->model(['modSettings/M_settings','modPages/M_pages','modUsers/M_users']);
		$this->setting = $this->M_settings->get(['where' => ['id' => 1], 'result' => 'row']);
		$this->logged_in = $this->ion_auth->logged_in();
		$this->is_admin = $this->ion_auth->is_admin();
		$this->data['logged_in'] = $this->logged_in;
		$this->data['theme'] = $this->parser;
		if($this->logged_in === true)
			$this->user = $this->ion_auth->get_user();
		else
			$this->user = NULL;
		$this->data['user'] = $this->user;

		if ($this->session->flashdata('message') != '')
			$this->data['message'] = $this->session->flashdata('message');

		if($this->logged_in === false)
			redirect(site_url(LOGIN_PAGE), 'location');


        if(!$this->is_admin)
        	show_error('У вас нет прав доступа к админке.', 303, $heading = 'Доступ закрыт');

		//$this->output->enable_profiler(TRUE);
    }
    public function index($id = 'home',$page = '',$page_dop1 = '',$page_dop2 = '')
	{

		$this->data['active'] = $id;

		$check_lib = check_lib($id);

		$check_lib_menu = check_lib_menu($this->menu_config,$id);
		switch ($id)
		{
			case 'home':
				$this->data['content'] = 'home-admin';
				break;
			case $check_lib === true:

				$this->load->module('mod'.ucfirst($id));
				$mod = 'mod'.$id;

				if(method_exists($this->$mod,'load_ad'))
					$content = $this->$mod->load_ad($page,$page_dop1,$page_dop2);
				else
					$content = message(__('Создайте метод load_ad в Mod'.ucfirst($id).'.'),3);

				$this->data['content'] = $content;
				break;
			case $check_lib_menu === 0:
				$this->data['content'] = message(__('Создайте модуль Mod'.ucfirst($id).'.'),3);
				break;
			default: $this->data['content'] = message(__('Такой страницы нету.'),3);
		}
        $this->parser->parse('admin/main', $this->data);
    }
}
