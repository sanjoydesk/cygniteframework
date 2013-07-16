<?php if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');

    class WelcomeuserAppsController extends CF_BaseController
    {
      /**
        * --------------------------------------------------------------------------
        * The Default Controller
        *--------------------------------------------------------------------------
        *  This controller respond to uri begining with welcomeuser and also
        *  respond to root url like "welcomeuser/index"
        *
        * Your GET request of "welcomeuser/form" will repond like below -
        *
        *      public function action_form()
        *     {
        *            echo "Cygnite : Hellow ! World ";
        *     }
        * Note: By default cygnite doesn't allow you to pass query string in url, which
        * consider as bad url format.
        *
        * You can also pass parameters into the function as below-
        * Your request to  "welcomeuser/form/2134" will pass to
        *
        *      public function action_form($id = ")
        *      {
        *             echo "Cygnite : Your user Id is $id";
        *      }
        * In case if you are not able to access parameters passed into method
        * directly as above, you can also get the uri segment
        *  echo Url::segment(2);
        *
        * That's it you are ready to start your awesome application with Cygnite Framework.
        *
        */
        public function __construct()
        {
              parent::__construct();
        }

         public function action_index()
      {
               //$userdetails=  Cygnite::loader()->model('activerecords')->getdetails();
               //$values = Cygnite::loader()->model('activerecords')->gettags();
               //show($values);
               //show($userdetails);
           // $this->app()->helper('Assets');
              Cygnite::import(CF_SYSTEM.'>cygnite>helpers>CF_Assets');
             //echo $file = CYGNITE_BASE.DS."webroot".DS."uploads".DS."AltoRouter-master.zip";
             $file = CYGNITE_BASE.DS."webroot".DS."uploads".DS;
             //Cygnite::loader()->request('Downloader')->download($file);

            //  var_dump(Cygnite::loader()->request('Zip'));
          //  Cygnite::loader()->request('Zip')->make('CygniteStructure.png',$file,CYGNITE_BASE.DS."webroot".DS."uploads".DS,'test');
              //Cygnite::loader()->model('activerecords')->insert();
              //Cygnite::loader()->model('activerecords')->update();
             //Cygnite::loader()->model('activerecords')->delete();

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

               //Cygnite::loader()->request('Cache')->build("FileCache")->write_cache('user',$query);
             // show(Cygnite::loader()->request('Cache')->build("FileCache")->read_cache('user'));

              //echo Cygnite::loader()->request('Encrypt')->encrypt('sanjay@123');
             //$is_authenticated = Cygnite::loader()->request('Authx')->validate($query ,'cygnite')->build_user_session($sess_details);

               //var_dump($this->request('Session')->getsession('validated_login'));
             //var_dump(Cygnite::loader()->request('Authx')->is_logged_in('validated_login'));
              /* if($is_authenticated === TRUE):
                      echo "User Authenticated Successfully";
               else:
                       echo "Not a valid User";
               endif; */
              // var_dump(Cygnite::loader()->request('Session')->retrieve('validated_login'));
           $this->render("welcome")->with(array(
                                                                                     'author'=>'sanjoy',
                                                                                    'email'=>'sanjoy09@hotmail.com',
                                                                                    'country'=> $this->country
                                                                                  ));

      }

        public function action_gethtml()
       {
        Cygnite::import(CF_SYSTEM.'>cygnite>libraries>CF_Form');
        $form = CF_Form::initialize("contact_form");
        var_dump($form);
        echo CF_Form::open(array(
                               'method' => 'post',
                               'action' => '/libreoffice/test',
                               'id' => '',
                               'class' => ''
                              ));

        echo $form->input("name",array("type"=>"text"))->class("textbox","required")->id("name");
        echo $form->input("age")->type("password")->value("true")->id("age");
        echo $form->textarea("age1")->value("true")->id("age");
        echo $form->label()->value('Username')->class('user-name');
        echo $form->select("years")->style("width:100px;")->options(
                   array("1997"=>"1997","1996"=>"1996","1995"=>"1995","1994"=>"1994","1993"=>"1993","1992"=>"1992","1991"=>"1991")
                )->id("years");

        echo $form->input("checkbox2",array("type"=>"checkbox"))->class("required")->id("name")->checked("checked");
        echo $form->input("radioname",array("type"=>"radio"))->class("required")->id("radioname")->checked("checked");
        echo $form->input("txtsubmit",array("type"=>"submit"))->value('Login')->class("required")->id("login-id");
        echo $form->input("fileupload",array("type"=>"file"))->multiple('multiple');
        echo $form->input("update",array("type"=>"button"))->value('Save');
        echo CF_Form::close();

    }


        public function action_registration()
        {
             Cygnite::import(CF_SYSTEM.'>cygnite>libraries>CF_Form');
              var_dump(Cygnite::loader()->request('Post')->is_posted('is_posted'));
              $postvalues =array();
              $haspost = Cygnite::loader()->request('Post')->is_posted('btnSubmit');
              if(isset($haspost) === TRUE){
                      try{
                             // Cygnite::loader()->request('FileUpload')->file = $_FILES;
                           //   var_dump(Cygnite::loader()->request('FileUpload')->file);
                             // Cygnite::loader()->request('FileUpload')->ext = array("png");

                      if(isset($_FILES['supporting_documents'])){
                            Cygnite::loader()->request('FileUpload')->upload(array(
                                                                                                                                 "upload_path"=>"/webroot/uploads",
                                                                                                                                "file_name"=>"supporting_documents",
                                                                                                                                ''
                                                                                                                               )
                               );
                             $postvalues = Cygnite::loader()->request('Post')->values();
                  //    $this->app()->users->insert($postvalues);
                      }
                      }catch (Exception $e){
                          echo $e->getMessage();
                      }
                   //   exit;
              }
            //  $users=  $this->app()->users->getdetails();

             $this->render('register')->with(array());
        }

      public function action_userlist()
      {
              $this->app()->model('users');
              $users = $this->app()->users->getusers();
              $this->render('userlist')->with(array('users' => $users));
      }

      function action_testglobals()
      {
              $this->app()->helper('FormValidator');
             Cygnite::import(CF_SYSTEM.'>cygnite>libraries>CF_Form');
          //    $this->app()->model('users');
              $required_fields = array(
                                                          "name"=>"User Name",
                                                          "country"=>"Country Name ",
                                                         "email"=>"Email Address",
                                              );
            $data['errors']  = FormValidator::validate_require_fields($required_fields,'required','txtSubmit');
            $postvalues =array();
            Cygnite::loader()->request('Session')->save('cygnite','Hellow !! Cygnite you are awesome !');
             //Cygnite::loader()->request('Session')->delete('cygnite');
            echo Cygnite::loader()->request('Session')->retrieve('cygnite');

              if(Cygnite::loader()->request('Post')->is_posted('btnSubmit') === TRUE):
                      $postvalues = Cygnite::loader()->request('Post')->values();
                     var_dump($postvalues);
                     $this->app()->users->insert($postvalues);
              endif;

             $data['email']= FormValidator::is_valid_email("email","Email Address","required","checkvalid");
              echo $encryt= Cygnite::loader()->request('Encrypt')->encode("sanjoy"); echo "<br>";
              echo Cygnite::loader()->request('Encrypt')->decode($encryt);
             $this->app()->helper('Assets');


            //Cygnite::request('Cache')->build("FileCache")->write_cache('welcome_page', $this->render("welcome",TRUE));
            //echo Cygnite::request('Cache')->build("FileCache")->read_cache('welcome_page');
                     $this->createsections(array('header'=>'header.view','content'=>'register.view','footer'=>'footer.view'));
               $this->setlayout('admin',array(
                                                'title'=> "Welcome Sanjoy",
                                                'header_title'=> "Header Page",
                                                'content_title'=> "Content Page",
                                                'footer_title'=> "Footer Page"
                                                ));
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
               Cygnite::import(CF_SYSTEM.'>cygnite>libraries>CF_Form');
               $this->app()->helper('Assets');

               Cygnite::loader()->request('Mailer')->Host = '';
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