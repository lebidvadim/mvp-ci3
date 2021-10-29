<?php defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! function_exists('site_url'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function site_url($uri = '', $protocol = NULL)
	{
		$CI =& get_instance();
		$lang_conf = $CI->config->item('ROUTE_LOCALIZE');
		$lang_def = $lang_conf['list'][$lang_conf['default_key']];
		if(substr($uri, 0, 1) === '/')
			$uri = substr($uri, 1);
		if (in_array($CI->router->user_lang, $lang_conf['list']) and $lang_def != $CI->router->user_lang){
			$url = get_instance()->config->site_url($CI->router->user_lang.'/'.$uri, $protocol);
		}
		else{
			$url = get_instance()->config->site_url($uri, $protocol);
		}
		return $url;
	}
}
if ( ! function_exists('url_get_param'))
{
	function url_get_param($arr = [],$page = false)
	{
		if(empty($arr)) {
			$request = $_REQUEST;
			$request_str = '?';
			foreach ($request as $k => $v) {
				if ($k != '/' . uri_string())
					$request_str .= $k . '=' . $v . '&';
			}
			$request_str = substr($request_str, 0, -1);
		}
		else{
			$get = [];
			$get_new = [];
			if(!empty($arr['get_param'])) {
				$str = substr($arr['get_param'], 1);
				$get = explode("&",$str);
				foreach ($get as $k => $v)
				{
					$new = explode("=",$v);
					if ($new[0] != 'per_page')
						$get_new[$new[0]] = $new[1];
				}
			}
			if($page == true) {
				foreach ($arr as $k => $v) {
					if($k == 'per_page')
						unset($arr[$k]);
				}
			}
			$arr = array_merge($get_new,$arr);
			unset($arr['get_param']);

			$request = $arr;

			if(!empty($_GET['per_page'])) {
				if($page == false) {
					$request['per_page'] = $_GET['per_page'];
				}
				unset($request['get_param']);
			}
			unset($request['csrf_test_name']);

			$request_str = '?';

			foreach ($request as $k => $v) {
				if ($k != '/' . uri_string() and $v != '')
					$request_str .= $k . '=' . $v . '&';
			}

			$request_str = substr($request_str, 0, -1);
		}
		return $request_str;
	}
}
