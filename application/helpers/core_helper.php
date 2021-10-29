<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function __($key, $replace = []) {
	$lang = Language::getInstance();
	return $lang->search($key, $replace);
}
function lang_widget()
{
	$CI =& get_instance();
	$lang = $CI->config->item('ROUTE_LOCALIZE');
	$lang_def = $lang['list'][$lang['default_key']];
	$lang = $lang['list'];

	$uri = uri_string();
	$uri = explode('/',$uri);

	if(in_array($CI->router->user_lang, $uri))
		unset($uri[0]);
	$lang_new = [];
	foreach($lang as $k => $v)
	{
		if($lang_def != $v)
			$lang_new[$v] = base_url().$v.'/'.implode('/',$uri);
		else
			$lang_new[$v] = base_url().implode('/',$uri);
	}
	$html = '';
	if(count($lang_new) > 1)
	{
		$html .= '<div class="btn-group"><button type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-globe-americas"></i></button><div class="dropdown-menu dropdown-menu-right">';
		foreach ($lang_new as $k => $v) {
			$active = '';
			if ($k == $CI->router->user_lang)
				$active = ' active';
			$html .= '<a class="dropdown-item' . $active . '" href="' . $v . '">' . __($k) . '</a>';
		}
		$html .= '</div></div>';
	}
	return $html;
}
function message($message,$type = 1)
{
    if($message != '')
    {
        if($type == 1)
            $mess = '<div class="alert alert-success">'.$message.'</div>';

        if($type == 2)
            $mess = '<div class="alert alert-warning">'.$message.'</div>';

		if($type == 3)
			$mess = '<div class="alert alert-danger">'.$message.'</div>';

        return $mess;
    }
    else
        return '';
}
function pri($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}
function generate_password($number)
{
	$arr_b = array('A','B','C','D','E','F',
		'G','H','I','J','K','L',
		'M','N','O','P','R','S',
		'T','U','V','X','Y','Z');
	$arr = array('a','b','c','d','e','f',
		'g','h','i','j','k','l',
		'm','n','o','p','r','s',
		't','u','v','x','y','z',
		'1','2','3','4','5','6',
		'7','8','9','0');
	// Генерируем пароль
	$pass = rand(0, count($arr_b) - 1);
	$pass = $arr_b[$pass];
	for($i = 0; $i < $number; $i++)
	{
		// Вычисляем случайный индекс массива
		$index = rand(0, count($arr) - 1);
		$pass .= $arr[$index];
	}
	return $pass;
}

