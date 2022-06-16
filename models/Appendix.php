<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\db\DbModel;

class Appendix extends DbModel
{
    public string $path = '';
    public string $title = '';
    public ?string $html = null;
    public string $created_by = User::DEFAULT_UUID;

    public static function tableName(): string
    {
        return 'appendices';
    }

    public static function attributes(): array
    {
        $output = parent::attributes();
        array_push($output, 'path', 'title', 'html', 'created_by');
        return $output;
    }

    public function labels(): array
    {
        return [
            'path' => 'URL',
            'title' => 'Title',
            'html' => 'Content'
        ];
    }

    public function rules(): array
    {
        return [
            'path' => [self::RULE_REQUIRED, self::RULE_UNIQUE, self::RULE_MIN => 3, self::RULE_MAX => 64],
            'title' => [self::RULE_REQUIRED, self::RULE_MIN => 3, self::RULE_MAX => 64]
        ];
    }
}
