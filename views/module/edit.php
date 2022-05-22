<?php

/**
 * @var \codewild\csubmboer\models\Module $model
 * @var \codewild\csubmboer\models\Article $inputModel
 */

use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\lists\DescriptionList;
use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\models\Article;

$this->title = 'Edit Module: '.$model->title;

?>

<div class="container">

    <div class="col-lg-4 col-md-6 mx-auto">
        <?php
            echo DescriptionList::create($model, ['path']);
            $updateForm = new Form('', 'update');
            echo $updateForm->begin();
            echo $updateForm->field($inputModel, 'title');
            echo $updateForm->field($inputModel, 'subtitle');
            echo $updateForm->field($inputModel, 'keywords');
            echo $updateForm->end('Save', 'btn-info mt-2');

            echo "<hr>";
            $deleteForm = new Form('', 'delete');
            echo $deleteForm->begin('mt-4');
            echo $deleteForm->field($model, 'id')->hiddenField();
            echo $deleteForm->end('Delete this module', 'btn-danger');

        ?>
    </div>
</div>
