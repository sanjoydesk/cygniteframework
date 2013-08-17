<?php
namespace Cygnite\Libraries\Globals;

use Cygnite\Libraries\Globals as Globals;

/**
 *  Cygnite Framework
 *  An open source application development framework for PHP 5.3x or newer
 *
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       :  CF_Cookie
 * @Description                   :  Cookie class that inherits Global base class and implements ISecureData interface
 * @Author                           : Sanjoy Dey
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *@todo                             : need to add Cookie settings
 *
 */

class cookie extends Globals implements ISecureData
{
        public $_var = "_COOKIE";
        private $_cookie; // the returned COOKIE values

        /**
         * Sets or returns the cookie variable value.
         *
         * @param string $name The cookie name.
         * @param mixed $value If given, the cookie variable will assume this value.
         *                     Otherwise, the function returns current cookie variable value.
         * @param int $expire  Optional excfration time, in minutes. Defaults to 24 hours (60 * 24 = 1440).
         * @return mixed O valor do cookie.
         */
        public function save($name, $value = NULL, $expire = 1440)
        {
               // No given value, the user is requesting the cookie value.
                if( is_null($value) ):
                    $this->_cookie = ( (isset($_COOKIE[$name]) ) ?   $_COOKIE[$name] :  NULL);

                    return $this->_cookie;
                endif;
                $expire = time() + ($expire * 60); // Excfre will passed in minutes, so multiply by 60 seconds.
                setcookie($name, $value, $expire);  // Define Cookie.
        }

        public function values()
        {

        }

       /**
         * Deletes a cookie.
         *
         * @param string $cookie Cookie name to be deleted.
         */
        public  function delete($cookie)
        {
              setcookie($cookie, FALSE, time() - 3600);
        }

}
