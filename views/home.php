<?php

/**
 * @var Outline[] $model
 * @var Outline $inputModel
 * */

use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\models\Outline;

$this->title = key(Outline::COURSE_BC401).': '.current(Outline::COURSE_BC401);

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
