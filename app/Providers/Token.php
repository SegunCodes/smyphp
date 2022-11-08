<?php

namespace App\Providers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use SmyPhp\Core\Application;

require_once Application::$ROOT_DIR."/vendor/autoload.php";

class Token{

    public static function encode($data) {
        return JWT::encode($data, $_ENV['JWT_KEY'], 'HS256');
    }

    public static function decode($jwt) {
        return (array) JWT::decode($jwt, new Key($_ENV['JWT_KEY'], 'HS256'));
    }

    public static function sign($data){
        return self::encode($data);
    }
	
}