<?php

/**
 * @var \codewild\csubmboer\models\Outline[] $model
 * @var \codewild\csubmboer\models\Outline $inputModel
 * */

use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\table\Table;

$this->title = 'Table of Contents';

?>

<div class="container-md">

<?php
    if(!empty($model)) {
        $table = Table::begin($model[0], ['n', 'title', '']);
        foreach ($model as $m) {
            echo $table->row($m, ['n' => 'Chapter ' . $m->n, 'title' => 'title'])->lastColumn('link', '/ch' . $m->n);
        }
        Table::end();
    }
    if (\codewild\csubmboer\authorization\AuthHandler::authorize($inputModel, 'create')):
?>
    <div class="row justify-content-center mb-4">
        <h4>Add a New Chapter</h4>
        <?php
            $form = new Form();
            echo $form->begin();
            echo $form->field($inputModel, 'title');
            echo $form->end();
        ?>
    </div>
    <?php endif; ?>
</div>
