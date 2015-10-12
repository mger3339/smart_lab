<?php

// User account
$route['my-account']
		= 'user_account/index';

// User account - update account
$route['my-account/update'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "user_account/update_account";
	}
	
	return "user_account/index";
};

// User account - update password
$route['my-account/change-password'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "user_account/update_password";
	}
	
	return "user_account/index";
};
