<?php 
function _import($file){
    if (!file_exists($file)) {
        throw new \Exception("SmyPhp: Could not Import File '$file' to your project");
    }
    return require $file;
}