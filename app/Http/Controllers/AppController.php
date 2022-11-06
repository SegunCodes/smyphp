<?php

namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Http\Request;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package App\Controllers
*/ 
class AppController extends Controller{

    public function home(){
        $params = [
            'name' => 'DTC'
        ];
        return $this->render('home', $params);
    }
    
    public function contact(){
        return $this->render('contact');
    }

    public function handleContact(Request $request){
        $body = $request->getBody();
        return 'Loading';
    }
}