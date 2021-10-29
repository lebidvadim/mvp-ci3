<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModPages extends MX_Controller
{
	public $data;
	public $user;
	public function __construct()
	{
		parent::__construct();
		$this->load->library(['modAuth/ion_auth']);

		if($this->ion_auth->logged_in() === true)
			$this->user = $this->ion_auth->get_user();
		else
			$this->user = NULL;

		$this->load->model(['M_pages']);
	}
	public function menu($type)
	{
		$menu = [];
		$menu['admin'][] = [
			'order' => 3,
			'disable' => 0,
			'url' => site_url('admin/pages'),
			'title' => __('Страницы'),
			'active' => 'pages',
			'icon' => '<i class="fas fa-file"></i>',
			'type' => 'sys',
			'submenu' => [
				[
					'order' => 1,
					'url' => site_url('admin/pages/all'),
					'title' => __('Все'),
					'active' => ['all','edit','del'],
				],
				[
					'order' => 2,
					'url' => site_url('admin/pages/add'),
					'title' => __('Добавить'),
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
		$this->load->library('admin_pages',$this);
		return $this->admin_pages->load($page,$page_dop1,$page_dop2);
	}
	public function ValidPagesName($str)
	{
		$page = $this->M_pages->get_num(['where' => ['name' => transliterate(strtolower($str))]]);
		if ($page == 1)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
