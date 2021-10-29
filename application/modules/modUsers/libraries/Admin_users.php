<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_users
{
	public $CI;
	public function __construct($par = [])
	{
		$this->CI = $par;
		$this->CI->config->load('ion_auth', TRUE);
		$this->CI->load->library(['modAuth/ion_auth']);
	}
	public function load($page,$page_dop1,$page_dop2){
		if($page == '') $page = 'all';
		$this->CI->data['active'] = ($page_dop1 != '' and !ctype_digit($page_dop1)) ? $page.'-'.$page_dop1 : $page;
		switch ($page)
		{
			case '' or $page == 'all':
				$this->CI->data['title'] = __('Пользователи');
				$this->CI->data['breadcrumb']['title'] = __('Пользователи');
				$this->CI->data['breadcrumb']['desc'] = __('Страница всех пользователей');
				$content = $this->all();
				break;
			case 'add':
				$this->CI->data['title'] = __('Добавить пользователя');
				$this->CI->data['breadcrumb']['title'] = __('Добавить пользователя');
				$this->CI->data['breadcrumb']['desc'] = __('Страница добавить пользователя');
				$content = $this->add();
				break;
			case $page == 'edit' and $page_dop1 != 0 and $this->CI->M_users->get_num(['where' => ['id' => $page_dop1]]):
				$this->CI->data['title'] = __('Редактировать пользователя');
				$this->CI->data['breadcrumb']['title'] = __('Редактировать пользователя');
				$this->CI->data['breadcrumb']['desc'] = __('Страница редактировать пользователя');
				$content = $this->edit($page_dop1);
				break;
			default: $content = message(__('Такой страницы нету.'),3);
		}
		$this->CI->data['content'] = $content;
		return $this->CI->parser->parse('modUsers/admin/users/users', $this->CI->data, true);
	}
	public function all()
	{
		$count = $this->CI->M_users->get_num();
		$pages = pagination(site_url('admin/users'),10,$count);
		$this->CI->data['pagination'] = $pages[0];
		$this->CI->data['users'] = $this->CI->M_users->get(['limit' => $pages[1]]);
		return $this->CI->parser->parse('modUsers/admin/users/users-all', $this->CI->data, true);
	}
	public function add()
	{
		$this->addForm();
		return $this->CI->parser->parse('modUsers/admin/users/users-add', $this->CI->data, true);
	}
	private function addForm()
	{
		$tables = $this->CI->config->item('tables', 'ion_auth');
		$identity_column = $this->CI->config->item('identity', 'ion_auth');
		$this->CI->data['identity_column'] = $identity_column;

		$this->CI->form_validation->set_rules('identity', __('Логин'), 'trim|required|is_unique[' . $tables['users'] . '.username]',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Логин')]),
			'is_unique' => __('Поле [input] должно содержать уникальное значение.',[__('Логин')])
		]);
		$this->CI->form_validation->set_rules('email', __('Эмаил'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Эмаил')]),
			'is_unique' => __('Поле [input] должно содержать уникальное значение.',[__('Эмаил')]),
			'valid_email' => __('Поле [input] должно быть вида емаил.',[__('Эмаил')]),
		]);
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
			$email = strtolower($this->CI->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->CI->input->post('identity');
			$password = $this->CI->input->post('password');
			$additional_data = [
				'active' => 1,
			];
			if ($this->CI->ion_auth->register($identity, $password, $email, $additional_data))
			{
				$this->CI->session->set_flashdata('message', message(__('Вы успешно добавили оператора')));
				redirect(site_url('admin/users'), 'location');
			}
		}
	}
	public function edit($id)
	{
		if($this->CI->input->post('type_form') == 0)
			$this->editForm($id);

		if($this->CI->input->post('type_form') == 1)
			$this->changePasswordForm($id);

		$user_edit = $this->CI->M_users->get(['where' => ['users.id' => $id],'result' => 'row']);

		$this->CI->data['user_edit'] = $user_edit;

		return $this->CI->parser->parse('modUsers/admin/users/users-edit', $this->CI->data, true);
	}
	private function editForm($id)
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
			if($this->CI->input->post('active') == null)
				$arr['active'] = 0;
			else
				$arr['active'] = 1;

			$this->CI->M_users->up($id, $arr);
			$this->CI->session->set_flashdata('message', message(__('Вы успешно отредактировали данные пользователя.')));
			redirect(site_url('admin/users'), 'location');
		}
	}
	private function changePasswordForm($id)
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
			$this->CI->M_users->up($id,$arr);
			$this->CI->session->set_flashdata('message', message(__('Вы успешно изменили пароль пользователю.')));
			redirect(site_url('admin/users'), 'location');
		}
	}
	private function withMoney($id)
	{
		$this->CI->form_validation->set_rules('balance', __('Баланс'), 'required|numeric',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Баланс')]),
			'numeric' => __('Поле [input] должно содеражть только цифры.',[__('Баланс')]),
		]);

		if ($this->CI->form_validation->run() === TRUE)
		{
			$user = $this->CI->M_users->get(['where' => ['users.id' => $id],'result' => 'row']);
			if($user->balance >= $this->CI->input->post('balance') and $this->CI->input->post('balance') != 0 and gmp_sign($this->CI->input->post('balance')) == 1) {
				$this->CI->M_users->up($id, ['balance' => $user->balance - $this->CI->input->post('balance')]);

				$arr_w['id_us'] = $user->id;
				$arr_w['operation'] = 4;
				$arr_w['date'] = time();
				$arr_w['amount'] = $this->CI->input->post('balance');
				$this->CI->M_wallet->ins($arr_w);

				$this->CI->session->set_flashdata('message', message(__('Вы успешно вывели пользователю <b>[name]:</b> [money]$.', [$user->username, $this->CI->input->post('balance')])));
				redirect(site_url('admin/users'), 'location');
			}
			else{
				$this->CI->session->set_flashdata('message', message(__('У оператора недостаточно средств на балансе для вывода.'),3));
				redirect(site_url('admin/users/edit/'.$id), 'location');
			}
		}
	}
}
