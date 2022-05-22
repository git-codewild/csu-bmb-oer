<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\Model;
use codewild\csubmboer\models\UserVM;

class LoginForm extends Model {
    public string $uniqueId = '';
    public string $password = '';

    public function rules(): array {
        return [
            'uniqueId' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED],
        ];
    }
    public function labels(): array {
        return [
            'uniqueId' => 'Email or usernamne',
            'password' => 'Password'
        ];
    }
    public function login(){
        $uniqueField = filter_var($this->uniqueId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::findOne([$uniqueField => $this->uniqueId]);
        if (!$user){
            $this->addError('uniqueId', 'User not found. Create a new account?');
            return false;
        }
        if (!password_verify($this->password, $user->password)){
            $this->addError('password', 'Password is incorrect'); 
            return false;
        } else {
            return Application::$app->login($user);
        }
    }
}

?>
