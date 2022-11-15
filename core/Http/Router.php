<?php

namespace SmyPhp\Core\Http;
use SmyPhp\Core\Application;
/**
 * SmyPhps - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Http
*/ 
class Router{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    /**
    * router constructor
    * 
    * @param \App\Core\Request $request
    * @param \App\Core\Response $response
    */

    public function __construct(Request $request, Response $response){
        $this->request = new Request();
        $this->response = new Response();
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }

    public function getCallback(){
        $path = $this->request->getPath();
        $method = $this->request->method();
        //trim slashes
        $path = trim($path, '/');

        $routes = $this->routes[$method] ?? [];
        $params = false;

        foreach ($routes as $route => $callback) {
            //trim slashes
            $route = trim($route, '/');
            $routeNames = [];
            if(!$route){
                continue;
            }

            if(preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)){
                $routeNames = $matches[1];
            }
            // convert route name to regex pattern
            $routeRegex = "@^".preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route)."$@";
            //match current route
            if(preg_match_all($routeRegex, $path, $valueMatches)){
                $values = [];
                for ($i=1; $i < count($valueMatches); $i++) { 
                    $values[] = $valueMatches[$i][0];
                }
                $params = array_combine($routeNames, $values);
                $this->request->setRouteParameters($params);
                return $callback;
            }
        }
        return false;
    }

    public function resolve(){
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false){
            $callback = $this->getCallback();
            if($callback === false){
                $this->response->setStatus(404); 
                return "Cannot GET $path";
            }
        }
        if(is_string($callback)){
            return $this->getView($callback);
        }
        if(is_array($callback)){
            /**
             * @var \Smyphp\Core\Controller\Controller $controller 
             * */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            foreach($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    public function getView($view, $params = []){
        $layout = $this->viewLayout();
        $viewContent = $this->getOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layout);
    }

    public function renderContent($viewContent){
        $layoutContent = $this->viewLayout();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function viewLayout(){
        $layout = Application::$app->layout;
        if(Application::$app->controller){
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function getOnlyView($view, $params){
        foreach($params as $key => $value){
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    public function getAppViews($view, $params = []){
        $layout = $this->viewLayout();
        $viewContent = $this->getOnlyAppViews($view, $params);
        return str_replace('{{content}}', $viewContent, $layout);
    }

    protected function getOnlyAppViews($view, $params){
        foreach($params as $key => $value){
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/config/routes/$view.php";
        return ob_get_clean();
    }

}