<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModUsers extends MX_Controller
{
	public $data;
	public $logged_in;
	public $user;
	public function __construct()
	{
		parent::__construct();
		$this->load->library(['modAuth/ion_auth']);
		$this->load->model(['M_users']);
		$this->logged_in = $this->ion_auth->logged_in();
		if($this->logged_in === true)
			$this->user = $this->ion_auth->get_user();
		else
			$this->user = NULL;

		if($this->logged_in === false)
			redirect(site_url(LOGIN_PAGE), 'location');

		$this->data['user'] = $this->user;
	}
	public function menu($type)
	{
		$menu = [];
		$menu['admin'][] = [
			'order' => 1,
			'disable' => 0,
			'url' => site_url('admin/users'),
			'title' => 'Пользователи',
			'active' => 'users',
			'icon' => '<i class="fas fa-users"></i>',
			'type' => 'sys',
			'submenu' => [
				[
					'order' => 1,
					'disable' => 0,
					'url' => site_url('admin/users/all'),
					'title' => 'Пользователи',
					'active' => ['all','edit','del'],
				],
				[
					'order' => 2,
					'disable' => 0,
					'url' => site_url('admin/users/add'),
					'title' => 'Добавить пользователя',
					'active' => 'add',
				]
			]
		];

		if(!empty($menu[$type]))
			return $menu[$type];
		else
			return [];
	}
	public function load_ad($page,$page_dop1,$page_dop2){
		$this->data['menu'] = $this->menu('admin');
		$this->data['menu_act'] = $this->data['menu'][0];
		$this->load->library('admin_users',$this);
		return $this->admin_users->load($page,$page_dop1,$page_dop2);
	}
	public function ajax($page,$page_dop1 = '',$page_dop2 = ''){
		$this->load->library('ajax',$this);
		return $this->ajax->load($page,$page_dop1,$page_dop2);
	}
}
