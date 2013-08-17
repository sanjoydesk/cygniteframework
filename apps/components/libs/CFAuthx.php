<?php
namespace Apps\Components\Libs;

use Cygnite\Cygnite;
use Cygnite\Database\CF_ActiveRecords;

class CFAuthx extends CF_ActiveRecords
{
    var $query = NULL;
    public $username = NULL;
    public $tableName =NULL;
    public $database = NULL;
    public $credentials = array();
    public $sessionDetails = array();
    private $authx = NULL;



    public function identifyUser($instance)
    {

        if($instance instanceof AuthxIdentity)
                $this->authx = $instance;

         parent::__construct($this->authx->getDbName());

                 $whereQuery= array();

                 $i = 0;
                 foreach ($this->authx->userCredentials as $key => $value) :
                        if($i===0):
                                $whereQuery[$key.' ='] = $value;
                                $this->username  = $value;
                        endif;

                            if($i==2 || $key == 'status')
                                  $whereQuery["$key ="] = $value;

                        $i++;
                 endforeach;

                 $userCredentials= $this->where($whereQuery)->select('all')->fetch_all($this->authx->getTableName());
                 $this->flushresult();

                      if(($this->num_row_count() && count($userCredentials) ) > 0):

                         if((Cygnite::loader()->encrypt->decode($userCredentials[0]['password']) == $this->authx->userCredentials['password'])):

                                     $credentials['isLoggedIn'] =  TRUE;
                                     $credentials['flashMsg'] = ucfirst($this->username).' has authenticated successfully !';

                                     $this->sessionDetails = $this->authx->getSessionConfig();

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

     public function __destruct()
     {
           unset($this->authx);
     }
}