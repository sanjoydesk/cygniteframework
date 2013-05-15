<?php

/*
*---------------------------------------------------------------------------------
* ROUTER CONFIG
* --------------------------------------------------------------------------------
* This file is used to enable routing
*
*
* Created Date   : 05-07-2012
* Modified Date  : 06-07-2012
*/

//Set RouteMapper::$is_router_enabled = FALSE; by default.
Router::$is_router_enabled = TRUE;
Router::set_route('category/list','welcomeuser@testing');

//RouteMapper::get('welcomeuser/(:all?)', 'category@view');
//RouteMapper::get('industrial/(:all?)', 'category@list');

