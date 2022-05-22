<?php

/** @var $this \codewild\csubmboer\core\View
 *  @var $model \codewild\csubmboer\models\ContactForm 
*/

use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\form\TextareaField;

$this->title = 'Contact Us';

?>

<div class="row justify-content-center mb-4">
    <?php
        $form = new Form();
        echo $form->begin();
        echo $form->field($model, 'subject');
        echo $form->field($model, 'email');
        echo $form->textarea($model, 'body');
        echo $form->end();
    ?>
</div>

