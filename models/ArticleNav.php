<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\Application;
use codewild\phpmvc\db\DbModel;
use codewild\phpmvc\Recursive;
use codewild\phpmvc\Request;

class ArticleNav extends Recursive
{
    public static function tableName(): string
    {
        return 'article_navs';
    }

    public string $articleId = '';
    public string $versionId = '';

    public Article|bool $article;
    public ModuleVersion|bool $version = false;

    public ArticleNav|bool $prev;
    public ArticleNav|bool $next;

    public function __construct(?string $articleId = null, ?string $versionId = null){
        if (!is_null($articleId)){
            $this->articleId = $articleId;
        }
        if (!is_null($versionId)){
            $this->versionId = $versionId;
        }

        $this->article = Article::findOne(['id' => $this->articleId]);
        $this->version = ModuleVersion::findOne(['id' => $this->versionId]);
        $urlAttributes = ['path' => $this->version->module->path, 'id' => $this->version->shortId(), 'n' => $this->n];

        $this->article->url_index = Request::createUrl(Article::URL_INDEX, $urlAttributes);
        $this->article->url_edit = Request::createUrl(Article::URL_EDIT, $urlAttributes);
    }

    public function updateVersion(){
        if (!$this->version){
            $this->getVersion();
        }
        $this->version->status = 0;
        $this->version->updated_at = date('Y-m-d H:i:s');
        $this->version->updated_by = Application::$app->user->id;

        return $this->version->update();
    }


    public static function attributes(): array
    {
        $output = Recursive::attributes();
        array_push($output, 'articleId', 'versionId');
        return $output;
    }

    public function rules(): array
    {
        return [
            'n' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'condition' => ['versionId' => $this->versionId, 'articleId' => $this->articleId]]],
        ];
    }

    public function update(?array $keys = null){
        if ($this->updateVersion()){
            return parent::update();
        }
        return false;
    }

    public function delete()
    {
        if (!$this->updateVersion()){
            return false;
        }

        $children = self::findMany(['parentId' => $this->id]);
        foreach ($children as $child){
            $child->parentId = $this->parentId ?? null;
            $child->update(['parentId']);
        }

        $siblings = self::findMany(['versionId' => $this->versionId]);
        foreach($siblings as $sib){
            if ($sib->n > $this->n) {
                $sib->n -= 1;
                $sib->update(['n']);
            }
        }

        if (!$this->isShared()){
            $this->article->delete();
        }

        // Override Recursive delete method
        return DbModel::delete();
    }

    public function getNeighbors() {
        $this->prev = self::findOne(['versionId' => $this->versionId, 'n' => $this->n - 1]);
        $this->next = self::findOne(['versionId' => $this->versionId, 'n' => $this->n + 1]);
    }

    public function isShared(): bool{
        $stmt = self::prepare("SELECT id FROM article_navs WHERE articleId = :articleId AND versionId <> :versionId");
        $stmt->bindValue(':articleId', $this->articleId);
        $stmt->bindValue(':versionId', $this->versionId);
        $stmt->execute();
        $rows = $stmt->fetchColumn(0);
        return !empty($rows);
    }

    public function hasError($attribute)
    {
        $articleErrors = $this->article->hasError($attribute);
        $parentErrors = parent::hasError($attribute);
        return ($articleErrors || $parentErrors);
    }
    public function getFirstError($attribute)
    {
        return $this->article->getFirstError($attribute);
    }

}
