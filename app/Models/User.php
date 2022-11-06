<?php

namespace App\Models;

use SmyPhp\Core\DatabaseModel;

class User extends DatabaseModel
{
    //variable should match input attribute name 
    public string $name = "";
    public string $email = "";
    public string $password = "";
    public string $cpassword = "";

    public function tableName(): string
    {
        return 'users';
    }

    public function uniqueKey(): string 
    {
        return 'id';
    }

    public function save(){
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function rules(): array{
        return [
            'name' => ['required'],
            'email' => ['required', 'email', ['unique', 'table' => self::class]],
            'password' => ['required', ['min', 'min' => 8]],
            'cpassword' => ['required', ['match', 'match' => 'password']],
        ];
    }

    public function attributes(): array{
        return ['name', 'email', 'password']; //return the fields that should be saved in the database
    }

    public function labels(): array
    {
        return [
            'name' => 'Your Name',
            'email' => 'Your Email',
            'password' => 'Your Password',
            'cpassword' => 'Confirm Password'
        ];
    }

    public function getDisplayName(): string
    {
        return $this->name; 
    }
}