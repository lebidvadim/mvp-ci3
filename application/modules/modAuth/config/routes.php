<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['recover'] = 'modAuth/forgot_password';
$route['recovery-ver/(:any)'] = 'modAuth/reset_password/$1';
$route['logout'] = 'modAuth/logout';
$route['register'] = 'modAuth/register';
$route['activate/(:num)/(:any)'] = 'modAuth/activate/$1/$2';
$route[LOGIN_PAGE] = 'modAuth/login';
