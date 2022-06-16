<?php

namespace codewild\csubmboer\views\article;

use codewild\phpmvc\Application;
use codewild\phpmvc\Nav;
use codewild\phpmvc\Request;
use codewild\csubmboer\models\ArticleNav;
use codewild\csubmboer\models\ModuleVersion;

class _articleNav extends Nav
{
    public ModuleVersion $version;
    public array $pages;
    public array $titles;
    public array $routes;

    public function __construct(ModuleVersion $version, string $path)
    {
        $this->version = $version;
        $flatten = $this->version->flattenNavs();
        $this->pages = array_column($flatten, 'n');
        array_unshift($this->pages, 0);
        $this->titles[0] = 'Outline';
        $this->routes[0] = Request::createUrl($path, ['path' => $version->module->path, 'id' => $version->shortId()]);
        foreach ($this->version->articleNavs as $nav) {
            $this->set($nav, $path);
            if (!empty($nav->children)){
                foreach ($nav->children as $child){
                    $this->set($child, $path, true);
                }
            }
        }
        return parent::__construct('rounded-0 rounded-end');
    }

    public function pages(): array
    {
        return $this->pages;
    }
    public function routes(): array
    {
        return $this->routes;
    }
    public function titles(): array
    {
        return $this->titles;
    }
    public function needle(): string
    {
        return Application::$app->request->getRouteParams()['n'] ?? 0;
    }
    public function set(ArticleNav $nav, string $path, bool $isChild = false,){
        $this->titles[$nav->n] = ($isChild) ? '&emsp13;&emsp13;'.$nav->n.'. '.$nav->article->title : $nav->n.". ".$nav->article->title;
        $this->routes[$nav->n] = Request::createUrl($path, ['path' => $this->version->module->path, 'id' => $this->version->shortId(), 'n' => $nav->n]);
    }
}
