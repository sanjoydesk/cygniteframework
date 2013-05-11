<?php
class CategoryAppsController extends CF_ApplicationController
{
           public function __construct()
           {
                parent::__construct();
               $this->app()->model('DBmodel');
               $enc = $this->request('Encrypt')->encrypt("sanjay@123");
               $this->request('Encrypt')->decrypt($enc);
          }

          public function action_index()
        {

                 $data['userdetails']=  $this->app()->DBmodel->getdetails(); //show($data['userdetails']);

                   /*
               $this->app()->DBmodel->insert($insertarray);
               $this->app()->DBmodel->edit($insertarray);
               $this->app()->DBmodel->delete('25');
               */
                $sess_details = array(
                                                        'session_key' => 'validated_login',
                                                        'session_value' => array('id','username','email')
                        );
                $query = array(
                                            'table_name' => 'userdetails',
                                            'username' => 'sanjay',//post values need to be passed for username or email address field
                                            'password' => 'sanjay@123',
                                            'status' => '' // optional field to check user authentication
                );

                // $this->request('Cache')->build("FileCache")->write_cache('user',$query);
               // show($this->request('Cache')->build("FileCache")->read_cache('user'));


                $is_authenticated = $this->request('AuthManager')
                                                             ->validate($query ,'phpignite')
                                                             ->build_user_session($sess_details);



                 //var_dump($this->request('Session')->getsession('validated_login'));
               //var_dump($this->request('Authentication')->is_logged_in('validated_login'));
                 if($is_authenticated === TRUE):
                        echo "User Authenticated Successfully";
                 else:
                         echo "Not a valid User";
                 endif;

            $data['values'] = "Sanjay";
            $this->app()->render("user_details_view",$data);
        }



}