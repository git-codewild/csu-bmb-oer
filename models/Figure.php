<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\db\DbModel;

class Figure extends DbModel
{
    static public function tableName(): string
    {
        return 'figures';
    }

    public string $title = '';
    public ?string $caption = '';
    public string $imageId = '';

    public Image|bool $image;

    public function __construct()
    {
        $image = Image::findOne(['id' => $this->imageId]);
        $this->image = (!$image) ? new Image() : $image;
    }

    public static function attributes(): array
    {
        $output =  parent::attributes();
        array_push($output, 'title', 'caption', 'imageId');
        return $output;
    }

    public function rules(): array
    {
        return [
            'title' => [self::RULE_REQUIRED, self::RULE_UNIQUE, [self::RULE_MAX, 'max' => 64]],
            'caption' => [[self::RULE_MAX, 'max' => 1000]],
        ];
    }

    public function create(string $articleId){
        if ($this->save()){
            return (Slide::create(Slide::TYPE_FIGURE, self::lastInsertId(), $articleId));
        }
        return false;
    }

    public function delete()
    {
        if (!$this->image->delete()){
            return false;
        }
        return parent::delete();
    }
}
