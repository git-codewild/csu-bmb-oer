<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\Recursive;
use codewild\phpmvc\Request;

class Outline extends Recursive
{
    public const COURSE_BC401 = ['BC401' => 'The Fundamentals of Biochemistry'];

    public const TYPE_CHAPTER = 'Chapter';
    public const TYPE_SECTION = 'Section';

    public static function tableName(): string
    {
        return 'outlines';
    }

    public string $courseId = 'BC401';
    public string $type = '';
    public ?string $moduleVersionId = null;
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
        array_push($array, 'courseId', 'moduleVersionId', 'title');
        return $array;
    }
    public function labels(): array
    {
        return [
            'courseId' => 'Course',
            'parentId' => 'Parent',
            'n' => 'Index',
            'title' => 'Title',
            'moduleVersionId' => 'Module'
        ];
    }

    public function rules(): array
    {
        $output = parent::rules();
        $output['title'] = [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 64]];
        return $output;
    }

    public function chapterList() {
        $buffer = "<ol class='list-group list-group-numbered'>";
        foreach ($this->children as $section) {
            $buffer .= "<li class='list-group-item'>";
            if ($section->moduleVersionId === null){
                $buffer .= $section->title;
            } else {
                $path = ModuleVersion::findOne(['id' => $section->moduleVersionId])->module->path;
                $url = Request::createUrl('ch{n}/{path}', ['n' => $this->n, 'path' => $path]);
                $buffer .= "<a href='$url'>$section->title</a>";
            }

            if (!empty($section->children)){
                $buffer .= "<ol style='list-style-type: upper-alpha'>";
                foreach($section->children as $subsection){
                    $buffer .= "<li class='list-group-flush'>";
                    if (is_null($subsection->moduleVersionId)){
                        $buffer .= $subsection->title;
                    } else {
                        $path = ModuleVersion::findOne(['id' => $subsection->moduleVersionId])->module->path;
                        $url = Request::createUrl('ch{n}/{path}', ['n' => $this->n, 'path' => $path]);
                        $buffer .= "<a href='$url'>$subsection->title</a>";
                    }
                    $buffer .= "</li>";
                }
                $buffer .= "</ol>";
            }
            $buffer .= "</li>";
        }
        $buffer .= "</ol>";
        return $buffer;
    }

}
