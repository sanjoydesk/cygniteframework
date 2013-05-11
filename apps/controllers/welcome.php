<?php if ( ! defined('PI_BASEPATH')) exit('No direct script access allowed');
/*
*===============================================================================================
*
* ===============================================================================================
*/
class WelcomeAppsController extends PI_ApplicationController
{
            function __construct()
            {
                parent::__construct();
               $this->app()->model('DBmodel');
              // $this->app()->library('Encrypt');
              $this->app()->library('Cache');
              $this->app()->library('HTTPRequest');
               $enc = $this->request('Encrypt')->encrypt("sanjoy");
               $this->request('Encrypt')->decrypt($enc);
          }

        public function index()
        {
                $data['userdetails']=  $this->app()->DBmodel->getdetails();


                //show($data['userdetails']);
              //  $this->request('Session')->setsession('name', 'Hello World !!   Php-Ignite is the great Framework');
               // $this->request('session')->unset_session();
              // echo $this->request('session')->getsession('name');
                //PI_CoreLoader::load_lib_class('PI_Cache');
                //var_dump(PI_CoreLoader::load('Cache'));
                //PI_CoreLoader::load('Cache')->read_cache('hello');
                //$this->request('Cache')->initialize();
                //$this->app_library('Cache')->write_cache('hello', 'Hello World! This is cache ');
             //echo $this->app_library('Cache')->read_cache('hello');
               //$this->request('Cache')->write_cache('welcome_page', $this->app()->presenter("welcome",$data,'ui_contents'));
               //echo $this->request('Cache')->read_cache('welcome_page');
                $insertarray = array(
                                            'Name' => 'Framework 6 ',
                                            'EntryDate' => date('Y-m-d H:m:s'),
                                            'Comment' => 'Hi Users'
                );
               //$this->app_model()->insert($insertarray);
               //  PI_AppLoader::app_model()->edit($insertarray);
               // PI_AppLoader::app_model()->delete('25');

                $this->app()->helper('FormValidations');
                $required_fields = array(
                                                            "name"=>"User Name",
                                                            "country"=>"Country Name ",
                                                           "email"=>"Email Address",
                                                );
              $data['errors']  = validate_require_fields($required_fields,'required','txtSubmit');
              $postvalues =array();
              $var =  $this->request('HTTPRequest')->is_request_posted('btnSubmit');
                if($var === TRUE):
                        $postvalues = $this->request('HTTPRequest')->post_values();
                       //var_dump($postvalues);
                endif;
                /*
               if(isset ($_POST['txtSubmit'])):
                    if($data['errors'] === TRUE):
                    echo "Validation successful";exit;
                    endif;
               endif;

               */ //show($data['userdetails']);
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

                $is_authenticated = $this->request('Authentication')->validate_authentication($query ,'phpignite')->build_user_session($sess_details);

                 //var_dump($this->request('Session')->getsession('validated_login'));
               //var_dump($this->request('Authentication')->is_logged_in('validated_login'));
                 if($is_authenticated === TRUE):
                        echo "User Authenticated Successfully";
                 else:
                         echo "Not a valid User";
                 endif;

            $data['email']= is_valid_email("email","Email Address","required","checkvalid");
           // $encryt= $this->request('Encrypt')->encrypt("sanjoy");
            //$this->app_library('Encrypt')->decrypt($encryt);
            $data['values'] = "Sanjay";
            $data1 = $this->app()->DBmodel->getProducts();
            $this->app()->presenter("user_details_view",$data);
        }

        function test()
        {

        }

}