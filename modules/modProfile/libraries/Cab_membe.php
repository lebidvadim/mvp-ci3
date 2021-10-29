<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cab_membe
{
	public $CI;

	public function __construct($par)
	{
		$this->CI = $par;
	}

	public function load($page,$page_dop1,$page_dop2){
		$this->CI->data['active'] = ($page_dop1 != '' and !ctype_digit($page_dop1)) ? $page.'-'.$page_dop1 : $page;
		$this->CI->load->library('general',$this->CI);
		switch ($page_dop1)
		{
			case '':
				redirect(site_url('cabinet/profile/edit-profile'), 'location');
				break;
			case 'edit-profile':
				$this->CI->data['title'] = __('Редактировать профиль');
				$this->CI->data['breadcrumb']['title'] = __('Редактировать профиль');
				$this->CI->data['breadcrumb']['desc'] = __('Редактировать профиль пользователя');
				$content = $this->CI->general->editProfile();
				break;
			default: $content = message(__('Такой страницы нету.'),3);
		}
		return $content;
	}

}
