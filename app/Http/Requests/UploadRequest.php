<?php

namespace App\Http\Requests;

use SmyPhp\Core\Model;
use App\Providers\File;

class UploadRequest extends Model
{
    public string $file = "";

    public function rules(): array{
        return [
            
        ];
    }

    public function labels(): array
    {
        return [
            'file' => 'Upload File'
        ];
    }

    public function attributeName(): string 
    {
        return 'id';
    }

    public function upload(){
        $dom = new DOMDocument();
        $name = $dom->getAttribute($this->file);
        $test = (new File)->uploadImage($name, 'newFolder');
    }

}