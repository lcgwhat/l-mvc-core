<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc;


abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public function loadData($data)
    {
        foreach ($data as $key=>$value){
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public $errors=[];
    abstract public function rules():array;
    public function validate()
    {
        foreach ($this->rules() as $attribute=>$rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName == self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName == self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName == self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName == self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName == self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName == self::RULE_UNIQUE) {
                    $classname = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tabelname = $classname::tablename();
                    $statement = Application::$app->db->pdo->prepare("select * from $tabelname where $uniqueAttr=:$uniqueAttr");
                    $statement->bindValue(":$uniqueAttr", $value);

                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getLabel($attribute)
    {
        return $this->attributeLabel()[$attribute] ?? $attribute;
    }
    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }
    private function addErrorForRule($attribute, $rule, $params=[])
    {
        $message = $this->errorMessage()[$rule] ?? '';
        foreach ($params as $key=>$param) {
            $message = str_replace("{{$key}}", $param, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function errorMessage(){
        return [
            self::RULE_REQUIRED => "this field is required",
            self::RULE_EMAIL => "this field must be valid email address",
            self::RULE_MIN => "Min length of this field must be {min}",
            self::RULE_MAX => "Max length of this field must be {max}",
            self::RULE_MATCH => "this field must be same as {match}",
            self::RULE_UNIQUE => "Record with this {field} already exists",
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? '';
    }
    public function attributeLabel(){
        return [];
    }
}
