<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\Model;

class Search extends Model
{
    public const TYPE_APPENDIX = 'appendix';
    public const TYPE_MODULE = 'module';
    public const TYPE_ARTICLE = 'article';
    public const TYPE_FIGURE = 'figure';

    public string $type;
    public string $url;
    public string $title;
    public string $match;

    public function __construct($type, $url, $title, $match)
    {
        $this->type = $type;
        $this->url = $url;
        $this->title = $title;
        $this->match = $match;
    }

    public static function attributes(): array
    {
        return ['type', 'url', 'title', 'match'];
    }

    public static function search(string $q, ?string $type = null): array {
        $results = array();
        if (!is_null($type)){

        } else {
            self::searchAppendices($q, $results);
            self::searchModules($q, $results);
            self::searchArticles($q, $results);
        }
        return $results;
    }

    public static function searchAppendices(string $q, array &$results = []) {
        $appendices = Appendix::findAll();
        array_filter($appendices, function($a) use ($q, &$results) {
            $content = self::strip_tags($a->html);
            $title = strpos($a->title, $q);
            $html = strpos($content, $q);
            $record = is_int($title) || is_int($html);
            if ($record) {
                $results[] = new self(
                    'Appendix',
                    "/appendix/$a->path",
                    $a->title,
                    is_int($html) ? '...'.substr($content, $html, 200).'...' : $a->title
                );
            }
            return $record;
        });
    }
    public static function searchModules(string $q, array &$results = []) {
        $modules = Module::getPublishedModules();
        array_filter($modules, function($m) use ($q, &$results) {
            $record = str_contains($m->title, $q) || str_contains($m->subtitle, $q) || str_contains($m->keywords, $q);
            if ($record) {
                $results[] = new self(
                    'Module',
                    "/module/$m->path",
                    $m->title,
                    $m->keywords
                );
            }
            return $record;
        });
    }
    public static function searchArticles(string $q, array &$results = []) {
        $modules = Module::getPublishedModules();
        $publishedArticles = array();
        foreach ($modules as $module) {
            $version = Module::getLatestVersion($module->path);
            $version->getNavs();
            foreach ($version->articleNavs as $articleNav) {
                $publishedArticles[] = $articleNav->article;
            }
        }
        array_filter($publishedArticles, function($a) use ($q, &$results) {
            $content = self::strip_tags($a->html);
            $title = strpos($a->title, $q);
            $html = strpos($content, $q);
            $record = is_int($title) || is_int($html);
            if ($record) {
                $results[] = new self(
                    'Article',
                    $a->url_index,
                    $a->title,
                    is_int($html) ? '...'.substr($content, $html, 200).'...' : $a->title
                );
            }
            return $record;
        });
    }

    public static function strip_tags(string $html){
        $content = html_entity_decode($html);
        $content = strip_tags($content, ['p', 'br', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']);
        $content = preg_replace('/<[^>]*>/', "\n", $content);
        $content = trim(preg_replace('/\n{2,}/', "\n", $content));
        return nl2br($content);
    }




}
