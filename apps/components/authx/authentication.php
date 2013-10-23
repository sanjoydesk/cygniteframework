<?php
namespace Apps\Components\Authx;

use Cygnite\Cygnite;
use Apps\Components\Authx\Identity;

/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
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
 * @package                          Apps
 * @subpackages                 Components
 * @filename                         AuthxIdentity
 * @description                    This file is used to map all routing of the cygnite framework
 * @author                           Sanjoy Dey
 * @copyright                      Copyright (c) 2013 - 2014,
 * @link	                    http://www.cygniteframework.com
 * @since	                    Version 1.0
 * @filesource
 * @warning                       Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class Authentication
{
    public $username;
    public $credentials = array();
    public $sessionDetails = array();
    private $cfAuth;

    public function __construct($instance)
    {
        if ($instance instanceof Identity) {
            $this->cfAuth = $instance;
        }

        $this->tableName = $this->cfAuth->getTableName();

    }

    public function identifyUser()
    {
        $whereQuery= array();

        $i = 0;
        foreach ($this->cfAuth->userCredentials as $key => $value) {
            if ($i===0) {
                    $whereQuery[$key.' ='] = $value;
                    $this->username  = $value;
            }

            if ($i==2 || $key == 'status') {
                $whereQuery["$key ="] = $value;
            }

            $i++;
        }

        try {
            $userCredentials= $this->cfAuth->where($whereQuery)->findAll();

        } catch (\Exception $ex) {
            echo $ex->getTraceAsString();
        }

        if (($this->cfAuth->rowCount() && count($userCredentials) ) > 0) {

            if (Cygnite::loader()->encrypt->decode($userCredentials[0]->password) ==
                $this->cfAuth->userCredentials['password']
            ) {
                $credentials['isLoggedIn'] =  true;
                $credentials['flashMsg'] = ucfirst($this->username).' has authenticated successfully !';

                $this->sessionDetails = $this->cfAuth->getSessionConfig();

                foreach ($this->sessionDetails['value'] as $key => $val) {
                        $credentials[$val] =    $userCredentials[0][$val];
                        unset($userCredentials[0][$val]);
                }

                $isSessionExists= Cygnite::loader()->session->save(
                    $this->sessionDetails['key'],
                    $credentials
                );

                return ($isSessionExists == true) ?
                    true :
                    false;

            } else {

                return false;

            } // password validation end

        } else {

            return false;

        } // row count end

    }

    /*
    * Check user logged in or not
    * @return boolean
    *
    */
    public function isLoggedIn($loginKey)
    {
        if (Cygnite::loader()->session) {
            //If user has valid session, and such is logged in
            $sessionArray = Cygnite::loader()->session->retrieve($loginKey);
            if (!empty($sessionArray['isLoggedIn'])) {
                return true;
            } else {
                return false;
            }
        } else {
             return true;
        }
    }

    public function rememberMe()
    {

    }

    public function attempts()
    {

    }

    public function logout()
    {
        Cygnite::loader()->session->vanish();
        Url::redirectTo(Url::getBase());
    }

    public function __destruct()
    {
        unset($this->cfAuth);
    }
}
