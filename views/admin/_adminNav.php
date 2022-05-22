<?php

namespace codewild\csubmboer\views\admin;

use codewild\csubmboer\core\Application;

class _adminNav extends \codewild\csubmboer\core\Nav
{
    public function __construct()
    {
        parent::__construct('rounded-0 rounded-end');
    }

    public function pages(): array
    {
        return ['index', 'contacts', 'images', 'modules', 'users'];
    }
    public function routes(): array
    {
        return [
            'index' => '/admin',
            'modules' => '/admin/modules',
            'contacts' => '/admin/contacts',
            'images' => '/admin/images',
            'users' => '/admin/users'
        ];
    }

    public function titles(): array
    {
        return [
            'index' => 'Admin Portal',
            'modules' => 'Manage Modules',
            'contacts' => 'Read Messages',
            'images' => 'Manage Images',
            'users' => 'Manage Users'
        ];
    }
    public function needle(): string
    {
        return Application::$app->router->getCallback()[1];
    }


}

?>


