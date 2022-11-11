<?php

namespace App\Providers;
use SmyPhp\Core\Application;

class Files
{
    public $file;	
	public $fileName;
	public $fileExtension;	
	public $fileType;
	public $fileTmp;
	public $inputName;
	public $allowedExtensions;
	public $max_size;
	public $DB;
	public $data = [];
	public $prefix;

	public $base64InitValue;
	public $base64Value;
	public  $base64Extension;
	
	function __construct($type, $name, $max_size, $base64InitValue=null)
	{
		$this->fileType = $type;
		$this->inputName = $name;
		$this->max_size = $max_size;
		$this->prefix = time().'_smy'; // set prefix to add to new file name
		
		if ($type == 'image' && $base64InitValue == null) {
			$this->allowedExtensions = array('jpg', 'png', 'gif', 'jpeg');
			if(isset($_FILES[$this->inputName])) {
				$this->file = $_FILES[$this->inputName];
				$this->fileName = $this->file['name'];
				$this->fileTmp = $this->file['tmp_name'];
				$filterFileExtension = explode('.', $this->fileName);  // filtering file extension
				$this->fileExtension = strtolower(end($filterFileExtension));
				if($this->fileType == 'image') {
                    
				} 			
			}
		}



		if ($type == 'image'  && $base64InitValue != null) {

			$this->allowedExtensions = array('jpg', 'png', 'gif', 'jpeg', 'webp');

			$this->base64InitValue = $base64InitValue;

			$base64Parts = explode(";base64,", $this->base64InitValue);

            if (!isset($base64Parts[1])) {
                throw new \Exception("Invalid base64 image string");
            }

			$base64ImageAux = $base64Parts[0];

			$this->base64Extension = strtolower(explode("/", $base64ImageAux)[1]);

			$this->base64Value = $base64Parts[1];
		}		
	}

	public function compress_img($source, $destination, $quality)
	{
		$info =	getimagesize($source);
		$image = null;
		if ($info['mime']=='image/jpeg') {
			$image=imagecreatefromjpeg($source);
		} elseif ($info['mime']=='image/gif'){
			$image=imagecreatefromgif($source);
		}elseif ($info['mime']=='image/png'){
			$image=imagecreatefrompng($source);
		}
		imagejpeg($image, $destination, $quality);
		return $destination;
		
	}

	public function isImage() {
		if ($this->fileType == 'image' && $this->base64InitValue == null) {
			return (in_array($this->fileExtension, $this->allowedExtensions) ) ? true : false;
		} else if ($this->fileType == 'image' && $this->base64InitValue != null) {
			$allowedMime = array('image/jpeg', 'image/gif', 'image/png', 'image/webp' );
			$mime = $this->getBase64ImageMIME($this->base64Value);
			return (in_array($this->base64Extension, $this->allowedExtensions) && in_array($mime, $allowedMime)) ? true : false;
		}
	}

	public function sizeIsLarge() {
		$byte_constant = 1048567;	// size in Byte	
		$size_in_MB = $this->max_size;			// size in Megabyte
		$allowed_file_size = $size_in_MB * $byte_constant; // allowed file size	
			
		if ($this->fileType == 'image' && $this->base64InitValue == null) {
			return ($this->file["size"] > $allowed_file_size) ? true : false;
		} else if ($this->fileType == 'image' && $this->base64InitValue != null) {
			return ($this->getBase64ImageSize($this->base64Value) > $allowed_file_size ) ? true :false;
		}

	} 

	public function hasError() {
		if ($this->fileType == 'image' && $this->base64InitValue == null) {
			return($this->file["error"] == 0) ? false : true ;
		} else if ($this->fileType == 'image' && $this->base64InitValue != null) {
			return (!base64_decode($this->base64Value, true)) ? true : false;
		}	
	}

	public function isEmpty() { 
		if ($this->fileType == 'image' && $this->base64InitValue == null) {
			return (strlen($this->fileTmp) == 0) ? true : false;
		} else if ($this->fileType == 'image' && $this->base64InitValue != null) {
			return (strlen($this->base64InitValue) == 0) ? true : false;
		}
	}

	public function pushImageTo($directory) {
		if($this->isImage() && !$this->hasError() && !$this->sizeIsLarge()) {
			$new_file_name = $this->prefix.uniqid('', true).'.'.$this->fileExtension;
			$destination = $directory."/".$new_file_name;
			copy($this->fileTmp, $destination);
			if(move_uploaded_file($this->fileTmp, $destination)) {
				$size_range = filesize($destination)/1048567; // converting bytes to MB

				/*compression algorithm*/
				if ($size_range>=2)	{
						$this->compress_img($destination, $destination, 23); 
					} else if ($size_range <2 && $size_range>=1.5) {
						$this->compress_img($destination, $destination, 27); 
					} else if ($size_range<1.5 && $size_range>=1){
						$this->compress_img($destination, $destination, 30); 
					} else if ($size_range<1 && $size_range>=0.5){
						$this->compress_img($destination, $destination, 35); 
					} else if ($size_range<0.5 && $size_range>=0.15){
						$this->compress_img($destination, $destination, 50); 
					}

					/*Pushing data to array*/	
					$this->data =  array(
						"new_file_name" => $new_file_name
					);
																							
			}
		}
	}

	public function getBase64ImageSize($b64){
		$image = base64_decode($b64);
		$tmp_path = Application::$ROOT_DIR."/temp/".time()."_".uniqid().".jpg";
		file_put_contents($tmp_path, $image);
		$image_size = filesize($tmp_path);
		unlink($tmp_path);
		return $image_size;
	}

	public function getBase64ImageMIME($b64){
		$image = base64_decode($b64);
		$tmp_path = Application::$ROOT_DIR."/temp/".time()."_".uniqid().".jpg";
		file_put_contents($tmp_path, $image);
		$mime =  mime_content_type($tmp_path);
		unlink($tmp_path);
		return $mime;
	}

	public function pushBase64ImageTo($directory){
		if($this->isImage() && !$this->hasError() && !$this->sizeIsLarge()) {
			$new_file_name = $this->prefix.uniqid('', true).'.'.$this->base64Extension;
			$destination = $directory."/".$new_file_name;

			$image = base64_decode($this->base64Value);
			$tmp_path = Application::$ROOT_DIR."/temp/".time()."_".uniqid().".jpg";
			file_put_contents($tmp_path, $image);

			copy($tmp_path, $destination);	
			$size_range = filesize($destination);



			/*compression algorithm*/
			if ($size_range>=2)	{
				$this->compress_img($destination, $destination, 23); 
			} else if ($size_range <2 && $size_range>=1.5) {
				$this->compress_img($destination, $destination, 27); 
			} else if ($size_range<1.5 && $size_range>=1){
				$this->compress_img($destination, $destination, 30); 
			} else if ($size_range<1 && $size_range>=0.5){
				$this->compress_img($destination, $destination, 35); 
			} else if ($size_range<0.5 && $size_range>=0.15){
				$this->compress_img($destination, $destination, 30); 
			}

			//Deleting image from Temporary path
			unlink($tmp_path);

			$info = getimagesize($destination);
			
			/*Pushing data to array*/	
			$this->data =  array(
				"new_file_name" => $new_file_name,
				"file_info" => $info
			);	


		}	
	}

	public function is_base64_string($str, $enc=array('UTF-8', 'ASCII')) {
		return !(($b = base64_decode($str, TRUE)) === FALSE) && in_array(mb_detect_encoding($b), $enc);
	}
}