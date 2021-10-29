<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu
{
	public $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	public function load()
	{
		$menu = [];
		/*$menu[] = [
			'order' => 1,
			'disable' => 0,
			'url' => site_url('admin'),
			'title' => __('Главная'),
			'icon' => '<i class="fas fa-home"></i>',
			'active' => 'home',
			'type' => 'sys'
		];*/

		$check_lib = check_lib_all();

		foreach ($check_lib as $m)
		{
			if(substr($m, 0,3) == 'mod') {
				$mod_load = $m;
				$mod = strtolower($m);
				$this->CI->load->module($mod_load);
				if (method_exists($this->CI->$mod, 'menu')) {
					if ($this->CI->$mod->menu('admin')) {
						$men = $this->CI->$mod->menu('admin');
						if(count($men) > 1) {
							foreach ($men as $admin){
								array_push($menu, $admin);
							}
						}
						else {
							array_push($menu, $men[0]);
						}
					}
				}
			}
		}
		//pri($menu);
		array_multisort(array_column($menu, 'order'), SORT_ASC, $menu);


		foreach ($menu as $k => $m)
			//pri($m);
			if (!empty($m['submenu']))
				array_multisort(array_column($m['submenu'], 'order'), SORT_ASC, $menu[$k]['submenu']);

		return $menu;
	}
}
