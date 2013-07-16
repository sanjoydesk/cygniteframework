<?php 
interface CF_IActiveRecords 
{  
    public function where($filedname,$where="",$type=NULL);
    
    public function limit($limit,$offset="");
    
    public function group_by($column);
    
    public function order_by();
    
    public function fetch_all($tblname,$fetchmode="");
    
    public function fetch_row($sql);
    
    public function prepare_query($sql);
    
    public function flushresult();
    
    public function debug_query();
    
    //public function remove($tblname,$whrarr = array());
    
    public function close();        
}