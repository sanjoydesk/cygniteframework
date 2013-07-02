<?php
   $obj = new ApcCache();
if ($obj->is_enable){ // if APC enabled
    if($obj->store('user',array('name'=>'Sanjoy','designation'=> 'Software Engineer'))){
       echo "<pre>";
       var_dump($obj->get_data('user'));
       echo "</pre>";
    } else{
        throw new Exception("Details not available in apc cache.");
    }
    //$obj->destroy('user');
} else
    echo 'Seems APC not installed, please install it to perform ';

?>
