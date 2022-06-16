<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\UserModel;

class User extends UserVM
{
    public string $email = '';
    public string $password = '';
    public string $confirmPw = '';

    public static function attributes(): array
    {
        $output = parent::attributes();
        array_push($output, 'email', 'password');
        return $output;
    }

    public function rules(): array {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'username' => [self::RULE_REQUIRED, self::RULE_UNIQUE],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], self::RULE_REGEX_UPPER, self::RULE_REGEX_LOWER, self::RULE_REGEX_NUMBER, self::RULE_REGEX_SPECIAL],
            'confirmPw' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }
    public function labels(): array{
        $output = parent::labels();
        $output[] = [
            'email' => 'Email',
            'password' => 'Password',
            'confirmPw' => 'Repeat password',
        ];
        return $output;
    }

    public function update(?array $keys = null){
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::update($keys);
    }

    public function save(){
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

}
