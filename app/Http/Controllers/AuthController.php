<?php

namespace App\Http\Controllers;

use SmyPhp\Core\Controller\Controller;
use App\Models\User;
use SmyPhp\Core\Http\Request;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\Application;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\LoginRequest;
use App\Providers\MailService;


class AuthController extends Controller{

    public function __construct(){
        $this->authenticatedMiddleware(new Authenticate(['profile']));
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
        if (isset($_POST['submit'])) {
            if(isset($_FILES['file']) && $_FILES['file']["error"] == 0){
                $allowed = array(
                    "jpg" => "image/jpg", 
                    "jpeg" => "image/jpeg", 
                    "gif" => "image/gif", 
                    "png" => "image/png"
                );
                $filename = time().'_'.$_FILES['file']["name"];
                $filetype = $_FILES['file']["type"];
                $filesize = $_FILES['file']["size"];
                $directory = "newFolder";
                $path = Application::$ROOT_DIR."/storage/$directory";
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                // Verify file extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if(!array_key_exists($ext, $allowed)) $msg = "Select a valid file format";
            
                // Verify file size
                $maxsize = 4 * 1024 * 1024;
                if($filesize > $maxsize) $msg = "Image size is larger than the allowed limit - 4MB";;
    
                // Verify MYME type of the file
                if(in_array($filetype, $allowed)){
                    //check if directory name is given
                    if($directory = null){
                        $msg = "Directory name not given";
                    }
                    // Check whether file exists before uploading it
                    if(file_exists($path."/".$filename)){
                        $msg = "File already exists";
                    } else{
                        move_uploaded_file($_FILES['file']["tmp_name"], $path."/".$filename);
                        $msg = "file";
                        return $this->render('test', [
                            'error' => $msg
                        ]);
                    } 
                } else{
                    echo "There was a problem uploading the file. Please try again."; 
                }
            } else{
                $msg = $_FILES['file']["error"];
                return $this->render('test', [
                    'error' => $msg
                ]);
            }
            $msg = "submit";
            return $this->render('test', [
                'error' => $msg
            ]);
        }
        $msg = "wahala";
        return $this->render('test', [
            'error' => $msg
        ]);
    }
}