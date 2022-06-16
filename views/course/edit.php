<?php

/**
 * @var \codewild\csubmboer\models\Outline $model
 * @var \codewild\csubmboer\models\Outline $inputModel
 * @var Module[] $modules
 */

use codewild\phpmvc\components\Modal;
use codewild\phpmvc\form\Form;
use codewild\phpmvc\table\Table;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\Outline;

$this->title = "$model->type $model->n: $model->title";

$addChildForm = new Form('', 'create');
$addChildModalBody = $addChildForm->begin()
    .$addChildForm->field($inputModel, 'parentId')->hiddenField()
    .$addChildForm->field($inputModel, 'title')
    .$addChildForm->end('Create');

$addChildModal = new Modal('addChildModal', 'Add Child', $addChildModalBody, '');

$selectModuleForm = new Form('', 'selectModule');
$selectModuleModalBody = $selectModuleForm->begin()
    .$selectModuleForm->field($model, 'id')
    .$selectModuleForm->selectField($modules, 'moduleId')
    .$selectModuleForm->end('Save', 'btn-primary');

$selectModuleModal = new Modal('selectModuleModal', 'Select Module', $selectModuleModalBody, '');

?>

<div class="container-xxl">
    <?php
        $table = Table::begin($model, [
            'Parent',
            'Child',
            'Move',
            'Add',
            'moduleVersionId',
            'title',
            'Delete']);
        foreach ($model->children as $m){
            echo $table->formRow($m, [
                'n' => 'n',
                '',
                'move' => $m->n,
                'addChild' => $addChildModal->setOnClick("$('#".$addChildModal->id." input:first').attr('value', '".$m->id."')"),
                'selectModule' => $selectModuleModal->setOnClick("$('#".$selectModuleModal->id." input:first').attr('value', '".$m->id."')"),
                'rename' => $m->title,
                'delete' => $m->id]);
            if (!empty($m->children)) {
                foreach($m->children as $c)
                echo $table->formRow($c, [
                    '',
                    'n' => 'n',
                    'move' => $c->n,
                    'addChild' => $addChildModal->setOnClick("$('#".$addChildModal->id." input:first').attr('value', '".$c->id."')"),
                    'selectModule' => $selectModuleModal->setOnClick("$('#".$selectModuleModal->id." input:first').attr('value', '".$c->id."')"),
                    'rename' => $c->title,
                    'delete' => $c->id]);
            }
        }
        Table::end();
    ?>

    <div class="row justify-content-center mb-4">
        <h4>Add a New Section</h4>
        <?php
            $addChildForm = new Form('', 'create');
            echo $addChildForm->begin();
            echo $addChildForm->field($inputModel, 'title');
            echo $addChildForm->end();
        ?>
    </div>
</div>

