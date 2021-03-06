<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI Smarty
 *
 * Smarty templating for Codeigniter
 *
 * @package   CI Smarty
 * @author    Dwayne Charrington
 * @copyright 2015 Dwayne Charrington and Github contributors
 * @link      http://ilikekillnerds.com
 * @license   MIT
 * @version   3.0
 */

class MY_Parser extends CI_Parser {

    protected $CI;

    protected $_module = '';
    protected $_template_locations = array();

    // Current theme location
    protected $_current_path = NULL;

    // The name of the theme in use
    protected $_theme_name = '';

    public function __construct()
    {
        // Codeigniter instance and other required libraries/files
        $this->CI =& get_instance();
        $this->CI->load->library('ci_smarty');
        $this->CI->load->helper('parser');
        $this->CI->load->config('smarty');

        // Detect if we have a current module
        $this->_module = $this->current_module();

        // What controllers or methods are in use
        $this->_controller  = $this->CI->router->fetch_class();
        $this->_method     = $this->CI->router->fetch_method();

        // If we don't have a theme name stored
        if ($this->_theme_name == '')
        {
            $this->set_theme($this->CI->config->item('smarty.theme_name'));
        }

        // Update theme paths
        $this->_update_theme_paths();
    }

    /**
    * Call
    * able to call native Smarty methods
    * @returns mixed
    */
    public function __call($method, $params=array())
    {
        if ( ! method_exists($this, $method) )
        {
            return call_user_func_array(array($this->CI->ci_smarty, $method), $params);
        }
    }

    /**
     * Set Theme
     *
     * Set the theme to use
     *
     * @access public
     * @param $name
     * @return string
     */
    public function set_theme($name)
    {
        // Store the theme name
        $this->_theme_name = trim($name);

        // Our themes can have a functions.php file just like Wordpress
        $functions_file  = $this->CI->config->item('smarty.theme_path') . $this->_theme_name . '/functions.php';

        // Incase we have a theme in the application directory
        $functions_file2 = APPPATH."themes/" . $this->_theme_name . '/functions.php';

        // If we have a functions file, include it
        if ( file_exists($functions_file) )
        {
            include_once($functions_file);
        }
        elseif ( file_exists($functions_file2) )
        {
            include_once($functions_file2);
        }

        // Update theme paths
        $this->_update_theme_paths();
    }

    /**
     * Get Theme
     *
     * Does what the function name implies: gets the name of
     * the currently in use theme.
     *
     * @return string
     */
    public function get_theme()
    {
        return (isset($this->_theme_name)) ? $this->_theme_name : '';
    }

    /**
     * Current Module
     *
     * Just a fancier way of getting the current module
     * if we have support for modules
     *
     * @access public
     * @return string
     */
    public function current_module()
    {
        // Modular Separation / Modular Extensions has been detected
        if ( method_exists( $this->CI->router, 'fetch_module' ) )
        {
            $module = $this->CI->router->fetch_module();
            return (!empty($module)) ? $module : '';
        }
        else
        {
            return '';
        }
    }

