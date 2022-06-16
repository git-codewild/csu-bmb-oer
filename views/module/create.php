<?php

/**
 * @var \codewild\csubmboer\models\Module $model
 * */

use codewild\phpmvc\form\Form;

$this->title = 'Create Module';

?>

<div class="container">
    <div class="col-md-6 col-lg-4 mx-auto">
    <?php
        $form = new Form();
        echo $form->begin();
        echo $form->field($model, 'path');
        echo $form->field($model, 'title');
        echo $form->field($model, 'subtitle');
        echo $form->field($model, 'keywords');
        echo $form->end();
    ?>
    </div>
</div>

