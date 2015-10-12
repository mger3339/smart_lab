<?php

// Super admin
$route['super-admin']
		= 'super_admin/index';

// Super admin user account
$route['super-admin/my-account']
		= 'super_admin/user_account/index';

// Super admin user account - update account
$route['super-admin/my-account/update'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/user_account/update_account";
	}
	
	return "super_admin/user_account/index";
};

// Super admin user account - update password
$route['super-admin/my-account/change-password'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/user_account/update_password";
	}
	
	return "super_admin/user_account/index";
};

// Super admin clients
$route['super-admin/clients']
		= 'super_admin/clients';

// Super admin clients add
$route['super-admin/clients/add'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/clients/put_client";
	}
	
	return "super_admin/clients/add_client";
};

// Super admin clients edit
$route['super-admin/clients/edit/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/clients/update_client/{$id}";
	}
	
	return "super_admin/clients/edit_client/{$id}";
};

// Super admin clients delete
$route['super-admin/clients/delete/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/clients/delete_client/{$id}";
	}
	
	return "super_admin/clients";
};

// Super admin client settings
$route['super-admin/client-settings/(:num)']
		= "super_admin/client_settings/index/$1";

// Super admin client settings edit
$route['super-admin/client-settings/edit/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/client_settings/update/{$id}";
	}
	
	return "super_admin/client_settings/index/{$id}";
};

// Super admin client applications
$route['super-admin/client-applications/(:num)']
		= "super_admin/client_applications/index/$1";

// Super admin client applications add
$route['super-admin/client-applications/(:num)/add'] = function($client_id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/client_applications/put_client_application/{$client_id}";
	}
	
	return "super_admin/client_applications/add_client_application/{$client_id}";
};

// Super admin client applications edit
$route['super-admin/client-applications/(:num)/edit/(:num)'] = function($client_id, $client_application_id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/client_applications/update_client_application/{$client_id}/{$client_application_id}";
	}
	
	return "super_admin/client_applications/edit_client_application/{$client_id}/{$client_application_id}";
};

// Super admin client applications delete
$route['super-admin/client-applications/(:num)/delete/(:num)'] = function($client_id, $client_application_id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/client_applications/delete_client_application/{$client_id}/{$client_application_id}";
	}
	
	return "super_admin/client_applications/{$client_id}";
};

// Super admin client applications sort
$route['super-admin/client-applications/(:num)/sort/(:any)']
		= "super_admin/client_applications/sort_client_applications/$1/$2";

// Super admin client application modules
$route['super-admin/client-application-modules/(:num)/(:num)']
		= "super_admin/client_application_modules/index/$1/$2";

// Super admin client application modules sort
$route['super-admin/client-application-modules/(:num)/(:num)/sort/(:any)']
		= "super_admin/client_application_modules/sort_client_application_modules/$1/$2/$3";

// Super admin client application modules edit
$route['super-admin/client-application-modules/(:num)/(:num)/edit/(:num)'] = function($client_id, $client_application_id, $client_application_module_id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/client_application_modules/update_client_application_module/{$client_id}/{$client_application_id}/{$client_application_module_id}";
	}
	
	return "super_admin/client_application_modules/edit_client_application_module/{$client_id}/{$client_application_id}/{$client_application_module_id}";
};

// Super admin client user roles
$route['super-admin/client-user-roles/(:num)']
		= "super_admin/client_user_roles/index/$1";

// Super admin client user roles edit
$route['super-admin/client-user-roles/edit/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/client_user_roles/update/{$id}";
	}
	
	return "super_admin/client_user_roles/index/{$id}";
};

// Super admin users
$route['super-admin/users']
		= 'super_admin/users';

// Super admin users add
$route['super-admin/users/add'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/users/put_user";
	}
	
	return "super_admin/users/add_user";
};

// Super admin users edit
$route['super-admin/users/edit/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/users/update_user/{$id}";
	}
	
	return "super_admin/users/edit_user/{$id}";
};

// Super admin users delete
$route['super-admin/users/delete/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "super_admin/users/delete_user/{$id}";
	}
	
	return "super_admin/users";
};
