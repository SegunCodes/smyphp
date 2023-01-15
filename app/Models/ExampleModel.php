<?php

namespace App\Models;

use SmyPhp\Core\DatabaseModel;

class Post extends DatabaseModel
{

    public function tableName(): string
    {
        return '';
    }

    public function uniqueKey(): string 
    {
        return '';
    }

    public function rules(): array{
        return [];
    }

    public function attributes(): array{
        return []; 
    }

    public function labels(): array
    {
        return [];
    }

    public function getDisplayName(): string
    {
        return ''; 
    }
}