function password_val($str)
{
	$ctype_upper = false;
	foreach (str_split($str) as $items)
	{
		if(ctype_upper($items)){
			$ctype_upper = true;
			break;
		}
	}
	if($ctype_upper and !ctype_lower($str) and !ctype_digit($str))
		return true;
	else
		return false;
}
function scandir_mod($type = 'mod',$lib = [])
{
	if($type == 'mod') {
		$files = scandir(APPPATH . '/modules');
		if(is_dir(FCPATH.'/modules')) {
			$files_pak = scandir(FCPATH.'/modules');
			foreach ($files_pak as $p => $pa) {
				$str = substr($pa, 0, 3);
				if (!in_array($pa, $files) and $str == 'mod') {
					array_push($files, $pa);
				}
			}
		}
	}
	if($type == 'lib')
	{
		if(count($lib) === 1)
			$files = scandir(APPPATH . '/modules/' . $lib[0] . '/libraries');
		else
			$files = scandir(APPPATH . '/modules/' . $lib[0] . '/libraries/' . $lib[1]);
	}
	$files_new = [];
	foreach ($files as $val){
		if($val != '.' and $val != '..'){
			$files_new[] = $val;
		}
	}
	//pri($files_new);
	return $files_new;
}
function scandir_mig()
{
	$files_new = [];
	if(is_dir(FCPATH.'/modules')) {
		$files = scandir(APPPATH . '/modules');
		foreach ($files as $k => $v)
		{
			if ($v != '.' and $v != '..') {
				$files[$k] = APPPATH . 'modules/' . $v . '/migrations/';
			}
		}
		if (is_dir(FCPATH . '/modules')) {
			$files_pak = scandir(FCPATH . '/modules');
			foreach ($files_pak as $p => $pa) {
				$str = substr($pa, 0, 3);
				if (!in_array($pa, $files) and $str == 'mod') {
					array_push($files, FCPATH . 'modules/' . $pa . '/migrations/');
				}
			}
		}

		foreach ($files as $val) {
			if ($val != '.' and $val != '..') {
				$files_new[] = $val;
			}
		}
	}
	return $files_new;
}
function check_mod($name, $type = 'mod')
{
	$mod = scandir_mod($type);
	$check = false;
	foreach($mod as $m)
		if ($m == $name)
			$check = true;
	return $check;
}
function check_lib($name)
{
	$mod = scandir_mod('mod');
	$check = false;
	foreach($mod as $m) {
		$rest = substr($name, 0, 3);
		if($rest == 'mod') {

			if ($m == 'mod' . ucfirst(substr($name, 3))) {
				$check = true;
			}
		}
		else{
			if ($m == 'mod' . ucfirst($name)) {
				$check = true;
			}
		}
	}
	return $check;
}
function check_lib_all()
{
	$mod = scandir_mod('mod');
	return $mod;
}
function check_lib_menu($menu,$name)
{
	$mod = scandir_mod('mod');
	$check = 0;
	$check_menu = false;
	foreach ($menu as $me){
		if($me['active'] == $name)
			$check_menu = true;
	}
	if($check_menu == true) {
		foreach ($mod as $m) {
			if ($m == 'mod'.ucfirst($name)) {
				$check = 1;
			}
		}
	}
	else{
		$check = 2;
	}
	return $check;
}
function phone_form($phone){
	return '('.substr($phone, 0, 3).') '.substr($phone, 3, 3).'-'.substr($phone, 6, 4);
}
function transliterate($string) {
	$converter = array(
		'а' => 'a',   'б' => 'b',   'в' => 'v',
		'г' => 'g',   'д' => 'd',   'е' => 'e',
		'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
		'и' => 'i',   'й' => 'y',   'к' => 'k',
		'л' => 'l',   'м' => 'm',   'н' => 'n',
		'о' => 'o',   'п' => 'p',   'р' => 'r',
		'с' => 's',   'т' => 't',   'у' => 'u',
		'ф' => 'f',   'х' => 'h',   'ц' => 'c',
		'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
		'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
		'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

		'А' => 'A',   'Б' => 'B',   'В' => 'V',
		'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
		'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
		'И' => 'I',   'Й' => 'Y',   'К' => 'K',
		'Л' => 'L',   'М' => 'M',   'Н' => 'N',
		'О' => 'O',   'П' => 'P',   'Р' => 'R',
		'С' => 'S',   'Т' => 'T',   'У' => 'U',
		'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
		'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
		'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
		'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		' ' => '-',
	);
	return strtr($string, $converter);
}

function pagination($url, $page, $count) {
	$CI =& get_instance();
	$CI->load->library('pagination');

	$config['base_url'] = $url;
	$config['page_query_string'] = true;
	$config['total_rows'] = $count;
	$config['per_page'] = $page;
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;

	$config['full_tag_open'] = '<ul class="pagination">';
	$config['full_tag_close'] = '</ul>';

	$config['cur_tag_open'] = '<li class="active"><span>';
	$config['cur_tag_close'] = '</span></li>';

	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';

	$config['next_link'] = '&raquo;';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';

	$config['prev_link'] = '&laquo';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';

	$config['first_link'] = 'Первая';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';

	$config['last_link'] = 'Последняя';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';

	$CI->pagination->initialize($config);
	//$CI->data['pagination'] = $CI->pagination->create_links();

	$pagination = $CI->pagination;

	if(!empty($_GET['per_page']) and $_GET['per_page'] == '') {
		$cur_page = 0;
	}
	elseif (!empty($_GET['per_page']) and $_GET['per_page'] != ''){
		$cur_page = $_GET['per_page']-1;
	}
	else {
		$cur_page = 0;
	}

	return [$CI->pagination->create_links(),[$pagination->per_page,$cur_page*$page]];
}

function arr_explode_calls($arr)
{
	$calls = explode('||', $arr);
	$calls_new = [];
	foreach ($calls as $k => $ca) {
		$mas = explode('|', $ca);
		$calls_new[$k]['id'] = $mas[0];
		$calls_new[$k]['price'] = $mas[1];
	}
	return $calls_new;
}
function search(){
	$result = [];
	$result['get'] = '';
	if($_POST) {
		foreach ($_POST as $k => $v) {
			if($k != 'get_param'){
				$result['like'][$k] = $v;
				$result['get'] .= '?' . $k . '=' . $v;
			}
		}
	}
	if($_GET) {
		foreach ($_GET as $k => $v) {
			if($k != 'per_page'){
				$result['like'][$k] = $v;
				$result['get'] .= '?' . $k . '=' . $v;
			}
		}
	}
	return $result;
}
