<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_pages
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
	}
	public function load($page,$page_dop1,$page_dop2){
		if($page == '') $page = 'all';
		$this->CI->data['active'] = ($page_dop1 != '' and !ctype_digit($page_dop1)) ? $page.'-'.$page_dop1 : $page;
		switch ($page)
		{
			case '' or $page == 'all':
				$this->CI->data['title'] = __('Страницы');
				$this->CI->data['breadcrumb']['title'] = __('Страницы');
				$this->CI->data['breadcrumb']['desc'] = __('Все страницы');
				$content = $this->all();
				break;
			case 'add':
				$this->CI->data['title'] = __('Добавить страницу');
				$this->CI->data['breadcrumb']['title'] = __('Добавить страницу');
				$this->CI->data['breadcrumb']['desc'] = __('Страница добавить страницу');
				$content = $this->add();
				break;
			case $page == 'edit' and $page_dop1 != 0 and $this->CI->M_pages->get_num(['where' => ['id' => $page_dop1]]):
				$this->CI->data['title'] = __('Редактировать страницу');
				$this->CI->data['breadcrumb']['title'] = __('Редактировать страницу');
				$this->CI->data['breadcrumb']['desc'] = __('Страница редактировать страницу');
				$content = $this->edit();
				break;
			case $page == 'del' and $page_dop1 != 0 and $this->CI->M_pages->get_num(['where' => ['id' => $page_dop1]]):
				$this->CI->data['title'] = __('Удалить страницу');
				$this->CI->data['breadcrumb']['title'] = __('Удалить страницу');
				$this->CI->data['breadcrumb']['desc'] = __('Страница удалить страницу');
				$content = $this->del($page_dop1);
				break;
			case $page = 'view' and $page_dop1 != 0 and $this->CI->M_pages->get_num(['where' => ['id' => $page_dop1]]):
				$this->view($page_dop1);
				break;
			default: $content = message(__('Такой страницы нету.'),3);
		}
		$this->CI->data['content'] = $content;
		return $this->CI->parser->parse('modPages/admin/pages/pages', $this->CI->data, true);
	}
	public function all()
	{
		$count = $this->CI->M_pages->get_num();
		$pages = pagination(site_url('admin/pages'),10,$count);
		$this->CI->data['pagination'] = $pages[0];
		$this->CI->data['pages'] = $this->CI->M_pages->get(['limit' => $pages[1]]);
		return $this->CI->parser->parse('modPages/admin/pages/pages-all', $this->CI->data, true);
	}
	public function search(){
		if(!empty($_POST)) {
			//url_get_param($_POST);
			redirect(site_url('admin/pages').url_get_param($_POST), 'location');
		}
	}
	public function add()
	{
		$this->addForm();
		return $this->CI->parser->parse('modPages/admin/pages/pages-add', $this->CI->data, true);
	}
	private function addForm()
	{
		$this->CI->form_validation->set_rules('title', __('Заголовок'), 'required|callback_ValidPagesName', [
			'required' => __('Поле [input] обязательно для заполнения.',[__('Заголовок')]),
			'ValidPagesName' => __('Страница с таким именем <b>[input]</b> существует', [$this->CI->input->post('title')])
		]);
		$this->CI->form_validation->set_rules('description', __('Описание'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Описание')])
		]);
		$this->CI->form_validation->set_rules('text', __('Текст страницы'), 'required',[
			'required' => __('Поле [input] обязательно для заполнения.',[__('Текст страницы')])
		]);

		if ($this->CI->form_validation->run($this->CI) === TRUE)
		{
			$arr['name'] = strtolower(transliterate($this->CI->input->post('title')));
			$arr['title'] = $this->CI->input->post('title');
			$arr['description'] = $this->CI->input->post('description');
			$arr['text'] = $this->CI->input->post('text');
			$arr['date_creat'] = time();
			$arr['id_us'] = $this->CI->user->user_id;
			$this->CI->M_pages->ins($arr);
			$this->CI->session->set_flashdata('message', message(__('Вы успешно добавили страницу.')));
			redirect(site_url('admin/pages'), 'location');
		}

	}
	public function edit()
	{
		return $this->CI->parser->parse('modPages/admin/pages/pages-edit', $this->CI->data, true);
	}
	public function del($id)
	{
		$page = $this->CI->M_pages->get(['where' => ['id' => $id],'result' => 'row']);
		$this->CI->data['page'] = $page;
		if($this->CI->input->post())
		{
			if($page->home == 0) {
				$this->CI->M_pages->del($id);
				$this->CI->session->set_flashdata('message', message(__('Страница <b>[name]</b> успешно удалена.', [$page->title])));
				redirect(site_url('admin/pages'), 'location');
			}
			else{
				$this->CI->session->set_flashdata('message', message(__('Вы не можете удалить <b>[name]</b> эту страницу. Потому что ей назначено что она есть главной страницей сайта.', [$page->title]),3));
				redirect(site_url('admin/pages/del/'.$id), 'location');
			}
		}
		return $this->CI->parser->parse('modPages/admin/pages/pages-del', $this->CI->data, true);
	}
	public function view($id)
	{
		$page = $this->CI->M_pages->get(['where' => ['id' => $id],'result' => 'row']);
		if($page->home != 1)
		{
			if ($page->status == 1)
			{
				$this->CI->M_pages->up($page->id, ['status' => 0]);
				$this->CI->session->set_flashdata('message', message(__('Страница <b>[name]</b> скрыта от показа.', [$page->title])));
			}
			else {
				$this->CI->M_pages->up($page->id, ['status' => 1]);
				$this->CI->session->set_flashdata('message', message(__('Страница <b>[name]</b> открыта для показа.', [$page->title])));
			}
		}
		else{
			$this->CI->session->set_flashdata('message', message(__('Вы не можете скрыть <b>[name]</b> эту страницу. Потому что она есть главной страницей сайта.', [$page->title]),3));
		}
		if($this->CI->input->post('get_param'))
			redirect(site_url('admin/pages').$this->CI->input->post('get_param'), 'location');
		else
			redirect(site_url('admin/pages'), 'location');
	}
}
