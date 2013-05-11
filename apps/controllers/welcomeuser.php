<?php
class WelcomeuserAppsController extends CF_ApplicationController
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
                $insertarray = array(
                                            'Name' => 'Framework 6 ',
                                            'EntryDate' => date('Y-m-d H:m:s'),
                                            'Comment' => 'Hi Users'
                );
                $file = "G://wamp/www/cygnus/demo/rep.zip";



               //$this->request('Downloader')->download($file);
                //var_dump($this->request('Zip'));
           //     $this->request('Zip')->make_zip($file,$file,"G://wamp/www/cygnus/demo/",'test');

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


                $is_authenticated = $this->request('AuthManager')->validate($query ,'phpignite')->build_user_session($sess_details);

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
                 if($is_authenticated === TRUE):
                        echo "User Authenticated Successfully";
                 else:
                         echo "Not a valid User";
                 endif;

            $data['values'] = "Sanjay";
            $this->app()->render("user_details_view",$data);
        }

        function action_test()
        {

                $this->request('Session')->setsession('name', 'Hello World !!   Cygnus is the great Framework');
               // $this->request('session')->unset_session();
              echo $this->request('session')->getsession('name');

                $this->app()->helper('FormValidator');
                $required_fields = array(
                                                            "name"=>"User Name",
                                                            "country"=>"Country Name ",
                                                           "email"=>"Email Address",
                                                );
              $data['errors']  = FormValidator::validate_require_fields($required_fields,'required','txtSubmit');
              $postvalues =array();
              $var =  $this->request('HTTPRequest')->is_request_posted('btnSubmit');
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


               $this->request('Cache')->build("FileCache")->write_cache('welcome_page', $this->app()->render("welcome",$data,'ui_contents'));
               echo $this->request('Cache')->build("FileCache")->read_cache('welcome_page');
        }

        function action_testing()
        {
               //echo $this->request('Uri')->urisegment(6);
               // echo "Routing call here ";
                $this->app()->render("welcome",$data);
        }
}