<?php

namespace App\Http\Middleware;

use SmyPhp\Core\Application;
use SmyPhp\Core\Middleware\BaseMiddleware;
use SmyPhp\Core\Authorization\Server;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\DatabaseModel;
use App\Providers\Token;

class ApiMiddleware extends BaseMiddleware
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
        if(in_array(Application::$app->controller->action, $this->actions)){
            try {
                $response = new Response();
                if(Server::getBearerToken()) {
                    $authToken = Token::decode(Server::getBearerToken());
                    if(time() > $authToken['expires_at']){
                        throw new \JsonException($response->json([
                            "message" => "authentication token expired", 
                            "success" => false
                        ], 401));
                    }
                    $stmt = DatabaseModel::prepare("SELECT * FROM users WHERE  id = :id LIMIT 1");
                    $stmt->bindParam(':id', $authToken['id']);
                    $stmt->execute();
                    $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    if(count($user) > 0)   $authenticatedUser = $user[0]; 
                }else{
                    throw new \JsonException($response->json([
                        "message" => "authentication required", 
                        "success" => false
                    ], 401));
                }
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

}