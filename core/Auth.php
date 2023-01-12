<?php

namespace SmyPhp\Core;
use SmyPhp\Core\DatabaseModel;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\Authorization\Server;
use App\Providers\Token;

class Auth{

    public static function User(){
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
            return $authenticatedUser["id"];
        }
    }
}


?>