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
$route['default_controller'] = 'User_authentication';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['API'] = 'Rest_server';

// User API Routes
$route['api/authentication/login'] = 'api/Authentication_api/login';
$route['api/authentication/registration'] = 'api/Authentication_api/registration';
$route['api/authentication/user/(:any)'] = 'api/Authentication_api/user/$1/$2';
$route['api/authentication/user/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/Authentication_api/user/id/$1/format/$3$4';
$route['api/authentication/update'] = 'api/Authentication_api/update';
$route['api/authentication/update/(:any)'] = 'api/Authentication_api/update/$1/$2';
// User Change Password Route
$route['api/authentication/changepassword'] = 'api/Authentication_api/changePassword';
// User Forgot Password Route
$route['api/authentication/sent'] = 'api/Authentication_api/sent';
$route['api/authentication/otpverify'] = 'api/Authentication_api/otpVerify';


//Subscription API Routes
$route['Subscription_api/addon_services'] = 'api/Subscription_api/addon_services';
$route['Subscription_api/index'] = 'api/Subscription_api/index';
$route['Subscription_api/insert'] = 'api/Subscription_api/insert';
$route['Subscription_api/update'] = 'api/Subscription_api/update/';
$route['Subscription_api/fetch_single'] = 'api/Subscription_api/fetch_single';
$route['Subscription_api/delete'] = 'api/Subscription_api/delete';

//Employees API Routes
$route['Employees_api/index'] = 'api/Employees_api/index';
$route['Employees_api/insert'] = 'api/Employees_api/insert';
$route['Employees_api/update'] = 'api/Employees_api/update';
$route['Employees_api/fetch_single'] = 'api/Employees_api/fetch_single';
$route['Employees_api/delete'] = 'api/Employees_api/delete';

//Orders API Routes
$route['Orders_api/index'] = 'api/Orders_api/index';
$route['Orders_api/insert'] = 'api/Orders_api/insert';
$route['Orders_api/update'] = 'api/Orders_api/update';
$route['Orders_api/fetch_single'] = 'api/Orders_api/fetch_single';
$route['Orders_api/delete'] = 'api/Orders_api/delete';

//Add To Cart API Routes
$route['Orders_api/add_to_cart'] = 'api/Orders_api/add_to_cart';
$route['Orders_api/cart_update'] = 'api/Orders_api/cart_update';

//Orders API Routes
$route['Gallery_api/index'] = 'api/Gallery_api/index';
$route['Gallery_api/insert'] = 'api/Gallery_api/insert';
$route['Gallery_api/update'] = 'api/Gallery_api/update';
$route['Gallery_api/fetch_single'] = 'api/Gallery_api/fetch_single';
$route['Gallery_api/delete'] = 'api/Gallery_api/delete';

//Notifications API Routes
$route['Notification_api/index'] = 'api/Notification_api/index';

