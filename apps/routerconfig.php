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
RouteMapper::$is_router_enabled = TRUE;
RouteMapper::set_route('category/any','welcome@testing');

//RouteMapper::get('welcomeuser/(:all?)', 'category@view');
//RouteMapper::get('industrial/(:all?)', 'category@list');

