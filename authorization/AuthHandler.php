<?php

namespace codewild\csubmboer\authorization;

use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\Model;
use codewild\csubmboer\models\Appendix;
use codewild\csubmboer\models\Article;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\ModuleVersion;
use codewild\csubmboer\models\Outline;
use codewild\csubmboer\models\UserRole;

class AuthHandler
{
    public const ACTION_CREATE = 'create';
    public const ACTION_READ = 'read';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';
    public const ACTION_APPROVE = 'approve';

    public static function authorize(Model $model, string $action): bool
    {
        if (!Application::isGuest()) {
            $user = Application::$app->user;

            if ($model instanceof Outline) {
                if ($action === self::ACTION_CREATE && $user->isInRole(UserRole::ROLE_ADMIN)){
                    return true;
                }
                if ($action === self::ACTION_UPDATE && $user->isInRole(UserRole::ROLE_ADMIN)){
                    return true;
                }
            }

            if ($model instanceof Module){
                if ($action === self::ACTION_CREATE){
                    if ($user->isInRole(UserRole::ROLE_AUTHOR) || $user->isInRole(UserRole::ROLE_ADMIN)){
                        return true;
                    }
                }
                if ($action === self::ACTION_UPDATE) {
                    if ($user->isInRole(UserRole::ROLE_ADMIN) || $user->id === $model->created_by){
                        return true;
                    }
                }
                if ($action === self::ACTION_DELETE){
                    // Will be a published version unless none exist
                    $latestVersion = $model->getLatestVersion($model->path);
                    if (($user->isInRole(UserRole::ROLE_ADMIN) || $user->id === $model->created_by) && $latestVersion->status < 2){
                        return true;
                    }
                }
            }
            if ($model instanceof ModuleVersion) {
                // Any logged on user can fork
                if ($action === self::ACTION_CREATE) {
                    return true;
                }

                if ($action === self::ACTION_READ && $model->status !== ModuleVersion::STATUS_APPROVED){
                    if ($user->isInRole(UserRole::ROLE_ADMIN) || $user->id === $model->created_by){
                        return true;
                    }
                }

                if ($action === self::ACTION_UPDATE && $model->status !== ModuleVersion::STATUS_APPROVED){
                    if ($user->isInRole(UserRole::ROLE_ADMIN) || $user->id === $model->created_by){
                        return true;
                    }
                }
                if ($action === self::ACTION_DELETE){
                    if ($user->isInRole(UserRole::ROLE_ADMIN) || $user->id === $model->created_by){
                        return true;
                    }
                }
                if ($action === self::ACTION_APPROVE){
                    if ($user->isInRole(UserRole::ROLE_ADMIN) && $model->status === ModuleVersion::STATUS_SUBMITTED){
                        return true;
                    }
                }
            }
            if ($model instanceof Article) {
                // TODO: Expand article ownership/credits and
                if ($action === self::ACTION_UPDATE) {
                    if (!$model->isShared() && $user->isInRole(UserRole::ROLE_ADMIN)){
                        return true;
                    }
                }
            }



            if ($model instanceof Appendix){
                if ($action === self::ACTION_CREATE){
                    if ($user->isInRole(UserRole::ROLE_AUTHOR) || $user->isInRole(UserRole::ROLE_ADMIN)){
                        return true;
                    }
                }
                if ($action === self::ACTION_UPDATE){
                    if ($user->id === $model->created_by || $user->isInRole(UserRole::ROLE_ADMIN)){
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
