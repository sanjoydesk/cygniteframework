<?php
   class Userslist extends CF_BaseModel
   {

        public  function __construct()
        {
           parent::__construct();
        }

        public function getusers()
        {
            echo "I am in model";
            $this->cygnite->settype('retrieve');
            return $this->cygnite->fetch();
        }

        public function insert()
        {
            $this->tips_title = "This is Sanjay";
            $this->tips_link = "www.google.com";
            $this->tips_description = 'Update Query goes here';
            $this->status = '1';
            $this->delete_tips = '0';
            //$this->save('vod_tips',array('tips_id'=> 5)); // Update Query
            $this->type('manapulate');
            $this->save('vod_tips');
        }

    }
