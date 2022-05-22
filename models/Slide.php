<?php

namespace codewild\csubmboer\models;

class Slide extends \codewild\csubmboer\core\db\BaseDbModel
{
    public const TYPE_FIGURE = 'Figure';
    public const TYPE_JSMOL = 'Script_JSmol';

    static public function tableName(): string
    {
        return 'slides';
    }
    static public function primaryKey(): array
    {
        return ['resourceId', 'articleId'];
    }

    public string $resourceId;
    public string $articleId;
    public int $n;
    public string $type;

    public object $resource;

    public static function attributes(): array
    {
        return ['resourceId', 'articleId', 'n', 'type'];
    }

    public function isShared(): bool {
        return (count(self::findMany(['resourceId' => $this->resourceId])) > 1);
    }

    public static function create($type, $resourceId, $articleId){
        $slide = new self;
        $slide->type = $type;
        $slide->resourceId = $resourceId;
        $slide->articleId = $articleId;
        $existingSlides = self::findMany(['articleId' => $articleId]);
        $slide->n = count($existingSlides) + 1;
        return $slide->save();
    }

    public function delete(){
        // If not shared, delete the resource
        if (!$this->isShared()){
            if(!$this->resource->delete()){
                return false;
            };
        }
        // Reorder the existing slides
        $existingSlides = self::findMany(['articleId' => $this->articleId]);
        foreach ($existingSlides as $existingSlide){
            if ($existingSlide->n > $this->n){
                $existingSlide->n -= 1;
                $existingSlide->update();
            }
        }
        // Delete the nav property
        return parent::delete();
    }

}
