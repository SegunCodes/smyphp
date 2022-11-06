<?php

namespace App\Http\Controllers;

use SmyPhp\Core\Controller\Controller;
use App\Models\User;
use App\Models\LoginUser;
use App\Http\Requests\UploadRequest;
use SmyPhp\Core\Http\Request;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\Application;
use App\Http\Middleware\Authenticate;
use App\Providers\MailService;


class AuthController extends Controller{

    public function __construct(){
        $this->authenticatedMiddleware(new Authenticate(['profile']));
    }

    public function login(Request $request, Response $response){
        $loginUser = new LoginUser();
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
        $errors = [];
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

    public function profile(){
        return $this->render('profile');
    }

    public function sendMail(){
        $subject = "test";
        $email = "shegstix64@gmail.com";
        $name = "olusegun";
        $email_template = Application::$ROOT_DIR."/views/email.php";
        $send = (new MailService)->Mail($subject, $email, $name, $email_template);
    }

    public function testFileUpload(Request $request){
        $this->setLayout('auth');
        $upload = new UploadRequest();
        if($request->isPost()){
            $file = $upload->loadData($request->getBody());
            if($upload->upload()){
                return $this->render('test' , [
                    'model' =>$upload
                ]);
            }
        }
        return $this->render('test', [
            'model' =>$upload
        ]);
    }
}