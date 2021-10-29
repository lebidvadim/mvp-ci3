<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_profile
{
	public $CI;
	public function __construct($par = [])
	{
		if (!empty($par)) {
			$this->CI = $par;
		} else {
			$this->CI =& get_instance();
		}
	}
	public function load($page,$page_dop1,$page_dop2){
		$this->CI->data['active'] = ($page_dop1 != '' and !ctype_digit($page_dop1)) ? $page.'-'.$page_dop1 : $page;
		$this->CI->load->library('modProfile/general',$this->CI);
		switch ($page)
		{
			case '':
				redirect(site_url('admin/profile/edit-profile'), 'location');
				break;
			case 'edit-profile':
				$this->CI->data['title'] = __('Редактировать профиль');
				$this->CI->data['breadcrumb']['title'] = __('Редактировать профиль');
				$this->CI->data['breadcrumb']['desc'] = __('Редактировать профиль пользователя');
				$content = $this->CI->general->editProfile();
				break;
			default: $content = message(__('Такой страницы нету.'),3);
		}
		$this->CI->data['content'] = $content;
		return $this->CI->parser->parse('modProfile/profile/main', $this->CI->data, true);
	}
}
