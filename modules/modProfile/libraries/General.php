<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General
{
	public $CI;

	public function __construct($par)
	{
		$this->CI = $par;
	}
	public function editProfile(){
		if($this->CI->input->post('type_form') == 0)
			$this->editForm();

		if($this->CI->input->post('type_form') == 1)
			$this->changePasswordForm();

		return $this->CI->parser->parse('modProfile/profile/edit-profile', $this->CI->data, true);
	}
	private function editForm()
	{
		$this->CI->form_validation->set_rules('first_name', __('Имя'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Имя')]),
		]);
		$this->CI->form_validation->set_rules('last_name', __('Фамилия'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Фамилия')]),
		]);

		if ($this->CI->form_validation->run() === TRUE)
		{
			$arr['first_name'] = $this->CI->input->post('first_name');
			$arr['last_name'] = $this->CI->input->post('last_name');
			$arr['company'] = $this->CI->input->post('company');
			$arr['phone'] = $this->CI->input->post('phone');
			$this->CI->M_users->up($this->CI->user->user_id, $arr);
			$this->CI->session->set_flashdata('message', message(__('Вы успешно отредактировали данные.')));
			if(!$this->CI->is_admin)
				redirect(site_url('cabinet/profile/edit-profile'), 'location');
			else
				redirect(site_url('admin/profile/edit-profile'), 'location');
		}
	}
	private function changePasswordForm()
	{
		$this->CI->form_validation->set_rules('password', __('Пароль'), 'required|min_length[' . $this->CI->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Пароль')]),
			'min_length' => __('Поле [input] должно содержать не менее [length] символов.',[__('Пароль'),$this->CI->config->item('min_password_length', 'ion_auth')]),
			'matches' => __('Поле [input] не соответствует полю [input1].',[__('Пароль'),__('Повторить пароль')]),
		]);
		$this->CI->form_validation->set_rules('password_confirm', __('Повторить пароль'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Повторить пароль')])
		]);

		if ($this->CI->form_validation->run() === TRUE)
		{
			$arr['password'] = $this->CI->ion_auth_model->hash_password($this->CI->input->post('password'));
			$this->CI->M_users->up($this->CI->user->user_id,$arr);
			$this->CI->session->set_flashdata('message', message(__('Вы успешно изменили пароль.')));
			if(!$this->CI->is_admin)
				redirect(site_url('cabinet/profile/edit-profile'), 'location');
			else
				redirect(site_url('admin/profile/edit-profile'), 'location');
		}
	}
}
