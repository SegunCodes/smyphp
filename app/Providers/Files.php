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

	public $base64_init_val;
	public $base64_value;
	public  $base64_ext;
	
	function __construct($type, $name, $max_size, $base64_init_val=null)
	{
		$this->fileType = $type;
		$this->inputName = $name;
		$this->max_size = $max_size;
		$this->prefix = time().'_smy'; // set prefix to add to new file name
		
		if ($type == 'image' && $base64_init_val == null) {
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



		if ($type == 'image'  && $base64_init_val != null) {

			$this->allowedExtensions = array('jpg', 'png', 'gif', 'jpeg', 'webp');

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
}