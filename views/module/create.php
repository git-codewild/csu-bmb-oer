<?php

/**
 * @var \codewild\csubmboer\models\Module $model
 * */

use codewild\csubmboer\core\form\Form;

$this->title = 'Create Module';

?>

<?php
    $form = new Form();
    echo $form->begin();
    echo $form->field($model, 'path');
    echo $form->field($model, 'title');
    echo $form->field($model, 'subtitle');
    echo $form->field($model, 'keywords');
    echo $form->end();
?>

