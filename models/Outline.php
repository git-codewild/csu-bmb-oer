<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\Recursive;

class Outline extends Recursive
{
    public const COURSE_BC401 = 'BC401';

    public const TYPE_CHAPTER = 'Chapter';
    public const TYPE_SECTION = 'Section';

    public static function tableName(): string
    {
        return 'outlines';
    }

    public string $courseId = 'BC401';
    public string $type = '';
    public ?string $moduleId = null;
    public ?string $title = ''; // Should be inherited from module if exists


    public function __construct(){
        if (is_null($this->parentId)){
            $this->type = self::TYPE_CHAPTER;
        } else {
            $this->type = self::TYPE_SECTION;
        }
        $this->findChildren();
    }

    public static function attributes(): array
    {
        $array = Recursive::attributes();
        array_push($array, 'courseId', 'moduleId', 'title');
        return $array;
    }
    public function labels(): array
    {
        return [
            'courseId' => 'Course',
            'parentId' => 'Parent',
            'n' => 'Index',
            'title' => 'Title',
            'moduleId' => 'Module'
        ];
    }

    public function rules(): array
    {
        $output = parent::rules();
        $output['title'] = [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 64]];
        return $output;
    }

    public function courseNames(): array{
        return [
            self::COURSE_BC401 => 'The Fundamentals of Biochemistry',
        ];
    }
}
