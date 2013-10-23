<?php
namespace Cygnite;

if (!defined('CF_SYSTEM')) {
    exit('External script access not allowed');
}
/**
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
 * @Package               :  Packages
 * @Sub Packages          :  Base
 * @Filename              :  CFView
 * @Description           :  This file is used to map all routing of the cygnite framework
 * @Author                :  Sanjoy Dey
 * @Copyright             :  Copyright (c) 2013 - 2014,
 * @Link	              :  http://www.cygniteframework.com
 * @Since	              :  Version 1.0
 * @Filesource
 * @Warning               :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class CFView
{
    private $layout = array();

    private $view_path;

    private $template;

    private $results =array();

    public $requestedController;

    public $model = null;

    private $views;

    private static $name = array();

    private static $uiContent;

    private static $content;

    public $data =array();

    public function __construct()
    {
        $this->views = getcwd().DS.APPPATH.DS.'views'.DS;
        //ob_start();
        $expires = 60*60*24*14;
        //header("Pragma: public");
        //header("Cache-Control: maxage=".$expires);
        //header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
    }

    /**
    * Magic Method for handling dynamic data access.
    */
    public function __get($key)
    {
        return $this->data[$key];
    }

    /**
    * Magic Method for handling the dynamic setting of data.
    */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Magic Method for handling errors.
     *
     */
    public function __call($method, $arguments)
    {
        throw new \Exception("Undefined method called by ".get_class($this).' Controller');
    }

    /*
    * This function is to load requested view file
    * @false string (view name)
    *
    */
    public function render($view, $ui_content = null)
    {
        $trace = debug_backtrace();

        //$controller = strtolower(str_replace('AppsController','',$trace[1]['class']));
        $controller = str_replace(
            strtolower(APPPATH).'\\controllers\\',
            '',
            strtolower($trace[1]['class'])
        );

        $path= APPPATH.DS.'views'.DS.$controller.DS;

        if (!file_exists($path.$view.'.view'.EXT) &&
            !is_readable($path.$view.'.view'.EXT)
        ) {
            throw new \Exception('The Path '.$path.$view.'.view'.EXT.' is invalid.');
        }

        self::$name[strtolower($view)] = $view;

        if (is_readable($path.self::$name[$view].'.view'.EXT)) {

            if ($ui_content == true) {
                self::$uiContent =$ui_content;
                $this->view_path = $path.self::$name[$view].'.view'.EXT;
                $this->loadView();
                return self::$content;
            }

            $this->view_path = $path.self::$name[$view].'.view'.EXT;

            return $this;
        }

    }

    protected function createSections(array $sections)
    {
        $this->assignToProperties($sections);
    }

    private function assignToProperties($resultArray)
    {
        try {
            $path = "";
            foreach ($resultArray as $key => $value) {
                $path = str_replace(':', DS, $value);
                $this->{$key} = $path;
                $this->layout[$key] = $path;
            }
        } catch (\InvalidArgumentException $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function setLayout($layout, array $results)
    {
        $trace = debug_backtrace();

        $this->requestedController = strtolower(
            str_replace(
                'Apps\\Controllers\\',
                '',
                $trace[1]['class']
            )
        );

        $this->assignToProperties($results);
        $this->layoutParams = $results;

        if (is_readable(
            $this->views.rtrim(
                str_replace(
                    array(
                        '.',
                        '/',
                        ':'
                    ),
                    DS,
                    $layout
                ).'.layout'
            ).EXT
        )
        ) {


            $this->view_path =
                $this->views.rtrim(
                    str_replace(
                        array(
                            '.',
                            '/',
                            ':'
                        ),
                        DS,
                        $layout
                    ).'.layout'
                ).EXT;
        }

        $this->loadView();

    }

    public function renderLayout($page)
    {
        //$this->requestedController.
        $path = null;

        if (is_string($page) && strstr($page, '@')) {
            $page = str_replace('@', '', $page);
            $page = $this->{$page};
            $path= str_replace(array('//', '\\\\'), array('/', '\\'), $this->views.DS.$page).EXT;
        } else {
            $path= str_replace(
                array(
                    '//',
                    '\\\\'
                ),
                array(
                    '/',
                    '\\'
                ),
                $this->views.DS.$page
            ).EXT;
        }

        if (is_readable($path)) {
            include_once $path;
        } else {
            throw new \Exception('The Path '.$path.' is invalid.');
        }

        $this->bufferOutput();
    }

    public function with($arrayResult)
    {
        if (is_array($arrayResult)) {
            $this->results = (array) $arrayResult;
        }

        $this->loadView();
        
    }

    private function loadView()
    {
        try {
            include $this->view_path;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            throw new \Exception('The Path '.$this->view_path.' is invalid.');
        }

        $this->bufferOutput();
    }

    private function bufferOutput()
    {
        ob_start();
        $output = ob_get_contents();
        ob_get_clean();

        //$this->gzippedOutput();

        if (isset(self::$uiContent) && self::$uiContent == true) {
            self::$content =  $output;
        } else {
            echo $output;
        }
        //ob_end_flush();
    }

    public function gzippedOutput()
    {
        $HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
        ob_start();
        if (headers_sent()) {
            $encoding = false;
        } elseif (strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false) {
            $encoding = 'x-gzip';
        } elseif (strpos($HTTP_ACCEPT_ENCODING, 'gzip') !== false) {
            $encoding = 'gzip';
        } else {
            $encoding = false;
        }
        //var_dump($encoding);

        if ($encoding) {
              $contents = ob_get_clean();
              $_temp = strlen($contents);
            if ($_temp < 2048) {   // no need to waste resources in compressing very little data
                print($contents);
            } else {
                header('Content-Encoding: '.$encoding);
                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                $contents = gzcompress($contents, 9);
                $contents = substr($contents, 0, $_temp);
                print($contents);
            }
        } else {
            ob_end_flush();
        }
    }

    public function __destruct()
    {
        //ob_end_flush(); //ob_end_clean();
        //ob_get_flush();
        unset($this->results);
    }
}
