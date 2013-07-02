<?php if ( ! defined('CF_SYSTEM')) exit('Direct script access not allowed');
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


class CF_Authx  implements IRegistry
{
    var $query = NULL;
    private $db, $auth = NULL;
    private $db_name = NULL;
    private $user_password =NULL;
    private $app = NULL;
    private $sess_key = NULL;

    function __construct()
    {
             $this->app=  GHelper::get_singleton();
             //CF_AppRegistry::load_lib_class('Encrypt','BaseSession');
    }

    public static function initialize($dirRegistry = array()) {}

    public function apps_load($key) { }

     public function validate($queryArray = array(),$db_name)
     {
           $arraykey= array_keys($queryArray);
            if(empty($queryArray))
                   throw new ErrorException('Empty argument  passed to '.__FUNCTION__);
            if($db_name == "")
                    throw new ErrorException('Database name should not be empty '.__FUNCTION__);

            if($this->query == NULL):
                    $status = ($queryArray[$arraykey[3]]  != "" || $queryArray[$arraykey[3]]  != 0) ? " AND `".$arraykey[3]."` = '".$queryArray[$arraykey[3]]."' " : '';
                    $this->query = (string) "SELECT * FROM `".$queryArray[$arraykey[0]]."` WHERE `".$arraykey[1]."` = '".$queryArray[$arraykey[1]]."'  ".$status;
                    $this->user_password = $queryArray[$arraykey[2]];
                    $this->db_name = $db_name;
            endif;

           return $this;
     }

     /*
      * Checking for user authentication process
      * @param array
      * @return bolean
      */
     public function build_user_session($sess_details = array())
     {
                $this->auth =  CF_ApplicationModel::get_db_instance($this->db_name);
                $user_credentials = array();

                $select_query = (!is_null($this->query)) ? $this->query : "";
                if(is_null($select_query) || trim($select_query) == ' ')
                       throw new Exception('Empty query passed ');

               $this->auth->sql_generator(FALSE);
                $stmt = $this->auth->db->query($select_query);   //build user query
                $stmt->execute();
                $user_credentials = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if($stmt->rowCount() > 0):
                           $user_credentials[0]['password']= $this->app->request('Encrypt')->decrypt($user_credentials[0]['password']);

                         if(($user_credentials[0]['password'] == $this->user_password)):
                                     $user_credentials['logged_in'] =  TRUE;
                                     $user_credentials['flash_message'] = 'You have Successfully logged in !';
                                     $this->sess_key = $sess_details['session_key'];
                                                 foreach ($sess_details['session_value'] as $key => $val):
                                                            $user_credentials[$val] =    $user_credentials[0][$val];
                                                            unset($user_credentials[0][$val]);
                                               endforeach;
                                               foreach($user_credentials as $userkey => $uservalue):
                                                             unset($user_credentials[0]);
                                               endforeach;
                                                $is_set_sess= $this->app->request('BaseSession')->setsession($this->sess_key,$user_credentials);
                                     return ($is_set_sess == TRUE) ? TRUE : FALSE;
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
    public function is_logged_in($login_key)
    {
            if($this->app->request('Session')):
                    //If user has valid session, and such is logged in
                    $sess_array = $this->app->request('BaseSession')->getsession($login_key);
                    if(!empty($sess_array['logged_in']))
                           return TRUE;
                    else
                             return FALSE;
            else:
                    return FALSE;
            endif;
     }

     function __destruct()
     {
           unset($this->app);
           unset($this->db);
     }
}