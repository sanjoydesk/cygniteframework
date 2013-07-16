<?php
namespace Cygnite;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of closure
 *
 * @author SANJOY
 */
$arr = array(41,62,83,49,88,92);
$arr_parms = array_filter( $arr,
                                                        function($r) {
                                                                return $r > 70;
                                                        }
                                            );
var_dump($arr_parms);


$callback =  function($lowest) {
                            return function ($r) use ($lowest) { var_dump($r);
                                        return $r >= $lowest;
                            };
               };

$arr_parms = array_filter( $arr, $callback(70));
var_dump($arr_parms);

function say($value,$callback)
{
    echo$callback($value);
}


say('Matthew',function($name){
    return"Hello,$name";
});


class closure {
    //put your code here
}

?>
