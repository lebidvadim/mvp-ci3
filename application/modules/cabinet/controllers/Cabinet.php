<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabinet extends MX_Controller
{
	public $logged_in;
	public $is_admin;
	public $data;
	public $setting;
	public $user;
	public $menu_config;

	public function __construct()
	{
		parent::__construct();

		$this->load->library(['cabinet/menu','modAuth/ion_auth']);
		$this->load->model(['modSettings/M_settings','modPages/M_pages','modUsers/M_users']);
		$this->menu_config = $this->menu->load('cabinet');

		$this->data['menu'] = $this->menu_config;
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

		if($this->is_admin === true)
			redirect(site_url('admin'), 'location');

	}
	public function index($page = 'home',$page_dop1 = '',$page_dop2 = '',$page_dop3 = ''){
		$this->data['active'] = $page;
		$check_lib = check_lib($page);

		if($check_lib == true) {

			$this->load->module('mod'.ucfirst($page));
			$mod = 'mod'.$page;
			$auth_role = substr($this->user->group->name, 0, 5);
			if(method_exists($this->$mod,'cab_'.$auth_role)) {
				$leb = 'cab_'.$auth_role;
				$content = $this->$mod->$leb($page, $page_dop1, $page_dop2);
			}
			else
				$content = message(__('Создайте метод cab_'.$auth_role.' в Mod'.ucfirst($page).'.'),3);

			$this->data['content'] = $content;
		}
		$this->parser->parse('cabinet/main', $this->data);
	}
}
