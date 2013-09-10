<?php
namespace Apps\Components\Libraries;

use Cygnite;
use Cygnite\Database\CF_ActiveRecords;

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
 * @package                     Apps
 * @subpackages                 Components
 * @filename                    Authx
 * @description                 This file is used to map all routing of the cygnite framework
 * @author                      Sanjoy Dey
 * @copyright                   Copyright (c) 2013 - 2014,
 * @link	                    http://www.cygniteframework.com
 * @since	                    Version 1.0
 * @filesource
 * @warning                     Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
class Authx extends CF_ActiveRecords
{
    var $query = NULL;
    public $username = NULL;
    public $tableName =NULL;
    public $database = NULL;
    public $credentials = array();
    public $sessionDetails = array();
    private $cfAuthx = NULL;



    public function identifyUser($instance)
    {

        if($instance instanceof AuthxIdentity)
                $this->cfAuthx = $instance;

                try{
                     parent::__construct($this->cfAuthx->getDbName());
                 } catch(\Exception $ex){
                        echo $ex->getTrace();
                 }

                 $whereQuery= array();

                 $i = 0;
                 foreach ($this->cfAuthx->userCredentials as $key => $value) :
                        if($i===0):
                                $whereQuery[$key.' ='] = $value;
                                $this->username  = $value;
                        endif;

                            if($i==2 || $key == 'status')
                                  $whereQuery["$key ="] = $value;

                        $i++;
                 endforeach;

                 try{
                         $userCredentials= $this->where($whereQuery)->select('all')->fetch_all($this->cfAuthx->getTableName());
                        // $this->flush();
                 } catch(\Exception $ex){
                        echo $ex->getTrace();
                 }

                      if(($this->num_row_count() && count($userCredentials) ) > 0):

                         if((Cygnite::loader()->encrypt->decode($userCredentials[0]['password']) == $this->cfAuthx->userCredentials['password'])):

                                     $credentials['isLoggedIn'] =  TRUE;
                                     $credentials['flashMsg'] = ucfirst($this->username).' has authenticated successfully !';

                                     $this->sessionDetails = $this->cfAuthx->getSessionConfig();

                                                 foreach ($this->sessionDetails['value'] as $key => $val):
                                                            $credentials[$val] =    $userCredentials[0][$val];
                                                            unset($userCredentials[0][$val]);
                                               endforeach;

                                                 $isSessionExists= Cygnite::loader()->session->save($this->sessionDetails['key'],$credentials);
                                                return ($isSessionExists == TRUE) ? TRUE : FALSE;
                            else:
                                    return FALSE;
                            endif; // password validation end

                 else:
                        return FALSE;
                endif; // row count end

    }

    /*
     * Check user logged in or not
     * @return bolean
     *
     */
    public function isLoggedIn($loginKey)
    {
            if(Cygnite::loader()->session):
                    //If user has valid session, and such is logged in
                    $sessionArray = Cygnite::loader()->session->retrieve($loginKey);
                    if(!empty($sessionArray['isLoggedIn']))
                           return TRUE;
                    else
                             return FALSE;
            else:
                    return FALSE;
            endif;
     }

     public function logout()
     {
                Cygnite::loader()->session->vanish();
                Url::redirect_to(Url::getBase());
     }

     public function __destruct()
     {
           unset($this->cfAuthx);
     }
}
