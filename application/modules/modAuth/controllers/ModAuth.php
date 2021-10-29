<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ModAuth extends MX_Controller
{
	public $data;
	public $setting;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('ion_auth', TRUE);
		$this->load->library(['form_validation','modAuth/ion_auth']);
		$this->load->model(['modSettings/M_settings','modUsers/M_users']);
		$this->load->helper(['url', 'language']);
		$this->lang->load('auth',$this->router->lang);
		$this->data['theme'] = $this->parser;
		$this->data['logged_in'] = $this->ion_auth->logged_in();
		$this->setting = $this->M_settings->get(['where' => ['id' => 1], 'result' => 'row']);
	}

	public function login()
	{
		$this->data['title'] = $this->setting->title.' - '.'Вход';

		// validate form input
		$this->form_validation->set_rules('identity', __('Эмаил'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Эмаил')])
		]);
		$this->form_validation->set_rules('password', __('Пароль'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Пароль')])
		]);

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', message($this->ion_auth->messages()));

				if($this->ion_auth->is_admin() == true)
					redirect(site_url('admin'), 'location');
				else
					redirect(site_url('cabinet'), 'location');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('error', message($this->ion_auth->errors()),3);
				redirect(site_url(LOGIN_PAGE), 'location'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			if($this->ion_auth->logged_in() === true)
				redirect(site_url(), 'location');
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['error'] = ($this->session->flashdata('error')) ? $this->session->flashdata('error') : '';

			$this->data['message'] = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';

			$this->data['remember_users'] = $this->config->item('remember_users', 'ion_auth');

			$this->data['content'] = $this->parser->parse('modAuth/site/auth/login', $this->data,true);
			$this->parser->parse('site/main', $this->data);
		}
	}
	public function logout()
	{
		// log the user out
		$this->ion_auth->logout();

		// redirect them to the login page
		redirect(site_url(LOGIN_PAGE), 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			if($this->ion_auth->logged_in() === true)
				redirect(site_url(), 'location');
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			// render
			$this->_render_page('auth/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		$this->data['title'] = $this->setting->title.' - '.__('Забыли пароль');

		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', __('Логин'), 'required',[
				'required' => __('Поле [input] обязательно для заполнения.',[__('Логин')]),
			]);
		}
		else
		{
			$this->form_validation->set_rules('identity', __('Эмаил'), 'required|valid_email',[
				'required' => __('Поле [input] обязательно для заполнения.',[__('Эмаил')]),
				'valid_email' => __('Поле [input] должно содержать действительный адрес электронной почты.',[__('Эмаил')]),
			]);
		}

		if ($this->form_validation->run() === FALSE)
		{
			if($this->ion_auth->logged_in() === true)
				redirect(site_url(), 'location');
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
			];

			// set any errors and display the form
			$this->data['error'] = ($this->session->flashdata('error')) ? $this->session->flashdata('error') : '';
			$this->data['message'] = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
			$this->data['content'] = $this->parser->parse('modAuth/site/auth/reset-password', $this->data,true);
			$this->parser->parse('site/main', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{

				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('error', message($this->ion_auth->errors(),3));
				redirect(site_url("recover"), 'location');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', message($this->ion_auth->messages()));
				redirect(site_url(LOGIN_PAGE), 'location'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('error', message($this->ion_auth->errors(),3));
				redirect(site_url("recover"), 'location');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$this->data['title'] = $this->setting->title.' - '.__('Изменить пароль');

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', __('Новый пароль'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]',[
				'required' => __('Поле [input] обязательно для заполнения.',[__('Новый пароль')]),
				'min_length' => __('Поле [input] должно содержать не менее [length] символов.',[__('Новый пароль'),$this->config->item('min_password_length', 'ion_auth')]),
				'matches' => __('Поле [input] не соответствует полю [input1].',[__('Новый пароль'),__('Новый пароль еще раз')]),
			]);
			$this->form_validation->set_rules('new_confirm', __('Новый пароль еще раз'), 'required',[
				'required' => __('Поле [input] обязательно для заполнения.',[__('Новый пароль еще раз')])
			]);

			if ($this->form_validation->run() === FALSE)
			{
				if($this->ion_auth->logged_in() === true)
					redirect(site_url(), 'location');
				// display the form

				// set the flash data error message if there is one
				$this->data['error'] = ($this->session->flashdata('error')) ? $this->session->flashdata('error') : '';
				$this->data['message'] = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = [
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'class'=> (form_error('new')) ? 'form-control is-invalid' : 'form-control',
					'placeholder' => __('Новый пароль'),
				];
				$this->data['new_password_confirm'] = [
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'class'=> (form_error('new_confirm')) ? 'form-control is-invalid' : 'form-control',
					'placeholder' => __('Новый пароль еще раз'),
				];
				$this->data['user_id'] = [
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				];
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->data['content'] = $this->parser->parse('modAuth/site/auth/reset-password-form', $this->data,true);
				$this->parser->parse('site/main', $this->data);
			}
			else
			{
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				// do we have a valid request?
				if ($user->id != $this->input->post('user_id'))
				{
					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($identity);

					show_error(__('Форма не прошла проверку безопасности.'));
				}
				else
				{
					// finally change the password
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', message($this->ion_auth->messages()));
						redirect(site_url(LOGIN_PAGE), 'location');
					}
					else
					{
						$this->session->set_flashdata('error', message($this->ion_auth->errors()));
						redirect(site_url('recovery-ver/' . $code), 'location');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect(site_url('recover'), 'location');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		$activation = FALSE;

		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', message($this->ion_auth->messages()));
			redirect(site_url(LOGIN_PAGE), 'location');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect(site_url("recover"), 'location');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			show_error('You must be an administrator to view this page.');
		}

		$id = (int)$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() === FALSE)
		{
			if($this->ion_auth->logged_in() === true)
				redirect(site_url(), 'location');
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect(site_url(), 'location');
		}
	}

	/**
	 * Create a new user
	 */
	public function register()
	{
		$this->data['title'] = $this->setting->title.' - '.__('Создать пользователя');

		if ($this->ion_auth->logged_in() || $this->ion_auth->is_admin())
		{
			redirect(site_url(), 'location');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		$this->form_validation->set_rules('identity', __('Логин'), 'trim|required|is_unique[' . $tables['users'] . '.username]',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Логин')]),
			'is_unique' => __('Поле [input] должно содержать уникальное значение.',[__('Логин')]),
		]);

		$this->form_validation->set_rules('email', __('Эмаил'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Эмаил')]),
			'is_unique' => __('Поле [input] должно содержать уникальное значение.',[__('Эмаил')]),
			'valid_email' => __('Поле [input] должно быть вида емаил.',[__('Эмаил')]),
		]);
		$this->form_validation->set_rules('password', __('Пароль'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Пароль')]),
			'min_length' => __('Поле [input] должно содержать не менее [length] символов.',[__('Пароль'),$this->config->item('min_password_length', 'ion_auth')]),
			'matches' => __('Поле [input] не соответствует полю [input1].',[__('Пароль'),__('Повторить пароль')]),
		]);
		$this->form_validation->set_rules('password_confirm', __('Повторить пароль'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Повторить пароль')])
		]);

		if ($this->form_validation->run() === TRUE)
		{

				$email = strtolower($this->input->post('email'));
				$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
				$password = $this->input->post('password');

				/*$additional_data = [
					'telegram' => $this->input->post('telegram'),
					'jabber' => $this->input->post('jabber'),
					'promo' => $this->input->post('promo'),
				];*/

				if ($this->ion_auth->register($identity, $password, $email))
				{
					$this->session->set_flashdata('message', message(__('Вам на почту отправлено письмо активации аккаунта.')));
					redirect(site_url(LOGIN_PAGE), 'location');
				}

		}
		else{
			$this->data['error'] = ($this->session->flashdata('error')) ? $this->session->flashdata('error') : '';
			$this->data['message'] = ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
			$this->data['content'] = $this->parser->parse('modAuth/site/auth/register', $this->data,true);
			$this->parser->parse('site/main', $this->data);
		}

	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}
}
