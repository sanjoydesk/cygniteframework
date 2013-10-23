<?php
namespace Cygnite;

if (!defined('CF_SYSTEM')) {
    exit('External script access not allowed');
}
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       : Inflectors
 * @Description                   : This library will be available on next version
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class Inflectors
{
    /********************* Inflections ******************/


    public function deCamelize($word)
    {
        return preg_replace(
            '/(^|[a-z])([A-Z])/e',
            'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")',
            $word
        );
    }
    /*
     * Class name - ClassName
     */
    public function camelize($word)
    {
        return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
    }

    public function toCameCase($str, $capitaliseFirstChar = false)
    {
        if ($capitaliseFirstChar) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * camelCaseAction name -> dash-separated.
     *
     * @false  string
     * @param $s
     * @return string
     */
    private static function actionpath($s)
    {
        $s = preg_replace('#(.)(?=[A-Z])#', '$1-', $s);
        $s = strtolower($s);
        $s = rawurlencode($s);
        return $s;
    }


    /**
     * dash-separated -> camelCaseAction name.
     *
     * @false  string
     * @param $s
     * @return string
     */
    private static function pathaction($s)
    {
        $s = strtolower($s);
        $s = preg_replace('#-(?=[a-z])#', ' ', $s);
        $s = substr(ucwords('x' . $s), 1);
        //$s = lcfirst(ucwords($s));
        $s = str_replace(' ', '', $s);
        return $s;
    }


    /**
     * PascalCase: Presenter name -> dash-and-dot-separated.
     *
     * @false  string
     * @param $s
     * @return string
     */
    private static function controllerPath($s)
    {
        $s = strtr($s, ':', '.');
        $s = preg_replace('#([^.])(?=[A-Z])#', '$1-', $s);
        $s = strtolower($s);
        $s = rawurlencode($s);
        return $s;
    }


    /**
     * Dash-and-dot-separated -> PascalCase:Presenter name.
     *
     * @false  string
     * @param $s
     * @return string
     */
    private static function pathview($s)
    {
        $s = strtolower($s);
        $s = preg_replace('#([.-])(?=[a-z])#', '$1 ', $s);
        $s = ucwords($s);
        $s = str_replace('. ', ':', $s);
        $s = str_replace('- ', '', $s);
        return $s;
    }

}
