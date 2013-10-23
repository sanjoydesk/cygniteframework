<?php
namespace Cygnite\Base;

use Cygnite\Helpers\GHelper;
use Cygnite\Helpers\Config;
use Cygnite\Exceptions;

class Dispatcher
{
    /**
    * The name of the entry page
    * @var string
    */
    private static $indexPage = 'index.php';

    private $router;
    /**
    * Define the router variable. default value set as false
    * @var bool
    */
    private static $router_enabled = false;

    private $default = array();

    private $routes = array();



    public function __construct($route)
    {
        $this->router = $route;
        $this->default['controller'] = lcfirst(
            Config::getConfig('global_config', 'default_controller')
        );
        $this->default['action'] = lcfirst(
            Config::getConfig('global_config', 'default_method')
        );

        $this->handle();
    }

    public function matches($routes, $quitAfterRun = false)
    {
        $uri = $this->router->getCurrentUri();
        //Counter to keep track of the number of routes we've handled
        $numHandled = 0;
        foreach ($routes as $route => $val) {

            // we have a match!
            if (preg_match_all('#^' . $route . '$#', $uri, $matches, PREG_SET_ORDER)) {

                    //Extract the matched URL false seters (and only the false seters)
                    $params = array_map(
                        function ($match) {
                            $var = explode('/', trim($match, '/'));
                            return isset($var[0]) ? $var[0] : null;
                        },
                        array_slice(
                            $matches[0],
                            1
                        )
                    );

                    // call the handling function with the URL falseeters
                    //call_user_func_array($route['fn'], $params);
                    $routerArray['controllerPath'] = $val;
                    $routerArray['params'] = $params;
                    // yay!
                    $numHandled++;

                    // If we need to quit, then quit
                if ($quitAfterRun) {
                    break;
                }

                return $routerArray;
            }

        }
    }

    public function handle()
    {
        if ($this->router->getCurrentUri() == '/' ||
            $this->router->getCurrentUri() == '/'.self::$indexPage
        ) {

            if ($this->default['controller'] != '') {

                $defaultController = $defaultAction = null;
                include APPPATH.DS.'controllers'.DS.$this->default['controller'].EXT;
                $defaultController = '\\'.ucfirst(APPPATH).'\\Controllers\\'.ucfirst($this->default['controller']);
                $defaultAction = $this->default['action'].'Action';
                $callArray = array(
                                  'controller' => $defaultController,
                                  'action' => $defaultAction,
                                  'params' => array()
                );

                //Static route: / (Default Home Page)
                $this->router->get(
                    '/',
                    function () use ($callArray) {
                        $homePageObject = new $callArray['controller'];
                        return $homePageObject->$callArray['action']();
                    }
                );

                /* call_user_func_array(
                    array(
                        new $defaultController,
                        $defaultAction
                    ),
                    $false_arr = array()
                );*/
            }
        } else {

            $routeConfig = Config::getConfig('routing_config');

            $newUrl = str_replace('/index.php', '', rtrim($this->router->getCurrentUri()));

            $exp= array_filter(explode('/', $newUrl));
            $matchedUrl = $this->matches($routeConfig);

            // Custom 404 Handler
            /*     \Cygnite\Cygnite::loader()->router->set404(function() {
                    header('HTTP/1.1 404 Not Found');
                    echo '404, route not found!';
            }); */

            if (!is_null($matchedUrl)) {

                $requestUri = preg_split('/[\.\ ]/', $matchedUrl['controllerPath']);
                $controller = ucfirst(APPPATH).'\\Controllers\\'.ucfirst($requestUri[0]);
                $action = strtolower($requestUri[1]).'Action';
                include APPPATH.DS.'controllers'.DS.$requestUri[0].EXT;
                call_user_func_array(array(new $controller,$action), (array)$matchedUrl['params']);

            } else {
                $includePath = "";
                $controller = ucfirst(APPPATH).'\\Controllers\\'.ucfirst($exp[1]);
                $includePath = strtolower(APPPATH).DS.'controllers'.DS.strtolower($exp[1]).EXT;
                //$callee = debug_backtrace();

                if (is_readable($includePath)) {
                    include APPPATH.DS.'controllers'.DS.strtolower($exp[1]).EXT;
                } else {
                    throw new \Exception('Unhandled Exception (404 Page)');
                }

                $instance = null;
                $action = strtolower((!isset($exp[2])) ? $this->default['action'] : $exp[2]).'Action';
                $instance = new $controller();

                if (!method_exists($instance, $action)) {
                    throw new \Exception("Requested action $exp[2] not found !");
                }

                $params = array_slice($exp, 2);
                call_user_func_array(array($instance, $action), (array)$params);
            }
        }
        $this->router->run();
    }

    public function __destruct()
    {
        //$this->router->run();
    }

}//End of the class