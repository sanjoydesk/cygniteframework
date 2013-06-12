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
/*RouteMapper::$is_router_enabled = TRUE;
RouteMapper::set_route('category/any','welcome@testing'); */
//Router::$is_router_enabled = TRUE;
//Router::set_route('category/any','welcomeuser@testing');

abstract class Route
{
    public static $path,$routeto;
    public static $routing = array();

    public static function set_route()
    {
       return self::$routing = array(
                              'is_router_enabled' => TRUE,
                              'url'=>'category/list',
                              'routeto' => 'welcomeuser@testing'
                              );
    }

    public static function get_route()
    {
        if(!is_null(self::set_route()))
            return self::set_route();

        return FALSE;
    }

}

//RouteMapper::get('welcomeuser/(:all?)', 'category@view');
//RouteMapper::get('industrial/(:all?)', 'category@list');