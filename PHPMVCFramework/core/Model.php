<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED  = 'required';
    public const RULE_EMAIL     = 'email';
    public const RULE_MIN       = 'min';
    public const RULE_MAX       = 'max';
    public const RULE_MATCH     = 'match';
    public const RULE_UNIQUE     = 'unique';

    public array $errors = [];

    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }


    public function getLabels($attribute): string
    {
        return $this->labels()[$attribute];
    }


    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }


    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }

                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }

                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabels($rule['match']);
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className  = $rule['class'];
                    $tableName  = $className::tableName();
                    $uniqueAttr = $rule['attribute'] ?? $attribute;

                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();

                    if ($record) {
                        $this->addError($attribute, self::RULE_UNIQUE, ['field' => $this->getLabels($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }


    public function addError(string $attribute, string $ruleName, $params = [])
    {
        $message = $this->errormsg()[$ruleName] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }


    public function errormsg(): array
    {
        return [
            self::RULE_REQUIRED => '*This feild is Required',
            self::RULE_EMAIL    => 'Emial must be a valid Email',
            self::RULE_MIN      => 'It must be minimum {min} character',
            self::RULE_MAX      => 'It must be maximum {max} character',
            self::RULE_MATCH    => 'This feild must be the same as {match}',
            self::RULE_UNIQUE   => 'Record with this {field} already exists'
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
}
