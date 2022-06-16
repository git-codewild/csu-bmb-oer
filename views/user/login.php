<?php

/** @var $this \codewild\phpmvc\View
 *  @var $model \codewild\csubmboer\models\LoginForm 
*/

use codewild\phpmvc\form\Form;

$this->title = 'Log In';

?>

<div class="col-sm-3 mx-auto">
    <div class="row justify-content-center mb-4">
        <?php
            $form = new Form();
            echo $form->begin();
            echo $form->field($model, 'uniqueId');
            echo $form->field($model, 'password')->setType('password');
            echo $form->end('Log In', 'mt-2 btn-info');
        ?>
    </div>
</div>


