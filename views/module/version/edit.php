<?php

/** @var \codewild\csubmboer\models\ModuleVersion $model
 *  @var Article $inputModel
 */

use codewild\csubmboer\core\components\Modal;
use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\lists\DescriptionList;
use codewild\csubmboer\core\Request;
use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\models\Article;

$this->title = 'Edit Module Version';

$addChildForm = new Form('', 'addChild');
$modalBody = (!empty($model->articleNavs)) ? $addChildForm->begin().$addChildForm->field($model->articleNavs[0], 'parentId')->hiddenField().$addChildForm->field($inputModel, 'title')."</form>" : '';
$modalFooter = '<button type="submit" form="addChild" name="create" class="btn btn-primary">Create</button>';

$modal = new Modal('exampleModal', 'Add Child', $modalBody, $modalFooter);

?>
<div class="container">

    <div class="row">
        <div class="col">
            <h5>Module</h5>
            <?php echo DescriptionList::create($model->module); ?>
            <a href="/module/<?php echo $model->module->path; ?>/edit" class="link">Edit Module</a>
            <hr>
            <h5>Version</h5>
            <?php echo DescriptionList::create($model, ['status', 'created_at', 'created_by', 'updated_at', 'updated_by']); ?>
        </div>
        <div class="col-xl-8">
        <?php
            $statusForm = new Form('', 'updateStatus');
            if($model->status === 0){
                echo $statusForm->begin();
                echo $statusForm->field($model, 'status')->hiddenField();
                echo $statusForm->end('Submit changes', 'btn-dark float-end');
            } else {
                echo "<button class='btn btn-dark float-end' disabled>Submitted</button>";
            }

            if(!empty($model->articleNavs)) {
                $table = Table::begin($model->articleNavs[0], ['Parent', 'Child', 'Move', 'Add', 'Rename', 'Delete', 'Edit']);
                foreach ($model->articleNavs as $a) {
                    $editRef = Request::createUrl(Article::URL_EDIT, ['path' => $model->module->path, 'n' => $a->n, 'id' => $model->shortId()]);
                    echo $table->formRow($a, ['n', '', 'move' => $a->n, 'addChild' => $modal->setOnClick("$('#".$modal->id." input:first').attr('value', '".$a->id."')"), 'rename' => $a->article->title, 'delete' => $a->id, 'link' => ['title' => 'Edit', 'href' => $editRef]]);
                    if (!empty($a->children)){
                        foreach ($a->children as $c) {
                            $editRef = Request::createUrl(Article::URL_EDIT, ['path' => $model->module->path, 'n' => $c->n, 'id' => $model->shortId()]);
                            echo $table->formRow($c, ['', 'n', 'move' => $c->n, '', 'rename' => $c->article->title, 'delete' => $c->id, 'link' => ['title' => 'Edit', 'href' => $editRef]]);
                        }
                    }
                }
                Table::end();
            }
        ?>
        <h5>Add a New Article</h5>
        <?php $form = new Form('', 'create');
            echo $form->begin();
            echo $form->field($inputModel, 'title');
            echo $form->end('Create');
        ?>
        </div>
    </div>
</div>


