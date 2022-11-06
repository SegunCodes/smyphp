<?php

namespace SmyPhp\Core\Form;
use SmyPhp\Core\Model;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Form
*/ 
class Form
{

    public static function start($action, $method){
        echo sprintf('<form action="%s" method="%s" enctype="multipart/form-data">', $action, $method);
        return new Form();
    }

    public static function stop(){
        echo '</form>';
    }
    
    public function input(Model $model, $attribute){
        return new InputField($model, $attribute);
    }
}