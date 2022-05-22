<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\db\DbModel;

class Article extends DbModel {
    public const URL_INDEX = '/module/{path}/v/{id}/{n}';
    public const URL_EDIT = '/module/{path}/v/{id}/edit/{n}';

    public static function tableName(): string
    {
        return 'articles';
    }

    public string $title = '';
    public string $html = '';
    public string $url_index;
    public string $url_edit;

    public array $slides = [];

    public static function attributes(): array
    {
        $array = parent::attributes();
        array_push($array, 'title', 'html');
        return $array;
    }
    public function labels(): array
    {
        $array = parent::labels();

        $array['title'] = 'Title';
        $array['html'] = 'HTML Content';

        return $array;
    }

    public function rules(): array
    {
        return [
            'title' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 64]]
        ];
    }

    public function getSlides(){
        $this->slides = Slide::findMany(['articleId' => $this->id], 'n');
        foreach ($this->slides as $slide){
            $class = "codewild\csubmboer\models\\$slide->type";
            $slide->resource = $class::findOne(['id' => $slide->resourceId]);
        }
    }
}

?>
