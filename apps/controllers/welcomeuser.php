<?php
/*
*===============================================================================================
*
* ===============================================================================================
*/
    class WelcomeuserAppsController extends CF_BaseController
    {
        public function __construct()
        {
              parent::__construct();
             $enc = $this->request('Encrypt')->encrypt("sanjay@123");
             $this->request('Encrypt')->decrypt($enc);
        }

        public function __call($name, $arguments)
        {
            echo "Error occurs";
            var_dump($name);
        }

        public function action_dbtest()
        {
              $this->app()->model('usersmodel');
               $insertarray = array(
                                          'Name' => 'Cygnite Framrework ',
                                          'EntryDate' => date('Y-m-d H:m:s'),
                                          'Comment' => 'Framework For Webartists'
              );
              $users=  $this->app()->usersmodel->getdetails();

              //show($users);

                $postvalues =array();
               //$var =  $this->request('HTTPRequest')->is_submited('btnSubmit');
              if(TRUE===$this->request('HTTPRequest')->is_submited('btnSubmit')):
                      $postvalues = $this->request('HTTPRequest')->post_values();
                      $this->app()->usersmodel->insert($postvalues);
              endif;

             $this->app()->render('register');
        }

        public function action_index()
      {
               //$data['userdetails']=  $this->app()->usersmodel->getdetails(); //show($data['userdetails']);
            $this->app()->helper('Assets');
              $insertarray = array(
                                          'Name' => 'Framework 6 ',
                                          'EntryDate' => date('Y-m-d H:m:s'),
                                          'Comment' => 'Hi Users'
              );
              $file = "G://wamp/www/cygnite/demo/rep.zip";



             //$this->request('Downloader')->download($file);
              //var_dump($this->request('Zip'));
         //     $this->request('Zip')->make_zip($file,$file,"G://wamp/www/cygnus/demo/",'test');

                 /*
             $this->app()->usersmodel->insert($insertarray);
             $this->app()->usersmodel->edit($insertarray);
             $this->app()->usersmodel->delete('25');
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


            //  $is_authenticated = $this->request('Authx')->validate($query ,'cygnite')->build_user_session($sess_details);

                try{
                       $this->request('FileUpload')->file = $_FILES;
                          //$this->app_library('File_Upload')->ext = array("jpeg");

              if(isset($_FILES['supporting_documents']))
                          $this->request('FileUpload')->upload(array("upload_path"=>"/webroot/uploads","file_name"=>"supporting_documents","multi_upload"=>true));
              }catch (Exception $e){
                  echo $e->getMessage();
              }
               //var_dump($this->request('Session')->getsession('validated_login'));
             //var_dump($this->request('Authentication')->is_logged_in('validated_login'));
               /*if($is_authenticated === TRUE):
                      echo "User Authenticated Successfully";
               else:
                       echo "Not a valid User";
               endif; */
           $this->app()->render("welcome")->with(array(
                                                                                     'author'=>'sanjoy',
                                                                                    'email'=>'sanjoy09@hotmail.com',
                                                                                    'country'=> $this->country
                                                                                  ));

      }

      public function action_userlist()
      {
              $users = $this->app()->usersmodel->getusers();
              $this->app()->render('userlist')->with(array('users' => $users));
      }

      function action_test()
      {

              $this->request('BaseSession')->setsession('name', 'Hello World !!   Cygnite is the great Framework');
             // $this->request('session')->unset_session();
            echo $this->request('BaseSession')->getsession('name');

              $this->app()->helper('FormValidator');
              $required_fields = array(
                                                          "name"=>"User Name",
                                                          "country"=>"Country Name ",
                                                         "email"=>"Email Address",
                                              );
            $data['errors']  = FormValidator::validate_require_fields($required_fields,'required','txtSubmit');
            $postvalues =array();
            $var =  $this->request('HTTPRequest')->is_submited('btnSubmit');
              if($var === TRUE):
                      $postvalues = $this->request('HTTPRequest')->post_values();
                     //var_dump($postvalues);
              endif;

             if(isset ($_POST['txtSubmit'])):
                  if($data['errors'] === TRUE):
                  echo "Validation successful";exit;
                  endif;
             endif;

             $data['email']= FormValidator::is_valid_email("email","Email Address","required","checkvalid");
              $encryt= $this->request('Encrypt')->encrypt("sanjoy");
              echo $this->request('Encrypt')->decrypt($encryt);

          //   $this->request('Cache')->build("FileCache")->write_cache('welcome_page', $this->app()->render("welcome",'','ui_contents'));
             echo $this->request('Cache')->build("FileCache")->read_cache('welcome_page');
      }
      private $country = 'India';
      function action_testing($param1,$param2,$param3)
      {      //var_dump($param1);
              //var_dump($param2);
              //var_dump($param3);
           //  echo $this->request('Uri')->urisegment(2);


             /*  $this->request('Cache')->build("FileCache")->write_cache('welcome_page', $this->app()->render("welcome",array('username'=>'sanjay','email'=>'sanjoy09@hotmail.com'),'ui_contents'));
             echo $this->request('Cache')->build("FileCache")->read_cache('welcome_page');
           $this->app()->render("welcome",array(
                                                                                     'username'=>'sanjay',
                                                                                    'email'=>'sanjoy09@hotmail.com',
                                                                                    'country'=> $this->country
                                                                                  )); */

             //$this->request('Session')->setsession('name', 'Hello World !!   Cygnite is the great Framework');
             // $this->request('session')->unset_session();
          //  echo $this->request('session')->getsession('name');
               $this->app()->model('usersmodel');
                echo $this->request('Uri')->urisegment(2);


               $this->app()->render("register")->with(array(
                                                            'author' => 'Sanjoy',
                                                            'Country'=>$this->country
                                                           ));
             //$this->app()->render("welcome",TRUE)->with(array('user'=>'Sanjay'));
      }
    }