<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModSettings extends MX_Controller
{
	public $data;
	public function __construct()
	{
		parent::__construct();
	}
	public function menu($type)
	{
		$menu = [];
		$menu['admin'][] = [
			'order' => 1000,
			'disable' => 0,
			'url' => site_url('admin/settings'),
			'title' => __('Настройки'),
			'active' => 'settings',
			'icon' => '<i class="fas fa-cogs"></i>',
			'type' => 'sys',
		];
		if(!empty($menu[$type]))
			return $menu[$type];
		else
			return [];
	}
	public function load_ad($page,$page_dop1,$page_dop2){
		$this->data['menu'] = $this->menu('admin');
		$this->data['menu_act'] = $this->data['menu'][0];
		$this->load->library('admin_settings',$this);
		return $this->admin_settings->load($page,$page_dop1,$page_dop2);
	}
}
