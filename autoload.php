<?php 
spl_autoload_register('loadCore');
spl_autoload_register('loadApp');
spl_autoload_register('loadTests');

function loadCore($class){
    $parts = explode('\\', $class);

   	if ($parts[0] === "SmyPhp") {
   		unset($parts[0]);
   		$file =  __DIR__."/core/".implode("/", $parts).'.php';
        require $file;
   	}
}

function loadApp($class){
    $parts = explode('\\', $class);
    if ($parts[0] === "App") {
       unset($parts[0]);
       $file =  __DIR__."/app/".implode("/", $parts).'.php';
      require $file;
    }
}

function loadTests($class){
    $parts = explode('\\', $class);
    if ($parts[0] === "Tests") {
       unset($parts[0]);
       $file =  __DIR__."/tests/".implode("/", $parts).'.php';
      require $file;
    }
}