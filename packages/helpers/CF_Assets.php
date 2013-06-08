<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :   Helper
 * @Filename                       :  Assets
 * @Description                   :  This helper is used to load all assets of html page. Not implemented in current version. May be available on next version.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */
    class HTML
    {
            /**
             * Convert html characters to entities.
             *
             * Encoding will be used based  on configuration given in Config file.
             *
             * @param  string  $value
             * @return string
             */
            public static function entities($value)
            {
                    return htmlentities($value, ENT_QUOTES, CF_Config::getconfig('global_config','encoding'), FALSE);
            }

            /**
             * Convert entities to html characters.
             *
             * @param  string  $value
             * @return string
             */
            public static function decode($value)
            {
                    return html_entity_decode($value, ENT_QUOTES, CF_Config::getconfig('global_config','encoding'));
            }

            /**
            * Convert html special characters.
            *
            * Encoding will be used based  on configuration given in Config file.
            *
            * @param  string  $value
            * @return string
            */
            public static function special_characters($value)
            {
                 if(is_null($value))
                      throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);
                   return htmlspecialchars($value, ENT_QUOTES, CF_Config::getconfig('global_config','encoding'), false);
            }
    }


    class Assets
    {
          /**
            * Generate a link to a stylesheet file.
            *
            * <code>
            *	     // Generate a link to a stylesheet file
            *	        echo Assets::addscript('js/jquery.js');
            * </code>
            *
            * @param  string  $href
            * @param  array   $type
            * @return string
            */
          public static function addstyle($href,$media = '',$title = '')
          {
                 if(is_null($href))
                      throw new InvalidArgumentException("Cannot pass null argument to ".__METHOD__);
                     $media = (is_null($media)) ? 'media="all"' : $media;
                     $title = (!is_null($title)) ? "title='".$title."'"  : '';
                return '<link rel="stylesheet" type="text/css"  "'.$media.'" "'.$title.'" href="'.GHelper::base_path().$href.'">'.PHP_EOL;
          }

            /**
            * Generate a link to a JavaScript file.
            *
            * <code>
            *	     // Generate a link to a JavaScript file
            *	        echo Assets::addscript('js/jquery.js');
            *
            *	   // Generate a link to a JavaScript file and add some attributes
            *	        echo Assets::addscript('js/jquery.js', array('required'));
            * </code>
            *
            * @param  string  $url
            * @param  array   $attributes
            * @return string
            */
            public static function addscript($url, $attributes = array())
            {
                     return '<script src="'.GHelper::base_path().$url.'"'.static::addattributes($attributes).'></script>'.PHP_EOL;
            }


          public static function ajax()
          {

          }

        /**
        * Generate anchor link
        * @param  string     $url
        * @param  string     $name
        * @param  array   $attributes
        * @return string
        */
          public static function addlink($url,$name = NULL,$attributes = array())
          {
                   $name =  (is_null($name)) ? $url :  $name;
                  return '<a href="'.GHelper::base_path().$url.'" '.static::addattributes($attributes).'>'.HTML::entities($name).'</a>'.PHP_EOL;
          }

        /**
        * Form HTML attributes from array.
        *
        * @param  array   $attributes
        * @return string
        */
        public static function addattributes($attributes = array(),$html = array())
        {
                foreach ($attributes as $key => $value):
                      if ( ! is_null($value))
                            $html[] = $key.'="'.HTML::entities($value).'"';
                endforeach;
                return (count($html) > 0) ? ' '.implode(' ', $html) : '';
        }

    }