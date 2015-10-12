<?php

// Timeline
$route['timeline']
		= 'timeline/index';

// Timeline - Workstreams - get single
$route['timeline/workstream/(:num)']
		= "timeline/workstreams/get_workstream/$1";


// Timeline - Milestones - get single
$route['timeline/milestone/(:num)']
		= "timeline/milestones/get_milestone/$1";

// Timeline - Milestones - get all
$route['timeline/milestones']
		= "timeline/milestones/index";

// Timeline - Milestones - get by workstream
$route['timeline/workstream/(:num)/milestones']
		= "timeline/milestones/get_milestones_by_workstream/$1";

// Timeline - Milestones - add
$route['timeline/milestones/add/(:num)']
		= "timeline/milestones/add_milestone/$1";

$route['timeline/milestones/add'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "timeline/milestones/put_milestone";
	}
	
	return "timeline/milestones/add_milestone";
};

// Timeline - Milestones - edit
$route['timeline/milestones/edit/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "timeline/milestones/update_milestone/{$id}";
	}
	
	return "timeline/milestones/edit_milestone/{$id}";
};

$route['timeline/milestones/edit-start-end/(:num)/(:num)/(:num)/(:num)']
		= "timeline/milestones/update_milestone_start_end/$1/$2/$3/$4";

$route['timeline/milestones/edit-start-end/(:num)/(:num)/(:num)']
		= "timeline/milestones/update_milestone_start_end/$1/$2/$3";

// Timeline - Milestones - delete
$route['timeline/milestones/delete/(:num)'] = function($id) {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "timeline/milestones/delete_milestone/{$id}";
	}
	
	return "timeline/milestones/index";
};


// Timeline - Admin
$route['timeline/configure']
		= "timeline/admin/workstreams/index";

// Timeline - Admin - Workstreams
$route['timeline/configure/workstreams'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "timeline/admin/workstreams/workstream_op";
	}
	
	return "timeline/admin/workstreams/index";
};

// Timeline - Admin - Calendar
$route['timeline/configure/calendar'] = function() {
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		return "timeline/admin/workstreams/update_calendar_options";
	}
	
	return "timeline/admin/workstreams/index";
};

// Timeline - Admin - Data export
$route['timeline/export/data-csv']
		= 'timeline/admin/data_export/index';


// Timeline - Get out of jail
$route['timeline/(:any)']
		= 'timeline/index';
