<?php if (!defined('CF_BASEPATH')) exit('No direct script access allowed');
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
            $data['userdetails'] = $this->app()->usersmodel->getdetails();
           $data['userlist'] =  $this->app()->category->getuserlist();

            $data['values'] = "Sanjay";
            $this->app()->render("register", $data);
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