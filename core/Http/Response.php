<?php

namespace SmyPhp\Core\Http;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Http;
*/ 
class Response{
    
    public function setStatusCode(int $code){
        http_response_code($code);
    }

    public function redirect(string $url){
        header('Location:'.$url);
    }
}