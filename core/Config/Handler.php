<?php
namespace SmyPhp\Core\Config;
use SmyPhp\Core\Application;

/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core
*/ 

class Handler{

	public $globals;

	function __construct () {
		// require 'core/Helpers.php';
		$this->globals = _import(Application::$ROOT_DIR.'/config/globals.php');
	}
}