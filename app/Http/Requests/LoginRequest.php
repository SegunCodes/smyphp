<?php

namespace App\Http\Requests;

use App\Models\User;
use SmyPhp\Core\Application;
use SmyPhp\Core\Model;

class LoginRequest extends Model
{
    public string $email = "";
    public string $password = "";

    public function rules(): array{
        return [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
    }

    public function login(){
        $user = (new User)->findOne(['email' => $this->email]);
        if(!$user){
            $this->throwError('email', 'User does not exist');
            return false;
        }
        if(!password_verify($this->password, $user->password)){
            $this->throwError('password', 'Incorrect password');
            return false;
        }
        return Application::$app->login($user);
    }
     
    public function labels(): array
    {
        return [
            'email' => 'Your Email',
            'password' => 'Enter Password'
        ];
    }
}