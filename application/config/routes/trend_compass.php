<?php

// Trend compass
$route['trend-compass']
		= 'trend_compass/index';


// Trend compass - Results
$route['trend-compass/results']
		= 'trend_compass/results/index';


// Trend compass - Admin - Rounds
$route['trend-compass/configure/rounds']
		= 'trend_compass/admin/rounds/index';


// Trend compass - Admin - Filters
$route['trend-compass/configure/filters']
		= 'trend_compass/admin/filters/index';


// Trend compass - Admin - Settings
$route['trend-compass/configure/settings']
		= 'trend_compass/admin/settings/index';


// Trend compass - Get out of jail
$route['trend-compass/(:any)']
		= 'trend_compass/index';
