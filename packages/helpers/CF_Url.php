<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :   Helper
 * @Filename                       :  Url
 * @Description                   :  This helper is used to take care of your url related stuffs
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class CF_Url
{
       /**
        * Header Redirect
        *
        * @access	public
        * @param	string	the URL
        * @param	string	the method: location or redirect
        * @return	string
        */
      public static function redirect_to($Uri = '', $type = 'location', $http_response_code = 302)
      {
            CF_AppRegistry::load('Uri')->redirect($Uri = '', $type = 'location', $http_response_code = 302);
      }

       /*
        * This Function is to get Uri Segment of the url
        *
        * @access public
        * @param  int
        * @return string
        */
        public static function request_uri_segment($Uri="")
        {
                CF_AppRegistry::load('Uri')->urisegment($Uri = '');
        }


        /*
        * This Function is to encode the url
        *
        * @access public
        * @param  int
        * @return string
        */

        public static function encode()
        {

        }

        public static function decode()
        {

        }
}