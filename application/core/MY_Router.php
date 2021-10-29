<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {
	/**
	 * Language user or default language
	 * Язык пользователя или язык по-умолчанию
	 */
	public $user_lang = '';

	public $lang = '';


	/**
	 * Class constructor
	 *
	 * Run the route mapping function.
	 *
	 * @param   array   $routing
	 * @return  void
	 */
	public function __construct($routing = NULL)
	{
		parent::__construct();
	}

	private function __localize_init( &$route = array() ) {

		// Loader config localize
		if (file_exists(APPPATH.'config/localize_config.php'))
		{
			include(APPPATH.'config/localize_config.php');
			$localize = $config['ROUTE_LOCALIZE'];
		} else {
			return FALSE;
		}

		/* --------------------------------------------------------- */

		// Check config localize
		if ( !isset($localize) or !isset($localize['list']) ) {
			return FALSE;
		}

		if ( !isset($localize['default_key']) ) {
			$localize['default_key'] = 0;
		}

		$localize['default_key'] = intval($localize['default_key']);

		/* --------------------------------------------------------- */

		// Language join list
		$lang_list = implode('|', $localize['list']);

		// Create new route list
		foreach ( $route as $key => $item ) {
			$_route[$key] = $item;
			if ( $key == 'default_controller' ) {
				$_route['('.$lang_list.')'] = $route['default_controller'];
				//$_route['('.$lang_list.')/(:any)'] = '$2';
			}
			else{

				if($key != '404_override' and $key != 'translate_uri_dashes' and is_string($item) == true) {
					$item_arr = explode('/', $item);

					foreach ($item_arr as $k => $v) {
						if ($v == '$1') {
							$item_arr[$k] = '$2';
						} elseif ($v == '$2') {
							$item_arr[$k] = '$3';
						} elseif ($v == '$3') {
							$item_arr[$k] = '$4';
						} elseif ($v == '$5') {
							$item_arr[$k] = '$6';
						}
					}

					$_route['(' . $lang_list . ')/' . $key] = implode('/', $item_arr);

				}

			}
		}

		/* --------------------------------------------------------- */

		// Check default language
		if ( isset( $localize['list'][ $localize['default_key'] ] ) ) {
			$this->user_lang = $localize['list'][ $localize['default_key'] ];
			$this->lang = $this->lang_filter($localize['list'][ $localize['default_key'] ]);
		}

		// User select language
		if ( array_search( $this->uri->segment(1), $localize['list'] ) !== FALSE ) {
			$this->user_lang = $this->uri->segment(1);
			$this->lang = $this->lang_filter($this->uri->segment(1));
		}

		$route = $_route;
		/*echo '<pre>';
		print_r($route);
		echo '<pre>';*/
	}

	protected function lang_filter($lang){
		$lang_arr = [
			'ua' => 'ukrainian',
			'ru' => 'russian',
			'en' => 'english',
		];
		return $lang_arr[$lang];
	}
	protected function _set_routing()
	{
		// Load the routes.php file. It would be great if we could
		// skip this for enable_query_strings = TRUE, but then
		// default_controller would be empty ...
		if (file_exists(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}

		if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}
		// Validate & get reserved routes
		if (isset($route) && is_array($route))
		{
			$this->__localize_init($route);
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = $route;
		}
		// Are query strings enabled in the config file? Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		if ($this->enable_query_strings)
		{
			// If the directory is set at this time, it means an override exists, so skip the checks
			if ( ! isset($this->directory))
			{
				$_d = $this->config->item('directory_trigger');
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d], " \t\n\r\0\x0B/") : '';

				if ($_d !== '')
				{
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}

			$_c = trim($this->config->item('controller_trigger'));
			if ( ! empty($_GET[$_c]))
			{
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);

				$_f = trim($this->config->item('function_trigger'));
				if ( ! empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}

				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				$this->_set_default_controller();
			}

			// Routing rules don't apply to query strings and we don't need to detect
			// directories, so we're done here
			return;
		}
		// Is there anything to parse?
		if ($this->uri->uri_string !== '')
		{
			$this->_parse_routes();
		}
		else
		{
			$this->_set_default_controller();
		}
	}
}

