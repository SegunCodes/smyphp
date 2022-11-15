<?php

namespace App\Http\Middleware;

use SmyPhp\Core\Application;
use SmyPhp\Core\Middleware\BaseMiddleware;
use SmyPhp\Core\Exception\Forbidden;

class Authenticate extends BaseMiddleware
{
    
    public array $actions = [];
    /**
     * AuthMiddleware constructor
     * 
     * @param array $actions
     */

    public function __construct(array $actions = []){
        $this->actions = $actions;
    }
    
    public function execute(){
        if(Application::$app->isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                throw new Forbidden();
            }
        }
    }
 
}