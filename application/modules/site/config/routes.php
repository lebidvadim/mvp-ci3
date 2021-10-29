<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['(:any)'] = 'site/index/$1';
$route['(:any)/(:any)'] = 'site/index/$1/$2';
$route['(:any)/(:any)/(:any)'] = 'site/index/$1/$2/$3';
