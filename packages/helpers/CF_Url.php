<?php
       /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
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
        public static function request_Uri_segment($Uri="")
        {
                CF_AppRegistry::load('Uri')->Urisegment($Uri = '', $type = 'location', $http_response_code = 302);
        }


        /*
        * This Function is to encode the url
        *
        * @access public
        * @param  int
        * @return string
        */

        public static function url_encode()
        {

        }
}