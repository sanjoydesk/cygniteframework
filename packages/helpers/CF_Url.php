<?php
/*
 * ===========================================================================
 *  An open source application development framework for PHP 5.1.6 or newer
 *
 *
 * @access          :  public
 * @Author          :  Sanjoy Dey
 * @Modified By     :
 * @Warning         :  Any changes in this file can cause abnormal behaviour of the framework
 * @Developed       :  PHP-ignite Team
 * ===========================================================================
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