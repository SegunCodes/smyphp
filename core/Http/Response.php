<?php

namespace SmyPhp\Core\Http;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Http;
*/ 
class Response{
    
    public function setStatus(int $code){
        http_response_code($code);
    }

    public function redirect(string $url){
        header('Location:'.$url);
    }

    public function json($data, $status){
        $response = array();
        $response['status_code'] = http_response_code($status);
        $response['data'] = $data;
        header('Content-Type: application/json');
        $jsonData = json_encode($data);
        return $jsonData;
    }
}