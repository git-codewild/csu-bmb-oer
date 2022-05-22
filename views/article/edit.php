<?php

/**
 *  @var \codewild\csubmboer\models\ArticleNav $model
 *  @var \codewild\csubmboer\models\Article $article
 *  @var \codewild\csubmboer\models\Figure $newFigure
 *  @var \codewild\csubmboer\models\DataFile $newDataFile
 */

use codewild\csubmboer\core\components\Modal;
use codewild\csubmboer\core\components\TabList;
use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\models\FigureCard;
use codewild\csubmboer\models\JSmolCard;
use codewild\csubmboer\views\article\_articleNav;

$this->title = 'Edit Article: '.$article->title;

?>

<div class="row h-100 mb-2">
    <div class="col-md-2">
        <?php
            new _articleNav($model->version, $article::URL_EDIT);
        ?>
        <div class="btn-group m-2">
            <button class="active btn btn-info">Editing</button>
            <a class="btn" role="link" href="<?php echo $article->url_index?>">Preview</a>
        </div>
    </div>
    <section class="col-md-5">
        <div class="container d-flex flex-column h-100">
            <?php
                $tablist = new TabList();
                echo $tablist->begin('slidesTab', 'nav-pills my-2');
                    foreach($article->slides as $slide){
                        echo $tablist->navItem($slide->resource, 'title', $slide->n);
                    }
                ?>
                <li class="nav-item dropdown" role="presentation">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        +
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php
                                $figureForm = new Form('multipart', 'addFigure');
                                $figureFormBody = $figureForm->begin().$figureForm->field($newFigure, 'title').$figureForm->field($newFigure->image, 'name')->setType('file')."</form>";
                                $figureFormFooter = "<button type='submit' form='addFigure' name='addFigure' class='btn btn-primary'>Upload</button>";
                                $addFigureModal = new Modal('addFigureModal', 'Add figure', $figureFormBody, $figureFormFooter);
                                echo $addFigureModal->setType(Modal::TYPE_DD_ITEM);
                            ?>
                        </li>
                        <li>
                            <form action="" method="POST">
                                <input class="dropdown-item" type="submit" name="addJSmol" value="Add JSmol">
                            </form>
                        </li>
                    </ul>
                </li>
            <?php
                echo $tablist->contentStart('slides', 'row flex-grow-1 overflow-hidden');
                foreach($article->slides as $slide){
                    $resource = $slide->resource;
                    if ($slide->type === $slide::TYPE_FIGURE){
                        echo "<div id='slide$slide->n' class='tab-pane fade h-100 w-100' role='tabpanel' aria-labelledby='slide$slide->n-tab'>";
                        $card = new FigureCard($resource);
                        echo $card->editable();
                        echo "</div>";
                    }
                    if ($slide->type === $slide::TYPE_JSMOL){
                        $resource->push($this->scripts);
                        echo "<div id='slide$slide->n' class='tab-pane fade' role='tabpanel' aria-labelledby='slide$slide->n-tab'>";
                            $card = new JSmolCard($resource, $newDataFile, ['card' => 'd-flex flex-column h-100 overflow-auto', 'body' => 'flex-grow-1']);
                            echo $card;
                        echo "</div>";
                    };
                }
            ?>
        </div>
    </section>
    <section class="col-md-5 mh-100">
        <div class="container d-flex flex-column h-100">
            <ul class="nav nav-tabs" id="articleTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link" id="articleHtmlTab" data-bs-toggle='tab' data-bs-target='#articleHtml' type='button' role='tab' aria-controls='articleHtml'>HTML</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="articleEditorTab" data-bs-toggle='tab' data-bs-target='#articleEditor' type='button' role='tab' aria-controls='articleEditor'>Editor</button>
                </li>
            </ul>
            <div id="articleTabContent" class="tab-content h-100 overflow-hidden">
                <article class="tab-pane fade h-100 overflow-auto" id="articleHtml" role='tabpanel' aria-labelledby='articleHtmlTab'>
                    <?php echo html_entity_decode($article->html); ?>
                </article>
                <div class="tab-pane fade h-100" id="articleEditor" role='tabpanel' aria-labelledby='articleEditorTab'>
                    <?php
                        $form = new Form('', 'updateArticle');
                        echo $form->begin('d-flex flex-column h-100 pb-2');
                        echo $form->field($article, 'title');
                        echo $form->textarea($article, 'html', ['div' => 'd-flex flex-column flex-grow-1', 'textarea' => 'flex-grow-1 overflow-auto'])->id('editor');
                        echo $form->end('Save', 'btn-warning');
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
    array_push($this->scripts,
        '<script src="/lib/ckeditor5/ckeditor.js"></script>',
        '<script type="text/javascript">
            $(function (){
                new bootstrap.Tab($("#slide1-tab")).show();
                new bootstrap.Tab($("#appletTab")).show();
                new bootstrap.Tab($("#articleHtmlTab")).show();
            });
        </script>');

if (!empty($newFigure->errors) || !empty($newFigure->image->errors)) {
    $this->scripts[] = '
    <script type="text/javascript">
        let element = document.getElementById("addFigureModal");	
        var modal = new bootstrap.Modal(element);
        modal.show();
    </script>';
}
if (!empty($newDataFile->errors)){
    $this->scripts[] = '
    <script type="text/javascript">
        let element = document.getElementById("createFileModal");	
        var modal = new bootstrap.Modal(element);
        modal.show();
    </script>';
}


?>
