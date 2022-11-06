<?php
namespace SmyPhp\Core\Controller;
use SmyPhp\Core\Application;
use SmyPhp\Core\Middleware\BaseMiddleware;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Controller;
*/ 
class Controller{
    
    public string $layout = 'main';
    public string $action = '';
    /**
     * @var \SmyPhp\Core\Middleware\BaseMiddleware[];
     */
    protected array $middlewares = [];
    
    public function setLayout($layout){
        $this->layout = $layout;
    }

    public function render($view, $params = []){
        return Application::$app->router->getView($view, $params);
    }

    public function authenticatedMiddleware(BaseMiddleware $middleware){
        $this->middlewares[] = $middleware;
    }

    /**
     * @return \Smyphp\Core\Middleware\BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}