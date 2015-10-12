<?php

// Admin
$route['admin']
		= 'admin/index';


// Admin - users
$route['admin/users']
		= 'admin/users';

// Admin - users add
$route['admin/users/add'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "admin/users/put_user";
	}
	
	return "admin/users/add_user";
};

// Admin - users edit
$route['admin/users/edit/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "admin/users/update_user/{$id}";
	}
	
	return "admin/users/edit_user/{$id}";
};

// Admin - users delete
$route['admin/users/delete/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "admin/users/delete_user/{$id}";
	}
	
	return "admin/users";
};


// Admin - settings
$route['admin/settings']
		= 'admin/settings/settings/index';

// Admin - settings - user auto register update
$route['admin/settings/auto-register/update']
		= "admin/settings/user_auto_register/update_user_auto_register";

// Admin - settings - email hostnames add
$route['admin/settings/email-hostnames/add']
		= "admin/settings/email_hostnames/put_email_hostname";

// Admin - settings - email hostnames edit
$route['admin/settings/email-hostnames/update/(:num)']
		= "admin/settings/email_hostnames/update_email_hostname/$1";

// Admin - settings - email hostnames delete
$route['admin/settings/email-hostnames/delete/(:num)']
		= "admin/settings/email_hostnames/delete_email_hostname/$1";


// Admin - account
$route['admin/account']
		= 'admin/account';

// Admin - applications
$route['admin/applications']
    = 'admin/applications';

// Admin - applications
$route['admin/applications/(:num)']
    = "admin/applications/index/$1";

//  Admin applications add
$route['admin/applications/add'] = function() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/applications/put_application/";
    }

    return "admin/applications/add_application/";
};

// Admin - applications edit
$route['admin/applications/edit/(:num)'] = function($application_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/applications/update_application/{$application_id}";
    }

    return "admin/applications/edit_application/{$application_id}";
};


// Admin - applications delete
$route['admin/applications/delete/(:num)'] = function($application_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/applications/delete_application/{$application_id}";
    }

    return "admin/applications";
};

// Admin - get applications users with ajax
$route['admin/applications/searchUsers/'] = function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        die('asd');
        return "admin/applications/searchUsers/";
    }
};

// Admin applications sort
$route['admin/applications/sort/(:any)']
    = "admin/applications/sort_applications/$1/$2";


// Admin - snapshot
$route['admin/snapshots']
    = 'admin/snapshots/snapshots';

// Admin - client snapshot
$route['admin/snapshots/(:num)']
    = "admin/snapshots/snapshots/index/$1";

// Admin snapshots sort
$route['admin/snapshots/sort/(:any)']
    = "admin/snapshots/snapshots/sort_snapshots/$1/$2";


//  Admin client snapshot add
$route['admin/snapshots/add'] = function() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/snapshots/put_client_snapshot/";
    }

    return "admin/snapshots/snapshots/add_client_snapshot/";
};

// Admin - client snapshot edit
$route['admin/snapshots/edit/(:num)'] = function($client_snapshot_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/snapshots/update_client_snapshot/{$client_snapshot_id}";
    }

    return "admin/snapshots/snapshots/edit_client_snapshot/{$client_snapshot_id}";
};



// Admin - client snapshot delete
$route['admin/snapshots/delete/(:num)'] = function($client_snapshot_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/snapshots/delete_client_snapshot/{$client_snapshot_id}";
    }

    return "admin/snapshots";
};




// Admin client snapshot applications
$route['admin/snapshots/applications/(:num)']
    = "admin/snapshots/applications/index/$1";


// Admin snapshot applications add
$route['admin/snapshots/applications/(:num)/add'] = function($snapshot_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/applications/put_application/{$snapshot_id}";
    }

    return "admin/snapshots/applications/add_application/{$snapshot_id}";
};

// Super admin client applications edit
$route['admin/snapshots/applications/(:num)/edit/(:num)'] = function($snapshot_id,$client_application_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/applications/update_application/{$snapshot_id}/{$client_application_id}";
    }
    return "admin/snapshots/applications/edit_application/{$snapshot_id}/{$client_application_id}";
};


// Admin - snapshot applications delete

$route['admin/snapshots/applications/delete/(:num)/(:num)'] = function($client_application_id,$snapshot_id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/applications/delete_application/{$client_application_id}/{$snapshot_id}";
    }

    return "admin/snapshots/applications/$2";
};

// Admin applications sort
$route['admin/snapshots/applications/sort/(:any)']
    = "admin/snapshots/applications/sort_applications/$1/$2";


// Admin client snapshot sessions
$route['admin/snapshots/sessions/(:num)']
    = "admin/snapshots/sessions/index/$1";


// Admin snapshot sessions add
$route['admin/snapshots/sessions/(:num)/add'] = function($snapshot_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/sessions/put_session/{$snapshot_id}";
    }

    return "admin/snapshots/sessions/add_sessions/{$snapshot_id}";
};

// Super admin client sessions edit
$route['admin/snapshots/sessions/(:num)/edit/(:num)'] = function($snapshot_id,$snapshot_session_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/sessions/update_session/{$snapshot_id}/{$snapshot_session_id}";
    }

    return "admin/snapshots/sessions/edit_session/{$snapshot_id}/{$snapshot_session_id}";
};


// Admin - snapshot sessions delete

$route['admin/snapshots/sessions/delete/(:num)/(:num)'] = function($snapshot_session_id,$snapshot_id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/snapshots/sessions/delete_session/{$snapshot_session_id}/{$snapshot_id}";
    }

    return "admin/snapshots/sessions/$2";
};

// Admin sessions sort
$route['admin/snapshots/sessions/sort/(:any)']
    = "admin/snapshots/sessions/sort_sessions/$1/$2";



// Admin - groups
$route['admin/groups']
    = 'admin/groups';

// Admin - groups
$route['admin/groups/(:num)']
    = "admin/groups/index/$1";

//  Admin groups add
$route['admin/groups/add'] = function() {


    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/groups/put_group/";
    }

    return "admin/groups/add_group/";
};

$route['admin/groups/add_new_group'] = function() {
    return "admin/groups/add_new_group";
};

// Admin - groups edit
$route['admin/groups/edit/(:num)'] = function($group_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/groups/update_group/{$group_id}";
    }

    return "admin/groups/edit_group/{$group_id}";
};


// Admin - groups delete
$route['admin/groups/delete/(:num)'] = function($group_id) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/groups/delete_group/{$group_id}";
    }

    return "admin/groups";
};

// Admin groups sort
$route['admin/groups/sort/(:any)']
    = "admin/groups/sort_groups/$1/$2";


$route['admin/users/search'] = function()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        return "admin/users/search_users/";
    }

};

// Admin - notifications add
$route['admin/notifications/add'] = function() {
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/notifications/put_notification";
    }
    
    return "admin/notifications/add_notification";
};

//// Admin - users import
//$route['admin/users/import'] = function() {
//    return "admin/users/import";
//};
//
//$route['admin/users/import_users'] = function() {
//    return "admin/users/import_users";
//};

//  Admin groups add
$route['admin/users/import'] = function() {


    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/users/import_users";
    }

    return "admin/users/import";
};


// Admin - edit user/users role
$route['admin/users/edit_user_role'] = function() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/users/update_user_role";
    }

    return "admin/users/edit_user_role";
};

// Admin - edit user/users role
$route['admin/users/edit_groups'] = function() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return "admin/users/update_user_groups";
    }

    return "admin/users/edit_user_groups";
};