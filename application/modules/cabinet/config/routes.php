<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['cabinet/(:any)/(:any)/(:num)'] = 'cabinet/index/$1/$2/$3';
$route['cabinet/(:any)/(:any)'] = 'cabinet/index/$1/$2';
$route['cabinet/(:any)'] = 'cabinet/index/$1';
$route['cabinet'] = 'cabinet/index';
