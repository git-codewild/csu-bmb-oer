<?php

namespace codewild\csubmboer\controllers;

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\Controller;
use codewild\csubmboer\core\exception\DbException;
use codewild\csubmboer\core\exception\ForbiddenException;
use codewild\csubmboer\core\middleware\AuthMiddleware;
use codewild\csubmboer\core\Request;
use codewild\csubmboer\core\Response;
use codewild\csubmboer\models\Article;
use codewild\csubmboer\models\ArticleNav;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\ModuleVersion;

class ModuleVersionController extends Controller
{
    public function edit(Request $request, Response $response)
    {
        $routeParams = $request->getRouteParams();

        $version = ModuleVersion::findByShortId($routeParams['id']);

        if (!AuthHandler::authorize($version, 'update')){
            throw new ForbiddenException();
        }

        $version->getNavs();
        $version->module->getVersions();

        $body = $request->getBody();

        $inputModel = new Article();

        if ($request->isPost()) {
            // SUBMIT CHANGES
            if (array_key_exists('updateStatus', $body)){
                $version->status = 1;
                $version->update();
            }
            // MAKE CHANGES
            else {
                // CREATE NEW ARTICLE
                if (array_key_exists('create', $body)) {
                    $inputModel->loadData($body);
                    $parentId = array_key_exists('parentId', $body) ? $body['parentId'] : null;
                    if ($inputModel->validate() && $inputModel->save()) {
                        if ($version->createArticleNav($inputModel::lastInsertId(), $parentId)) {
                            Application::$app->session->setFlash('success', 'Article created successfully');
                        } else {
                            // The article was inserted into the database but there was a problem inserting the articleNav;
                            $inputModel->delete();
                            Application::$app->session->setFlash('danger', 'There was a problem inserting the articleNav in the database');
                        }
                        return $response->redirect();
                    }
                } else {
                    $articleNav = current(ArticleNav::filter($version->articleNavs, ['id' => $body['id']]));

                    $isShared = $articleNav->isShared();

                    // RENAME
                    if (array_key_exists('rename', $body)) {
                        if ($isShared) {
                            // CREATE A NEW ARTICLE WITH $inputModel->title;
                        } else {
                            if ($articleNav->article->title !== $body['title']) {
                                $articleNav->article->title = $body['title'];
                                if ($articleNav->article->validate(['title']) && $articleNav->article->update(['title'])) {
                                    Application::$app->session->setFlash('success', 'Article renamed successfully');
                                    return $response->redirect();
                                }
                            }
                        }
                    }
                    // DELETE
                    if (array_key_exists('delete', $body)) {
                        if ($isShared) {
                            if (!$articleNav->delete()) {
                                // ARTICLE NAV FAILED TO DELETE
                            }
                        } else if (!$articleNav->article->delete() || !$articleNav->delete()) {
                            // ARTICLE OR ARTICLENAV FAILED TO DELETE
                        } else {
                            Application::$app->session->setFlash('success', 'Article was deleted successfully');
                            return $response->redirect();
                        }
                    }
                    // MOVE UP
                    if (array_key_exists('moveUp', $body)) {
                        if ($articleNav->n > 1) {
                            $toSwap = current(ArticleNav::filter($version->articleNavs, ['n' => $articleNav->n - 1]));
                            if ($toSwap->parentId !== $articleNav->parentId) {
                                $articleNav->parentId = $toSwap->parentId;
                            }
                            $articleNav->n -= 1;
                            $toSwap->n += 1;

                            if ($articleNav->update() && $toSwap->update()) {
                                Application::$app->session->setFlash('success', 'Section was reordered.');
                                return $response->redirect();
                            }
                        }
                    }
                    if (array_key_exists('indent', $body)) {
                        // INDENT
                        if (is_null($articleNav->parentId)) {
                            $newParent = current(ArticleNav::filter($version->articleNavs, ['n' => $articleNav->n - 1]));
                            if (!is_null($newParent->parentId)) {
                                $newParent = current(ArticleNav::filter($version->articleNavs, ['id' => $newParent->parentId]));
                            }
                            $articleNav->parentId = $newParent->id;
                            if (!empty($articleNav->children)) {
                                foreach ($articleNav->children as $child) {
                                    $child->parentId = $newParent->id;
                                    if (!$child->update()) {
                                        Application::$app->session->setFlash('danger', 'Article ' . $child->title . ' could not be updated.');
                                        return $response->redirect();
                                    }
                                }
                            }
                        } // OUTDENT
                        else {
                            $siblings = ArticleNav::filter($version->articleNavs, ['parentId' => $articleNav->parentId]);
                            if (!empty($siblings)) {
                                foreach ($siblings as $sibling) {
                                    $sibling->n -= 1;
                                    if (!$sibling->update()) {
                                        Application::$app->session->setFlash('danger', 'Article ' . $sibling->title . ' could not be updated.');
                                        return $response->redirect();
                                    }
                                }
                                $articleNav->n += count($siblings);
                            }
                            $articleNav->parentId = null;

                        }
                        if ($articleNav->update()) {
                            Application::$app->session->setFlash('success', 'Section was indented.');
                            return $response->redirect();
                        }
                    }
                }
            }
        }

        return $this->render('module/version/edit', ['model' => $version, 'inputModel' => $inputModel]);
    }

}
