<?php

/** @var $this \codewild\csubmboer\core\View
 *  @var $model \codewild\csubmboer\models\LoginForm 
*/

use codewild\csubmboer\core\form\Form;

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


