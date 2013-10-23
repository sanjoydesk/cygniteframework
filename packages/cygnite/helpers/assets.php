<?php
namespace Cygnite\Helpers;

//use Cygnite\Helpers\Url as Url;

if ( ! defined('CF_SYSTEM')) exit('External script access not allowed');
/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3  or newer
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
 * @Package                          :  Packages
 * @Sub Packages               :   Helper
 * @Filename                       :  Assets
 * @Description                  :  This helper is used to load all assets of html page. Not implemented in current version. May be available on next version.
 * @Author                           :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                              :  http://www.cygniteframework.com
 * @Since	                          :  Version 1.0
 * @Filesource
 * @Warning                      :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

    class Assets
    {
          /**
            * Generate a link to a stylesheet file.
            *
            * <code>
            *	     // Generate a link to a stylesheet file
            *	        echo Assets::addScript('css/cygnite.css');
            * </code>
            *
            * @false  string  $href
            * @false  array   $type
            * @return string
            */
          public static function addStyle($href,$media = '',$title = '')
          {
                 if(is_null($href))
                      throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);

                     $media = (is_null($media)) ? 'media=all' : $media;
                     $title = (!is_null($title)) ? "title='$title'"  : '';
                return '<link rel="stylesheet" type="text/css"  '.$media.' '.$title.' href="'.Url::getBase().$href.'" rel="stylesheet">'.PHP_EOL;
          }

            /**
            * Generate a link to a JavaScript file.
            *
            * <code>
            *  // Generate a link to a JavaScript file
            *    echo Assets::addScript('js/jquery.js');
            *
            * // Generate a link to a JavaScript file and add some attributes
            *   echo Assets::addScript('js/jquery.js', array('required'));
            * </code>
            *
            * @false  string  $url
            * @false  array   $attributes
            * @return string
            */
            public static function addScript($url, $attributes = array())
            {
                     return '<script type="text/javascript" src="'.Url::getBase().$url.'"'.self::addAttributes($attributes).'></script>'.PHP_EOL;
            }



          public static function ajax($url,$data,$method,$callback,$calltype = '')
          {
                $ajax_script = '';
                $ajax_script = '<script type="text/javascript">';
                $ajax_script = '
                                        $.ajax();
                                        ';

                $ajax_script = '</script>';
          }

        /**
        * Generate anchor link
        * @false  string     $url
        * @false  string     $name
        * @false  array   $attributes
        * @return string
        */
          public static function addLink($url,$name = null,$attributes = array())
          {
                   $name =  (is_null($name)) ? $url :  $name;
                  return '<a href="'.Url::getBase().$url.'" '.self::addAttributes($attributes).'>'.HTML::entities($name).'</a>'.PHP_EOL;
          }

        /**
        * Form HTML attributes from array.
        *
        * @false  array   $attributes
        * @return string
        */
        public static function addAttributes($attributes = array(),$html = array())
        {
                foreach ($attributes as $key => $value) {
                      if ( ! is_null($value))
                            $html[] = $key.'="'.HTML::entities($value).'"';
                }
                return (count($html) > 0) ? ' '.implode(' ', $html) : '';
        }

    }

       class HTML
    {
            /**
             * Convert html characters to entities.
             *
             * Encoding will be used based  on configuration given in Config file.
             *
             * @false  string  $value
             * @return string
             */
            public static function entities($value)
            {
                    return htmlentities($value, ENT_QUOTES,Config::getConfig('global_config','encoding'), false);
            }

            /**
             * Convert entities to html characters.
             *
             * @false  string  $value
             * @return string
             */
            public static function decode($value)
            {
                    return html_entity_decode($value, ENT_QUOTES,Config::getConfig('global_config','encoding'));
            }

            /**
            * Convert html special characters.
            *
            * Encoding will be used based  on configuration given in Config file.
            *
            * @false  string  $value
            * @return string
            */
            public static function specialCharacters($value)
            {
                 if(is_null($value))
                      throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);
                   return htmlspecialchars($value, ENT_QUOTES,Config::getConfig('global_config','encoding'), false);
            }

          /**
            * Private method to sanitize data
            * @false mixed $data
            */
            private function santize($value,$type='')
            {
                    switch($type):
                                    default:
                                                  return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
                                            break;
                                    case 'strong':
                                                 return htmlentities($value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                                            break;
                                    case 'strict':
                                                 return urlencode($value);
                                            break;
                    endswitch;
            }
    }