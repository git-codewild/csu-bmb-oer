<?php

/** @var \codewild\csubmboer\models\ModuleVersion $model
 *  @var \codewild\csubmboer\models\Article $article
 * @var string $articleRef;
 * @var \codewild\csubmboer\models\Outline $chapter
 */

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\components\TabList;
use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\lists\DescriptionList;
use codewild\csubmboer\models\FigureCard;
use codewild\csubmboer\models\JSmolCard;
use codewild\csubmboer\views\article\_articleNav;

$this->title = is_null($chapter) ? $model->module->title :
    "<div class='row flex-row-reverse flex-wrap-reverse '>
        <div class='col-sm text-sm-start'>".$article->title."</div>
        <div class='col-sm flex-grow-0 d-none d-sm-flex'> | </div>
        <div class='col-sm flex-grow-0'>".$model->module->title."</div>
        <div class='col-sm flex-grow-0 d-none d-sm-flex'> | </div>
        <div class='col-sm text-sm-end'><a href='/ch$chapter->n' class='link-secondary'>Chapter $chapter->n: $chapter->title</a></div> 
    </div>";

?>

<div class="row h-100">
    <div class="col-md-2">
        <?php

            new _articleNav($model, $articleRef);

            if (AuthHandler::authorize($model, 'update')){
                echo "
                    <div class='w-100 text-center'>
                        <a class='btn btn-info mt-2 mx-auto' href='".$article->url_edit."'>Edit Article</a>
                    </div>";
            }

        ?>



    </div>
    <section class="col-md-5 mb-2 p-0">
        <div class="d-flex flex-column h-100">
            <?php
            $tablist = new TabList();
            echo $tablist->begin('slidesTab', 'nav-pills my-2');
            foreach($article->slides as $slide){
                echo $tablist->navItem($slide->resource, 'title', $slide->n);
            }
            echo $tablist->contentStart('slides', 'row flex-grow-1 overflow-hidden');
            foreach($article->slides as $slide){
                $resource = $slide->resource;
                if ($slide->type === $slide::TYPE_FIGURE){
                    echo "<div id='slide$slide->n' class='tab-pane fade h-100 w-100' role='tabpanel' aria-labelledby='slide$slide->n-tab'>";
                    $card = new FigureCard($resource);
                    echo $card;
                    echo "</div>";
                }
                if ($slide->type === $slide::TYPE_JSMOL){
                    $resource->push($this->scripts);
                    echo "<div id='slide$slide->n' class='tab-pane fade' role='tabpanel' aria-labelledby='slide$slide->n-tab'>
                            <object id='jsmolApplet' class='h-100 w-100'></object>
                        </div>";
                };
            }
            ?>
        </div>
    </section>
    <section class="col-md-5 mh-100 p-0">
        <div class="d-flex flex-column h-100">
            <article class="h-100 overflow-auto px-4" id="articleHtml">
                <?php echo html_entity_decode(htmlspecialchars_decode($article->html)); ?>
            </article>
        </div>
    </section>
</div>
<?php
    $this->scripts[] = '
        <script type="text/javascript">
            $(function (){
                new bootstrap.Tab($("#slide1-tab")).show();
            });
        </script>';
?>
