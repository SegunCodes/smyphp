<?php

namespace SmyPhp\Core\Form;
use SmyPhp\Core\Model;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Form
*/ 
abstract class BaseField
{
    
    public Model $model;
    public string $attribute;
    /**
     * Field constructor
     * 
     * @param \SmyPhp\Core\Model $model
     * @param string $attribute
    */

    public function __construct(Model $model, string $attribute){
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput(): string;

    public function __toString(){
        return sprintf('
            <div class="form-group">
                <label>%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            <div>
        ', 
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute),
        );
    }
}