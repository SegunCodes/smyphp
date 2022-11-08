<?php

namespace App\Providers;
use SmyPhp\Core\Application;


class Upload
{
	public $file;	
	public $file_name;
	public $file_ext;	
	public $file_type;
	public $file_tmp;
	public $form_name;
	public $allowed_ext;
	public $size_limit; // in megaBytes
	public $DB;
	public $data = [];
	public $prefix; // custom prefix for upload name

	public $base64_init_val;
	public $base64_value;
	public  $base64_ext;
	
	function __construct($type, $name, $size_limit, $base64_init_val=null)
	{
		$this->file_type = $type;
		$this->form_name = $name;
		$this->size_limit = $size_limit;
		$this->prefix = "external_"; // set prefix to add to new file name
		
		if ($type == 'image' && $base64_init_val == null) {
			$this->allowed_ext = array('jpg', 'png', 'gif', 'jpeg');
			if(isset($_FILES[$this->form_name])) {
				$this->file = $_FILES[$this->form_name];
				$this->file_name = $this->file['name'];
				$this->file_tmp = $this->file['tmp_name'];
				$filter_file_ext = explode('.', $this->file_name);  // filtering file extension
				$this->file_ext = strtolower(end($filter_file_ext));
				if($this->file_type == 'image') {
		
				} 			
			}
		}



		if ($type == 'image'  && $base64_init_val != null) {

			$this->allowed_ext = array('jpg', 'png', 'gif', 'jpeg', 'webp');

			$this->base64_init_val = $base64_init_val;

			$base64parts = explode(";base64,", $this->base64_init_val);

            if (!isset($base64parts[1])) {
                throw new \Exception("Invalid base64 image string");
            }

			$base64_image_aux = $base64parts[0];

			$this->base64_ext = strtolower(explode("/", $base64_image_aux)[1]);

			$this->base64_value = $base64parts[1];
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
		if ($this->file_type == 'image' && $this->base64_init_val == null) {
			return (in_array($this->file_ext, $this->allowed_ext) ) ? true : false;
		} else if ($this->file_type == 'image' && $this->base64_init_val != null) {
			$allowed_mime = array('image/jpeg', 'image/gif', 'image/png', 'image/webp' );
			$mime = $this->getBase64ImageMIME($this->base64_value);
			return (in_array($this->base64_ext, $this->allowed_ext) && in_array($mime, $allowed_mime)) ? true : false;
		}
	}

	public function sizeIsLarge() {
		$byte_constant = 1048567;	// size in Byte	
		$size_in_MB = $this->size_limit;			// size in Megabyte
		$allowed_file_size = $size_in_MB * $byte_constant; // allowed file size	
			
		if ($this->file_type == 'image' && $this->base64_init_val == null) {
			return ($this->file["size"] > $allowed_file_size) ? true : false;
		} else if ($this->file_type == 'image' && $this->base64_init_val != null) {
			return ($this->getBase64ImageSize($this->base64_value) > $allowed_file_size ) ? true :false;
		}

	} 

	public function hasError() {
		if ($this->file_type == 'image' && $this->base64_init_val == null) {
			return($this->file["error"] == 0) ? false : true ;
		} else if ($this->file_type == 'image' && $this->base64_init_val != null) {
			return (!base64_decode($this->base64_value, true)) ? true : false;
		}	
	}	

	public function isEmpty() { 
		if ($this->file_type == 'image' && $this->base64_init_val == null) {
			return (strlen($this->file_tmp) == 0) ? true : false;
		} else if ($this->file_type == 'image' && $this->base64_init_val != null) {
			return (strlen($this->base64_init_val) == 0) ? true : false;
		}
	}

	public function pushImageTo($directory) {
		if($this->isImage() && !$this->hasError() && !$this->sizeIsLarge()) {
			$new_file_name = $this->prefix.uniqid('', true).'.'.$this->file_ext;
			$destination = $directory."/".$new_file_name;
			copy($this->file_tmp, $destination);
			if(move_uploaded_file($this->file_tmp, $destination)) {
				$size_range=filesize($destination)/1048567; // converting bytes to MB

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


	public function getBase64ImageSize($b64)
	{
		$image = base64_decode($b64);
		$tmp_path = Application::$ROOT_DIR."/temp/".time()."_".uniqid().".jpg";
		file_put_contents($tmp_path, $image);
		$image_size = filesize($tmp_path);
		unlink($tmp_path);
		return $image_size;
	}




	public function getBase64ImageMIME($b64)
	{
		$image = base64_decode($b64);
		$tmp_path = Application::$ROOT_DIR."/temp/".time()."_".uniqid().".jpg";
		file_put_contents($tmp_path, $image);
		$mime =  mime_content_type($tmp_path);
		unlink($tmp_path);
		return $mime;
	}



	public function pushBase64ImageTo($directory)
	{
		if($this->isImage() && !$this->hasError() && !$this->sizeIsLarge()) {
			$new_file_name = $this->prefix.uniqid('', true).'.'.$this->base64_ext;
			$destination = $directory."/".$new_file_name;

			$image = base64_decode($this->base64_value);
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