<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_settings
{
	public $CI;
	public function __construct($par = [])
	{
		if(!empty($par))
		{
			$this->CI = $par;
		}
		else{
			$this->CI =& get_instance();
		}
		$this->CI->load->model(['M_settings']);
	}
	public function load($page,$page_dop1,$page_dop2){
		switch ($page)
		{
			case '':
				$this->CI->data['title'] = __('Настройки');
				$this->CI->data['breadcrumb']['title'] = __('Настройки');
				$this->CI->data['breadcrumb']['desc'] = __('Страница настройки системы');
				$content = $this->all();
				break;
			default: $content = message(__('Такой страницы нету.'),3);
		}
		return $content;
	}
	public function all()
	{
		$setting = $this->CI->M_settings->get(['where' => ['id' => 1], 'result' => 'row']);
		$this->CI->data['setting'] = $setting;
		//$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('title', __('Заголовок сайта'), 'required', [
				'required' => __('Поле [input] обязательно для заполнения.',[__('Заголовок сайта')]),
		]);
		$this->CI->form_validation->set_rules('desc', __('Описание проекта'), 'required', [
				'required' => __('Поле [input] обязательно для заполнения.',[__('Описание проекта')])
		]);
		if ($this->CI->form_validation->run() == true)
		{
			$arr['title'] = $this->CI->input->post('title');
			$arr['desc'] = $this->CI->input->post('desc');
			if($this->CI->input->post('status') == null)
				$arr['status'] = 0;
			else
				$arr['status'] = 1;
			$this->CI->M_settings->up(1,$arr);
			$this->CI->session->set_flashdata('message', message(__('Вы успешно отредактировали настройки.')));
			redirect(site_url('admin/settings'), 'location');
		}
		return $this->CI->parser->parse('modSettings/admin/settings', $this->CI->data, true);
	}
}
