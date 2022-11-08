<?php

namespace SmyPhp\Core;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core
*/ 
abstract class Model
{
    public const REQUIRED_RULE = 'required';
    public const EMAIL_RULE = 'email';
    public const MIN_RULE = 'min';
    public const MAX_RULE = 'max';
    public const MATCH_RULE = 'match';
    public const UNIQUE_RULE = 'unique';

    public function loadData($data){
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public function labels(): array{
        return [];
    }

    public function getLabel($attribute){
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    public function validate(){
        foreach ($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }
                if($ruleName === self::REQUIRED_RULE && !$value){
                    $this->addError($attribute, self::REQUIRED_RULE);
                }
                if($ruleName === self::EMAIL_RULE && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addError($attribute, self::EMAIL_RULE);
                }
                if($ruleName === self::MIN_RULE && strlen($value) < $rule['min']){
                    $this->addError($attribute, self::MIN_RULE, $rule);
                }
                if($ruleName === self::MAX_RULE && strlen($value) < $rule['max']){
                    $this->addError($attribute, self::MAX_RULE, $rule);
                }
                if($ruleName === self::MATCH_RULE && $value !== $this->{$rule['match']}){
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addError($attribute, self::MATCH_RULE, $rule);
                }
                if($ruleName === self::UNIQUE_RULE){
                    $className = $rule['table'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $stmt = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $stmt->bindValue(":attr", $value);
                    $stmt->execute();
                    $record = $stmt->fetchObject();
                    if($record){
                        $this->addError($attribute, self::UNIQUE_RULE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addError(string $attribute, string $rule, $params = []){
        $message = $this->errorMessage()[$rule] ?? '';
        foreach ($params as $key => $value) {
           $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function throwError(string $attribute, string $message){
        $this->errors[$attribute][] = $message;
    }

    public function errorMessage(){
        return[
            self::REQUIRED_RULE => 'This field is required',
            self::EMAIL_RULE => 'This field must contain a valid email address',
            self::MIN_RULE => 'Minimum length of this field must be {min}',
            self::MAX_RULE => 'Maximum length of this field must be {max}',
            self::MATCH_RULE => 'This field must be the same as {match}',
            self::UNIQUE_RULE => 'This {field} already exists',
        ];
    }

    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }
}