    /**
     * Parse
     *
     * Parses a template using Smarty 3 engine
     *
     * @access public
     * @param $template
     * @param $data
     * @param $return
     * @param $caching
     * @param $theme
     * @return string
     */
    public function parse($template, $data = array(), $return = FALSE, $caching = TRUE, $theme = '')
    {
        // If we don't want caching, disable it
        if ( $caching === FALSE )
        {
            $this->CI->ci_smarty->disable_caching();
        }

        // If no file extension dot has been found default to defined extension for view extensions
        if ( ! stripos($template, '.'))
        {
            $template = $template.".tpl";
        }

        // Are we overriding the theme on a per load view basis?
        if ( $theme !== '' )
        {
            $this->set_theme($theme);
        }
        // Get the location of our view, where the hell is it?
        // But only if we're not accessing a smart resource
        if ( ! stripos($template, '/') )
        {
            $template = $this->_find_view($template);
        }
        else{
            $mod = explode('/',$template);
            if(count($mod) == 2) {
                if(file_exists(FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $mod[0] . '/' . $mod[1])) {
                    $template = FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $mod[0] . '/' . $mod[1];
                }
                else{
                    if(file_exists(APPPATH . 'modules/' . $mod[0] . '/views/' . $mod[1])){
                        $template = APPPATH . 'modules/' . $mod[0] . '/views/' . $mod[1];
                    }
                    else{
                        if(file_exists(FCPATH . 'modules/' . $mod[0] . '/views/' . $mod[1])) {
                            $template = FCPATH . 'modules/' . $mod[0] . '/views/' . $mod[1];
                        }
                        else{
                            $ul = '<ul>';
                            $ul .= '<li><b>'.'/application/modules/' . $mod[0] . '/views/' . $mod[1].'</b></li>';
                            $ul .= '<li><b>'.'/modules/' . $mod[0] . '/views/' . $mod[1].'</b></li>';
                            $ul .= '<li><b>/'.$this->CI->config->item('smarty.theme_path') . $this->_theme_name . $mod[0] . '/' . $mod[1].'</b></li>';
                            $ul .= '</ul>';
                            show_error('???????????????? ???????? ?????????????? ?? ?????????? ???? ?????????????????? ????????.'.$ul, 303, '???????? ?????????????? ???? ????????????????????');
                        }
                    }
                }
            }
            else {
                $ad = '';
                for($i = 1; $i < count($mod); $i++){
                    $ad .= $mod[$i].'/';
                }

                $ad = substr($ad, 0, -1);
                if(file_exists(FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $ad)) {
                    $template = FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $ad;
                }
                else{
                    if(file_exists(APPPATH . 'modules/' . $mod[0] . '/views/' . $ad)) {
                        $template = APPPATH . 'modules/' . $mod[0] . '/views/' . $ad;
                    }
                    else{
                        if(file_exists(FCPATH . 'modules/' . $mod[0] . '/views/' . $ad)) {
                            $template = FCPATH . 'modules/' . $mod[0] . '/views/' . $ad;
                        }
                        else{
                            $ul = '<ul>';
                            $ul .= '<li><b>'.'/application/modules/' . $mod[0] . '/views/' . $ad.'</b></li>';
                            $ul .= '<li><b>'.'/modules/' . $mod[0] . '/views/' . $ad.'</b></li>';
                            $ul .= '<li><b>/'.$this->CI->config->item('smarty.theme_path') . $this->_theme_name . $ad.'</b></li>';
                            $ul .= '</ul>';
                            show_error('???????????????? ???????? ?????????????? ?? ?????????? ???? ?????????????????? ????????.'.$ul, 303, '???????? ?????????????? ???? ????????????????????');
                        }
                    }
                }
            }
        }

        // If we have variables to assign, lets assign them
        if ( ! empty($data) )
        {
            foreach ($data AS $key => $val)
            {
                $this->CI->ci_smarty->assign($key, $val);
            }
        }
        // Load our template into our string for judgement

        $template_string = $this->CI->ci_smarty->fetch($template);

        // If we're returning the templates contents, we're displaying the template
        if ( $return === FALSE )
        {
            $this->CI->output->append_output($template_string);
            return TRUE;
        }

        // We're returning the contents, fo' shizzle
        return $template_string;
    }

    /**
     * CSS
     *
     * An asset function that returns a CSS stylesheet
     *
     * @access public
     * @param $file
     * @return string
     */
    public function css($file, $attributes = array())
    {
        $defaults = array(
            'media' => 'screen',
            'rel'   => 'stylesheet',
            'type'  => 'text/css'
        );

        $attributes = array_merge($defaults, $attributes);

        $return = '<link rel="'.$attributes['rel'].'" type="'.$attributes['type'].'" href="'.base_url($this->CI->config->item('smarty.theme_path').$this->get_theme().$file).'" media="'.$attributes['media'].'">';

        return $return;
    }

    /**
     * JS
     *
     * An asset function that returns a script embed tag
     *
     * @access public
     * @param $file
     * @return string
     */
    public function js($file, $attributes = array())
    {
        $defaults = array(
            'type'  => 'text/javascript'
        );

        $attributes = array_merge($defaults, $attributes);

        $return = '<script type="'.$attributes['type'].'" src="'.base_url($this->CI->config->item('smarty.theme_path').$this->get_theme().$file).'"></script>';

        return $return;
    }

