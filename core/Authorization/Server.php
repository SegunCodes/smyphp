<?php 

namespace SmyPhp\Core\Authorization;

class Server
{


    public static function getHeader(){
        $headers = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }
        else if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }


   public static function getBearerToken() {
        $headers = self::getHeader();

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    } 
    
    
    public static function convertPayload(){
        $payload = file_get_contents('php://input');
        $is_json = is_string($payload) && is_array(json_decode($payload, true)) ? true : false;
        if($is_json) {
          $_POST = json_decode($payload, true);
          return true;
        }
    }
	
}
?>