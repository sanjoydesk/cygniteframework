<?php if (!defined('CF_SYSTEM')) exit('No direct script access allowed');
    /*
    * ===============================================================================================
    *
    * ===============================================================================================
    */
    class WelcomeAppsController extends CF_BaseController
    {
        function __construct()
        {
            parent::__construct();
            $this->app()->model('usersmodel');
            $this->app()->model('category');
        }

        public function action_index()
        {
            $userdetails = $this->app()->usersmodel->getdetails();
          // $userlist =  $this->app()->category->getuserlist();

            $data['values'] = "Sanjay";
            $this->app()->render("register")->with(array('userdetails' => $userdetails,
                                                                                             'userlist' => $userlist));
        }

        public function action_userlist()
        {
            echo "This is userlist";
        }

        public function action_testing()
        {
            echo "I am in " . __METHOD__;
        }
    }