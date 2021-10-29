<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax
{
	public $CI;
	public function __construct($par = [])
	{
		$this->CI = $par;
	}
	public function load($page,$page_dop1,$page_dop2){
		if($page == '') $page = 'all';
		switch ($page)
		{
			case $page == 'del' and $page_dop1 != 0 and $this->CI->M_users->get_num(['where' => ['id' => $page_dop1]]):
				$this->del($page_dop1);
				break;
			default: $content = __('Такой страницы нету.');
		}
	}
	public function del($id){
		$this->CI->data['user'] = $this->CI->M_users->get(['where' => ['users.id' => $id],'result' => 'row']);

		if($this->CI->input->post()){
			if($this->CI->user->user_id != $id) {
				$this->CI->M_users->del($id);
				$this->CI->session->set_flashdata('message', message(__('Пользователь <b>[name]</b> удален.', [$this->CI->data['user']->username])));
				redirect(site_url('admin/users'), 'location');
			}
			else{
				$this->CI->session->set_flashdata('message', message(__('Вы не можете удалить себя.', [$this->CI->data['user']->username]),3));
				redirect(site_url('admin/users'), 'location');
			}
		}

		$this->CI->parser->parse('modUsers/admin/users/modal/del', $this->CI->data);
	}
}
