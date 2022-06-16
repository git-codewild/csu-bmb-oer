<?php

/** @var $this \codewild\phpmvc\View
 *  @var $model \codewild\csubmboer\models\ContactForm
*/

use codewild\phpmvc\form\Form;
use codewild\phpmvc\form\TextareaField;

$this->title = 'Contact Us';

?>
<div class="container">
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
</div>

