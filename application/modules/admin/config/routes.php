<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/(:any)'] = 'admin/index/$1';
$route['admin/(:any)/(:any)'] = 'admin/index/$1/$2';
$route['admin/(:any)/(:any)/(:any)'] = 'admin/index/$1/$2/$3';
$route['admin/(:any)/(:any)/(:any)/(:any)'] = 'admin/index/$1/$2/$3/$4';
$route['admin'] = 'admin/index';

