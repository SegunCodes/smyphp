<?php

namespace App\Providers;
use SmyPhp\Core\Application;

class File{

    //function to upload image file
    public function uploadImage($inputName, $directory){
        if(isset($_FILES[$inputName]) && $_FILES[$inputName]["error"] == 0){
            $allowed = array(
                "jpg" => "image/jpg", 
                "jpeg" => "image/jpeg", 
                "gif" => "image/gif", 
                "png" => "image/png"
            );
            $filename = time().'_'.$_FILES[$inputName]["name"];
            $filetype = $_FILES[$inputName]["type"];
            $filesize = $_FILES[$inputName]["size"];
            $path = Application::$ROOT_DIR."/storage/$directory";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            // var_dump($filename);
            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!array_key_exists($ext, $allowed)) throw new \Exception("Select a valid file format");;
        
            // Verify file size
            $maxsize = 4 * 1024 * 1024;
            if($filesize > $maxsize) throw new \Exception("Image size is larger than the allowed limit - 4MB");;

            // Verify MYME type of the file
            if(in_array($filetype, $allowed)){
                //check if directory name is given
                if($directory = null){
                    throw new \Exception("Directory name not given");
                }
                // Check whether file exists before uploading it
                if(file_exists($path."/".$filename)){
                    throw new \Exception("File already exists");
                } else{
                    move_uploaded_file($_FILES[$inputName]["tmp_name"], $path."/".$filename);
                    return true;
                } 
            } else{
                throw new \Exception("There was a problem uploading the file. Please try again."); 
            }
        } else{
            // throw new \Exception($_FILES[$inputName]["error"]);
            return $_FILES[$inputName]["error"];
        }
    }

    //function to upload media file
    public function uploadMedia($inputName, $directory){
        if(isset($_FILES[$inputName]) && $_FILES[$inputName]["error"] == 0){
            $filename = time().'_'.$_FILES[$inputName]["name"];
            $filesize = $_FILES[$inputName]["size"];
            $path = Application::$ROOT_DIR."/storage/$directory";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        
            // verify file size
            $maxsize = 100 * 1024 * 1024;
            if($filesize > $maxsize) throw new \Exception("Media file size is larger than the allowed limit - 100MB");;

            //check if directory name is given
            if($directory = null){
                throw new \Exception("Directory name not given");
            }
            // Check whether file exists before uploading it
            if(file_exists($path."/".$filename)){
                throw new \Exception("File already exists");
            } else{
                move_uploaded_file($_FILES[$inputName]["tmp_name"], $path."/".$filename);
                return true;
            } 
        } else{
            throw new \Exception($_FILES[$inputName]["error"]);
        }
    }

    //function to upload multiple image file
}
