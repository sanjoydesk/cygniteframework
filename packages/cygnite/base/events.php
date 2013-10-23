<?php
namespace Cygnite\Base;

use Closure;

class Events
{
    protected $events = array();

    public function attach($eventName, $callback)
    {

        if (!isset($this->events[$eventName])) {
            $this->events[$eventName] = array();
        }

        $this->events[$eventName][] = $callback;
    }


    public function trigger($eventName, $data = null)
    {
        foreach ($this->events[$eventName] as $callback) {
            // echo $eventName."<br>";


            if (is_object($callback) && ($callback instanceof Closure)) {
                $callback($eventName, $data);
            }

            if (strpos($callback, '@')) {
                $exp = explode('@', $callback);

                call_user_func_array(array(new $exp[0], $exp[1]), (array) $data);
            }

            if (strpos($callback, '::')) {
                $class = '';
                //show($callback);
                $expression = "";
                $expression = explode('::', $callback);
                //show($expression);
                $class = '\\'.str_replace('_', '\\', $expression[0]);
                call_user_func(array(new $class, $expression[1]));

            }

            if (is_string($callback) && strpos($callback, '@') == false) {

                call_user_func($callback, $data);
            }
        }
    }

    public function flush($event)
    {
        unset($this->events[$event]);
    }
}