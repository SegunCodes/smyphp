<?php

namespace App\Http\Controllers;

use SmyPhp\Core\Controller\Controller;
use App\Models\User;
use SmyPhp\Core\Http\Request;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\Application;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller{

    public function __construct(){
        $this->authenticatedMiddleware(new Authenticate(['']));
    }

    public function login(Request $request, Response $response){
        $loginUser = new LoginRequest();
        if($request->isPost()){
            $loginUser->loadData($request->getBody());
            if($loginUser->validate() && $loginUser->login()){
                $response->redirect('/home');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginUser
        ]);
    }
    
    public function register(Request $request){
        $this->setLayout('auth');
        $user = new User();
        if($request->isPost()){
            $user->loadData($request->getBody());

            if($user->validate() && $user->save()){
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/home');
                exit;
            }
            return $this->render('register', [
                'model' =>$user
            ]);
        }
        return $this->render('register', [
            'model' =>$user
        ]);
    }

    public function logout(Request $request, Response $response){
        Application::$app->logout();
        $response->redirect('/home');
    }

}