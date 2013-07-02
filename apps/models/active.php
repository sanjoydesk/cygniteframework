<?php
class ActiveAppsModels extends CF_BaseModel
{
    function __construct()
    { 
       parent::__construct('umrc');
    }
    
    function gettags()
    {
        
    }
    
    function insert()
    {
        $this->tips_title = "Hi Sanjay";
        $this->tips_link = "www.yahoo.com";
        $this->tips_description = 'Update Query';
        $this->status = '1';
        $this->delete_tips = '0';
        $this->save('vod_tips');
    }
    
    function update()
    {
        $this->tips_title = "Hi Sanjay";
        $this->tips_link = "www.yahoo.com";
        $this->tips_description = 'Update Query';
        $this->status = '1';
        $this->delete_tips = '0';
        $this->save('vod_tips',array('tips_id'=> 62));
    }
        
}