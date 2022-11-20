<?php

namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Http\Request;

class AppController extends Controller{

    public function home(){
        $params = [
            'name' => 'DTC'
        ];
        return $this->render('home', $params);
    }
}