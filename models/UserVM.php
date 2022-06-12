<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\UserModel;

class UserVM extends UserModel {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function tableName(): string {
        return 'users';
    }

    public int $status = self::STATUS_INACTIVE;
    public string $username = '';
    public string $firstname = '';
    public string $lastname = '';
    public ?string $created_at = null;

    public static function attributes(): array{
        return ['status', 'firstname', 'lastname', 'username', 'created_at'];
    }
    public function labels(): array{
        return [
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'username' => 'Username',
        ];
    }
    public function getDisplayName(): string {
        return $this->firstname.' '.$this->lastname;
    }

    public function isInRole($roleId): bool
    {
        $result = UserRole::findOne(['userId' => $this->id, 'roleId' => $roleId]);
        if (empty($result)){
            return false;
        } else {
            return true;
        }
    }
}

?>
