<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\DbFile;

class Image extends DbFile
{
    public static function tableName(): string
    {
        return 'images';
    }

    public function rules(): array
    {
        return [
            'type' => [[self::RULE_CONTAINS, 'contains' => ['image/png', 'image/jpg', 'image/jpeg']]],
            'size' => [[self::RULE_MAX, 'max' => 1000000]],
        ];
    }

    public function addError(string $attribute, string $rule, $params = []){
        $message = $this->errorMessages()[$rule] ?? $rule;
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        // Override so that all attributes map to 'name' input
        $this->errors['name'][] = $message;
    }

    public function errorMessages(): array
    {
        $array = parent::errorMessages();
        $array[self::RULE_MAX] = 'Cannot be larger than 1MB';
        $array[self::RULE_CONTAINS] = 'Must be a valid image file';
        return $array;
    }
}
