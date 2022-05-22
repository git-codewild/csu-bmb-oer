<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\db\BaseDbModel;

class UserRole extends BaseDbModel
{
    public const ROLE_USER = 0;
    public const ROLE_AUTHOR = 1;
    public const ROLE_ADMIN = 2;

    static public function tableName(): string
    {
        return 'user_roles';
    }
    static public function primaryKey(): array
    {
        return ['userId', 'roleId'];
    }

    public string $userId = '';
    public int $roleId = 0;




}
