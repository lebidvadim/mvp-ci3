<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModProfile extends MX_Controller
{
	public $data;
	public $logged_in;
	public $is_admin;
	public $user;
	public function __construct()
	{
		parent::__construct();
		$this->load->library(['modAuth/ion_auth']);
		$this->load->model(['modSettings/M_settings','modUsers/M_users']);
		$this->setting = $this->M_settings->get(['where' => ['id' => 1], 'result' => 'row']);

		$this->logged_in = $this->ion_auth->logged_in();
		$this->is_admin = $this->ion_auth->is_admin();
		if($this->logged_in === true)
			$this->user = $this->ion_auth->get_user();
		else
			$this->user = NULL;

		$this->data['user'] = $this->user;

		if($this->logged_in === false)
			redirect(site_url(LOGIN_PAGE), 'location');
	}
	public function menu($type)
	{
		$menu = [];
		$menu['admin'][] = [
			'order' => 1,
			'disable' => 1,
			'url' => site_url('admin/profile'),
			'title' => __('Профиль'),
			'active' => 'profile',
			'icon' => '<i class="fas fa-user-tie"></i>',
			'type' => 'sys',
		];
		$menu['cabinet'][] = [
			'order' => 1,
			'disable' => 1,
			'url' => site_url('cabinet/profile'),
			'title' => __('Профиль'),
			'active' => 'profile',
			'icon' => '<i class="fas fa-user-tie"></i>',
			'type' => 'sys',
		];
		if(!empty($menu[$type]))
			return $menu[$type];
		else
			return [];
	}
	public function load_ad($page,$page_dop1,$page_dop2){
		if(!empty($this->menu('cabinet'))) {
			$this->data['menu'] = $this->menu('cabinet');
			$this->data['menu_act'] = $this->data['menu'][0];
		}
		$this->load->library('admin_profile',$this);
		return $this->admin_profile->load($page,$page_dop1,$page_dop2);
	}

	public function cab_membe($page,$page_dop1,$page_dop2){
		if(!empty($this->menu('cabinet'))) {
			$this->data['menu'] = $this->menu('cabinet');
			$this->data['menu_act'] = $this->data['menu'][0];
		}
		$this->load->library('cab_membe',$this);
		return $this->cab_membe->load($page,$page_dop1,$page_dop2);
	}
}
