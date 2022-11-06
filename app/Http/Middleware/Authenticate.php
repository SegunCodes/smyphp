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

    // public function protectForUser() {
    //     return function ($req, $res, $pipe) {
    //         if (!$this->authUser) {
    //             $res->sendStatus(401);
    //             $res->json(["message" => "authentication required", "status" => false]);
    //             return $pipe->block();
    //         }

    //        if(time() > $this->auth_token['expires_at']){
    //             $res->sendStatus(401);
    //             $res->json(["message" => "authentication token expired", "status" => false]);
    //             return $pipe->block();
    //        }

    //     };
    // } 
}