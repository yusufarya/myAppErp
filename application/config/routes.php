<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*********** USER DEFINED ROUTES *******************/

// *** Login Controller ***
$route['loginMe'] = "Login/loginMe";

// *** User Controller ***
$route['dashboard'] = "Dashboard";
$route['profile'] = "User/profile";
$route['userDownload'] = "User/userDownload";
$route['logout'] = "Login/logout";

// *** Mpersediaan Controller ***
$route['unitListing'] = "Mpersediaan/unitListing";
$route['unitListing/(:any)'] = "Mpersediaan/unitListing/$1";
$route['addunitForm'] = "Mpersediaan/addUnitForm";
$route['editUnitForm/(:any)'] = "Mpersediaan/editUnitForm/$1";
$route['deleteUnit'] = "Mpersediaan/deleteUnit";
$route['deleteUnit/(:any)'] = "Mpersediaan/deleteUnit/$1";

$route['goodsGroupListing'] = "Mpersediaan/goodsGroupListing";
$route['goodsGroupListing/(:any)'] = "Mpersediaan/goodsGroupListing/$1";
$route['addGoodsGroupForm'] = "Mpersediaan/addGoodsGroupForm";
$route['addGoodsGroup'] = "Mpersediaan/addGoodsGroup";
$route['editGoodsGroupForm'] = "Mpersediaan/editGoodsGroupForm";
$route['editGoodsGroupForm/(:any)'] = "Mpersediaan/editGoodsGroupForm/$1";
$route['editGoodsGroup'] = "Mpersediaan/editGoodsGroup";

$route['brandListing'] = "Mpersediaan/brandListing";
$route['brandListing/(:any)'] = "Mpersediaan/brandListing/$1";
$route['addbrandForm'] = "Mpersediaan/addbrandForm";
$route['addbrand'] = "Mpersediaan/addbrand";
$route['addbrandForm'] = "Mpersediaan/addbrandForm";
$route['addbrand'] = "Mpersediaan/addbrand";
$route['editbrandForm/(:any)'] = "Mpersediaan/editbrandForm/$1";
$route['editbrand'] = "Mpersediaan/editbrand";

$route['goodsListing'] = "Mpersediaan/goodsListing";
$route['goodsListing/(:any)'] = "Mpersediaan/goodsListing/$1";
$route['addGoodsForm'] = "Mpersediaan/addGoodsForm";
$route['addGoods'] = "Mpersediaan/addGoods";