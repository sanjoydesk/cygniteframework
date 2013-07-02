<?php
    class WelcomeuserAppsController extends CF_BaseController
    {
        public function __construct()
        {
              parent::__construct();
        }


        public function action_dbtest()
        {
              $this->app()->model('users');
              $users=  $this->app()->users->getdetails();
                $postvalues =array();
              if(TRUE===$this->app()->request('HTTPRequest')->is_submited('btnSubmit')):
                      $postvalues = $this->app()->request('HTTPRequest')->post_values();
                      $this->app()->users->insert($postvalues);
              endif;

             $this->render('register')->with(array());
        }

        public function action_index()
      {
               //$data['userdetails']=  $this->app()->users->getdetails(); //show($data['userdetails']);
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
                       $this->app()->request('FileUpload')->file = $_FILES;
                          //$this->app_library('File_Upload')->ext = array("jpeg");

              if(isset($_FILES['supporting_documents']))
                          $this->app()->request('FileUpload')->upload(array("upload_path"=>"/webroot/uploads","file_name"=>"supporting_documents","multi_upload"=>true));
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
           $this->render("welcome")->with(array(
                                                                                     'author'=>'sanjoy',
                                                                                    'email'=>'sanjoy09@hotmail.com',
                                                                                    'country'=> $this->country
                                                                                  ));

      }

      public function action_userlist()
      {
              $this->app()->model('users');
             // $users = $this->app()->users->getdetails();
              $users = $this->app()->users->getusers();
              $this->render('userlist')->with(array('users' => $users));
      }

      function action_test()
      {

            //  $this->app()->request('BaseSession')->setsession('name', 'Hello World !!   Cygnite is the great Framework');
             // $this->app()->request('session')->unset_session();
         //   echo $this->app()->request('BaseSession')->getsession('name');

              $this->app()->helper('FormValidator');
              $required_fields = array(
                                                          "name"=>"User Name",
                                                          "country"=>"Country Name ",
                                                         "email"=>"Email Address",
                                              );
            $data['errors']  = FormValidator::validate_require_fields($required_fields,'required','txtSubmit');
            $postvalues =array();

            $var =  Cygnite::loader()->request('HTTPRequest')->is_submited('btnSubmit');
              if($var === TRUE):
                      $postvalues = Cygnite::loader()->request('HTTPRequest')->post_values();
                     //var_dump($postvalues);
              endif;

             if(isset ($_POST['txtSubmit'])):
                  if($data['errors'] === TRUE):
                  echo "Validation successful";exit;
                  endif;
             endif;

             $data['email']= FormValidator::is_valid_email("email","Email Address","required","checkvalid");
           //   $encryt= $this->app()->request('Encrypt')->encrypt("sanjoy");
            //  echo $this->app()->request('Encrypt')->decrypt($encryt);
             $this->app()->helper('Assets');

            // Cygnite::request('Cache')->build("FileCache")->write_cache('welcome_page', $this->render("welcome",TRUE));
            echo Cygnite::loader()->request('Cache')->build("FileCache")->read_cache('welcome_page');
      }
      private $country = 'India';

      function action_testing()
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
             //  $this->app()->model('users');
             //   echo Url::segment(3);
             // $users = $this->app()->users->getdetails();
//show($users);
               $this->app()->helper('Assets');
/*
               $this->app()->request('Mailer')->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
               $this->app()->request('Mailer')->SMTPAuth = true;     // turn on SMTP authentication
               $this->app()->request('Mailer')->Username = "sanjoyinfotech@gmail.com";  // SMTP username
               $this->app()->request('Mailer')->Password = ""; // SMTP password
               $this->app()->request('Mailer')->From = 'dey.sanjoy0@gmail.com';
               $this->app()->request('Mailer')->AddAddress('sanjoy09@hotmail.com');
               $this->app()->request('Mailer')->AddCC('dey.sanjoy0@gmail.com');
               $this->app()->request('Mailer')->AddCC('dey.sanjoy0@gmail.com');
               $this->app()->request('Mailer')->IsHTML(TRUE);
               $this->app()->request('Mailer')->Subject = 'CF Mailer Testing';
               $this->app()->request('Mailer')->Body = 'Hi ! This is CF Mailer Test goes here';
               if($this->app()->request('Mailer')->Send() == TRUE){
                   echo 'Mail Sent';exit;
               }else{
                   echo $this->app()->request('Mailer')->ErrorInfo;exit;
               } */
            //   show(get_declared_classes());

               $this->createsections(array('header'=>'header.view','content'=>'register.view','footer'=>'footer.view'));
               $this->setlayout('admin',array(
                                                'title'=> "Welcome Sanjoy",
                                                'header_title'=> "Header Page",
                                                'content_title'=> "Content Page",
                                                'footer_title'=> "Footer Page"
                                                ));

              /* $this->render("register")->with(array(
                                                            'author' => 'Sanjoy',
                                                            'Country'=>$this->country,
                                                            'users' =>$users
                                                           )); */
             //$this->render("welcome",TRUE);
      }
    }
