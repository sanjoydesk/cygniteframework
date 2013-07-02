<?php 
class CF_ActiveRecordsModel 
{
    public $arr = array(); 
    private $db;
    private $connect;


    public function __construct($dbkey)
    {       
        $this->connect = $dbkey;
        $this->db = new CF_GenecricQueryBuilder($this->connect);
    }        
        
    
    public function __set($key,$value)
    {   
         $this->arr[$key] = $value;
    }      
    
    public function __get($key)
    {
        return $this->arr[$key];
    }   
    
     public function save($tbl,$key =array())
    {            
        if(empty($key))
           return $this->db->insert($tbl,$this->arr);
        else
           return $this->db->update($tbl,$this->arr,$key);
           
    } 
    
    public function fetch()
    {  
        return $this->db->select();
        
    }  
    
    
    public function __destruct()
    {
      unset($this->arr[$key]);
    }               
}
/*
class AUser extends Test
{
    
    public function __construct()
    {
        parent::__construct('umrc');
    }        
    
    public function testing()
    {   
        $this->tips_title = "Hi Sanjay";
        $this->tips_link = "www.yahoo.com";
        $this->tips_description = 'Update Query';
        $this->status = '1';
        $this->delete_tips = '0';
        $this->save('vod_tips',array('tips_id'=> 62));
        return TRUE;
        //$this->save('vod_tips');
    } 
    
    public function get()
    {          
        return $this->fetch();
    }        
    
    
}

$obj = new AUser();
echo $obj->testing();
echo count($obj->get());
echo "<pre>";
//var_dump($obj->get()); 
echo "</pre>";


class BUser extends Test
{
    
    public function __construct()
    {
        parent::__construct('petro_dir');
    }        
    
    public function testing()
    {  
        $this->IsActive = 'FALSE';
        $this->Memo = "Active records Test";
        $this->Code = '98643';
        $this->save('advert1');        
    }        
    
    public function getcategories()
    {
        return $this->fetchCateg();
    }        
    
}
//ob_start();
$obj1 = new BUser();
echo count($obj1->getcategories());
$obj1->testing();
echo "<pre>";
var_dump($obj1->getcategories());
echo "</pre>";
*/