    /**
     * IMG
     *
     * An asset function that returns an image tag
     *
     * @access public
     * @param $file
     * @return string
     */
    public function img($file, $attributes = array())
    {
        $defaults = array(
            'alt'    => '',
            'title'  => ''
        );

        $attributes = array_merge($defaults, $attributes);

        $return = '<img src ="'.base_url($this->CI->config->item('smarty.theme_path').$this->get_theme().$file).'" alt="'.$attributes['alt'].'" title="'.$attributes['title'].'" />';

        return $return;
    }

    /**
     * Theme URL
     *
     * A web friendly URL for determining the current
     * theme root location.
     *
     * @access public
     * @param $location
     * @return string
     */
    public function theme_url($location = '')
    {
        // The path to return
        $return = base_url($this->CI->config->item('smarty.theme_path').$this->get_theme());

        // If we want to add something to the end of the theme URL
        if ( $location !== '' )
        {
            $return = $return.$location;
        }

        return trim($return);
    }

    /**
    * Find View
    *
    * Searches through module and view folders looking for your view, sir.
    *
    * @access protected
    * @param $file
    * @return string The path and file found
    */
    protected function _find_view($file)
    {
        // We have no path by default
        $path = NULL;

        // Get template locations
        $locations = $this->_template_locations;


        // Get the current module
        $current_module = $this->current_module();

        if ( $current_module !== $this->_module )
        {

            $new_locations = array(
                FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $current_module .'/layouts/',
                FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $current_module .'/',
                FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name,
                APPPATH . 'modules/' . $current_module . '/views/layouts/',
                APPPATH . 'modules/' . $current_module . '/views/',
                FCPATH . 'modules/' . $current_module . '/views/layouts/',
                FCPATH . 'modules/' . $current_module . '/views/'
            );
            foreach ($new_locations AS $new_location)
            {
                array_unshift($locations, $new_location);
            }
        }

        // Iterate over our saved locations and find the file
        foreach($locations AS $location)
        {
            //$info = '';
            if ( file_exists($location.$file) )
            {
                // Store the file to load
                $path = $location.$file;
                $this->_current_path = $location;

                // Stop the loop, we found our file
                break;
            }
            /*else{
                $info = '???????????????? ???????? /' . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $current_module .'/'.$file;

            }*/
        }
        //echo $info;
        return $path;
    }

    /**
    * Add Paths
    *
    * Traverses all added template locations and adds them
    * to Smarty so we can extend and include view files
    * correctly from a slew of different locations including
    * modules if we support them.
    *
    * @access protected
    */
    protected function _add_paths()
    {
        // Iterate over our saved locations and find the file
        foreach($this->_template_locations AS $location)
        {
            $this->CI->ci_smarty->addTemplateDir($location);
        }
    }

    /**
     * Update Theme Paths
     *
     * Adds in the required locations for themes
     *
     * @access protected
     */
    protected function _update_theme_paths()
    {
        // Store a whole heap of template locations
        $this->_template_locations = array(
            FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $this->_module .'/layouts/',
            FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name . $this->_module .'/',
            FCPATH . $this->CI->config->item('smarty.theme_path') . $this->_theme_name,
            APPPATH . 'modules/' . $this->_module . '/views/layouts/',
            APPPATH . 'modules/' . $this->_module . '/views/',
            FCPATH . 'modules/' . $this->_module . '/views/layouts/',
            FCPATH . 'modules/' . $this->_module . '/views/',
            APPPATH . 'views/layouts/',
            APPPATH . 'views/'
        );
        // Will add paths into Smarty for "smarter" inheritance and inclusion
        $this->_add_paths();
    }

    /**
    * String Parse
    *
    * Parses a string using Smarty 3
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $is_include
    */
    public function string_parse($template, $data = array(), $return = FALSE, $is_include = FALSE)
    {
        return $this->CI->ci_smarty->fetch('string:'.$template, $data);
    }

    /**
    * Parse String
    *
    * Parses a string using Smarty 3. Never understood why there
    * was two identical functions in Codeigniter that did the same.
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $is_include
    */
    public function parse_string($template, $data = array(), $return = FALSE, $is_include = false)
    {
        return $this->string_parse($template, $data, $return, $is_include);
    }

}
