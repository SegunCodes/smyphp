<?php 
spl_autoload_register('__autoloadCore');
spl_autoload_register('__autoloadApp');
spl_autoload_register('__autoloadTests');

// Autoload SmyPhp Core

function __autoloadCore($class){
    $parts = explode('\\', $class);

   	if ($parts[0] === "SmyPhp") {
   		unset($parts[0]);
   		$file =  __DIR__."/core/".implode("/", $parts).'.php';
        require $file;
   	}
}

// Autoload SmyPhp App

function __autoloadApp($class){
    $parts = explode('\\', $class);
    if ($parts[0] === "App") {
       unset($parts[0]);
       $file =  __DIR__."/app/".implode("/", $parts).'.php';
      require $file;
    }
}

// Autoload Tests

function __autoloadTests($class){
    $parts = explode('\\', $class);
    if ($parts[0] === "Tests") {
       unset($parts[0]);
       $file =  __DIR__."/tests/".implode("/", $parts).'.php';
      require $file;
    }
}