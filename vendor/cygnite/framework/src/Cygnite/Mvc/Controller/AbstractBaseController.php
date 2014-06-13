<?php
namespace Cygnite\Mvc\Controller;

use Cygnite\Common\Encrypt;
use Cygnite\Common\SessionManager\Session;
use Cygnite\Common\SessionManager\Flash\FlashMessage;
use Cygnite\DependencyInjection\Container;
use Cygnite\Helpers\Inflector;
use Exception;
use Cygnite\Foundation\Application;
use Cygnite\Base\Event;
use Cygnite\Mvc\View\CView;
use Cygnite\Mvc\View\Template;

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
 * @Package                   :  Cygnite
 * @SubPackages               :  Mvc
 * @Filename                  :  AbstractBaseController
 * @Description               :  This is the base controller of your application.
 *                               Controllers extends all base functionality of BaseController class.
 * @Author                    :  Cygnite Dev Team
 * @Copyright                 :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @FileSource
 *
 */

abstract class AbstractBaseController extends CView
{
    public $app;

    private $validFlashMessage = array('setFlash', 'hasFlash', 'getFlash');

    /**
     * Constructor function
     *
     * @access    public
     * @return \Cygnite\Mvc\Controller\AbstractBaseController class object
     */
    public function __construct()
    {
        parent::__construct(new Template);
        $this->app = new Container();
    }

    //prevent clone.
    private function __clone()
    {

    }

    /**
     * Magic Method for handling errors.
     *
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->validFlashMessage)) {
            //$session = $this->get('cygnite.common.session-manager.flash.FlashMessage');
            
            $return = call_user_func_array(array(new FlashMessage(new Session(new Encrypt())), $method), $arguments);

            return ($method == 'setFlash') ? $this : $return;
        }

        throw new Exception("Undefined method [$method] called by ".get_class($this).' Controller');
    }

   /* protected function setFlashMessage($key, $value)
    {
        $this->session->addFlash($key, $value);

        return $this;
    }

    protected function hasFlashMessage($key)
    {
        return $this->session->hasFlash($key);
    }

    protected function getFlashMessage($key)
    {
        return $this->session->getFlash($key);
    }*/

    protected function redirectTo($uri = '', $type = 'location', $httpResponseCode = 302)
    {
        $url = $this->get('cygnite.common.url-manager.url');
        $url->redirectTo($uri, $type, $httpResponseCode);

        return $this;
    }

    /**
     * @param $key
     * @return object @instance instance of your class
     */
    protected function get($key)
    {
        $class = null;
        $class = explode('.', $key);
        $class = array_map('ucfirst', Inflector::instance()->classify($class));
        $class = implode('\\', $class);

        return $this->app->resolve('\\'.$class);
       // return $this->app->make('\\'.$class);
    }

    /**
    <code>
    * // Call the "index" method on the "user" controller
    *  $response = $this->call('admin::user@index');
    *
    * // Call the "user/admin" controller and pass parameters
    *   $response = $this->call('modules.admin.user@profile', $arguments);
    * </code>
    */
    public function call($resource, $arguments = array())
    {
        //$expression = explode('@', $resource);
        list($name, $method) = explode('@', $resource);

        $method = $method.'Action';
        $class = array_map('ucfirst', explode('.', $name));
        $className = Inflector::instance()->classify(end($class)).'Controller';
        $namespace = str_replace(end($class), '', $class);
        $class = '\\'.ucfirst(APPPATH).'\\'.implode('\\', $namespace).$className;

        return call_user_func_array(array(new $class, $method), $arguments);
    }
 }
