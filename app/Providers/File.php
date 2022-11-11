<?php

namespace App\Providers;
use SmyPhp\Core\Application;

class File{

    public function Upload($type, $name, $max_size, $base64InitValue = null){
        if ($type == 'image') {
			$allowedExtensions = array('jpg', 'png', 'gif', 'jpeg');
			if(isset($_FILES[$name]) && $base64InitValue == null) {
				$file = $_FILES[$name];
				$filename = $file['name'];
				$fileTemp = $file['tmp_name'];
				// Verify file extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if(!array_key_exists($ext, $allowedExtensions)) throw new \Exception("Select a valid file format");
				if($type == 'image') {
                    
				} 			
			}
		}


        if ($type == 'image'  && $base64InitValue != null) {

			$allowedExtensions = array('jpg', 'png', 'gif', 'jpeg', 'webp');

			$base64Parts = explode(";base64,", $this->base64InitValue);

            if (!isset($base64Parts[1])) {
                throw new \Exception("Invalid base64 image string");
            }

			$base64ImageAux = $base64Parts[0];

			$this->base64Extension = strtolower(explode("/", $base64ImageAux)[1]);

			$this->base64Value = $base64Parts[1];
		}
    }
}