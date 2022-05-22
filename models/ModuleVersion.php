<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\exception\DbException;
use codewild\csubmboer\core\Request;

class ModuleVersion extends \codewild\csubmboer\core\db\DbModel
{
    public const STATUS_CREATED = 0;
    public const STATUS_SUBMITTED = 1;
    public const STATUS_APPROVED = 2;
    public const URL_PATTERN = '/module/{path}/v/{id}';

    static public function tableName(): string
    {
        return 'module_versions';
    }

    public string $moduleId = '';
    public int $status = self::STATUS_CREATED;
    public ?string $created_at = null;
    public ?string $created_by = null;
    public ?string $updated_at = null;
    public ?string $updated_by = null;

    public Module|bool $module;
    public ?string $createdBy_displayName = null;
    public ?string $updatedBy_displayName = null;
    public ?array $articleNavs = null;
    public ?string $url = null;

    public function __construct(?string $moduleId = null)
    {
        if (!is_null($moduleId)){
            $this->moduleId = $moduleId;
        }
        $this->module = Module::findOne(['id' => $this->moduleId]);

        if (!is_null($this->created_by)){
            $this->createdBy_displayName = User::findOne(['id' => $this->created_by])->getDisplayName() ?? 'Undefined';
        }
        if (!is_null($this->updated_by)){
            $this->updatedBy_displayName = User::findOne(['id' => $this->updated_by])->getDisplayName() ?? 'Undefined';
        }

        $this->url = Request::createUrl(self::URL_PATTERN, ['path' => $this->module->path, 'id' => $this->shortId()]);
    }

    public function getNavs()
    {
        $this->articleNavs = ArticleNav::findMany(['versionId' => $this->id, 'parentId' => null], 'n');
        foreach($this->articleNavs as $articleNav){
            $articleNav->findChildren();
        }
    }

    public static function attributes(): array
    {
        return ['moduleId', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'];
    }

    public function labels(): array
    {
        return [
            'moduleId' => 'Module',
            'status' => 'Status',
            'created_at' => 'Created At',
            'createdBy_displayName' => 'Created By',
            'updated_at' => 'Updated At',
            'updatedBy_displayName' => 'Updated By'
        ];
    }

    public function delete()
    {
        foreach ($this->articleNavs as $nav){
            try
            {
                $nav->delete();
            }
            catch (\PDOException $e)
            {
                throw new DbException($e);
            }
        }
        return parent::delete();
    }

    public function clone(){
        $newVersion = new self();
        $newVersion->moduleId = $this->moduleId;
        $newVersion->created_by = Application::$app->user->id;
        if ($newVersion->save()){
            $versionId = self::lastInsertId();

            foreach ($this->articleNavs as $nav) {
                $newNav = new ArticleNav($nav->articleId, $versionId);
                $newNav->n = $nav->n;
                try {
                    $newNav->save();
                    if (!empty($nav->children)){
                        $parentId = self::lastInsertId();
                        foreach ($nav->children as $child){
                            $newChild = new ArticleNav($child->articleId, $versionId);
                            $newChild->parentId = $parentId;
                            $newChild->n = $child->n;
                            $newChild->save();
                        }
                    }

                } catch (\PDOException $e) {
                    throw new DbException($e);
                }
            }
            return substr($versionId, 0, 7);
        }
        return false;
    }

    public function createArticleNav($articleId, ?string $parentId = null): bool{
        $articleNav = new ArticleNav($articleId, $this->id);
        $articleNav->parentId = $parentId;

        $flatten = $this->flattenNavs();

        if (is_null($parentId)){
            $articleNav->n = count($flatten) + 1;
        } else {
            $parent = current(ArticleNav::filter($this->articleNavs, ['id' => $parentId]));
            $articleNav->n = $parent->n + count($parent->children) + 1;
            foreach($flatten as $nav){
                if ($nav->n >= $articleNav->n){
                    $nav->n ++;
                    $nav->update();
                }
            }
        }

        return $articleNav->save();
    }

    public function flattenNavs(): array{
        return ArticleNav::filter($this->articleNavs, ['versionId' => $this->id]);
    }
}
