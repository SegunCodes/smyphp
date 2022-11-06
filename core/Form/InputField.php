<?php

namespace SmyPhp\Core\Form;
use SmyPhp\Core\Model;
use SmyPhp\Core\Form\BaseField;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Form
*/ 
class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_CHECK = 'checkbox';
    public const TYPE_DATE = 'date';
    public const TYPE_RADIO = 'radio';
    public const TYPE_FILE = 'file';

    public string $type;

    /**
     * Field constructor
     * 
     * @param \SmyPhp\Core\Model $model
     * @param string $attribute
     */

    public function __construct(Model $model, string $attribute){
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function Password(){
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function TypeNumber(){
        $this->type = self::TYPE_NUMBER;
        return $this;
    }

    public function CheckBox(){
        $this->type = self::TYPE_CHECK;
        return $this;
    }

    public function TypeDate(){
        $this->type = self::TYPE_DATE;
        return $this;
    }

    public function TypeFile(){
        $this->type = self::TYPE_FILE;
        return $this;
    }

    public function TypeRadio(){
        $this->type = self::TYPE_RADIO;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s">',
            $this->type, 
            $this->attribute, 
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
        );
    }
}