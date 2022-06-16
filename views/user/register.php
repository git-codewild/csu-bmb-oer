<?php

/** @var $this \codewild\phpmvc\View
 *  @var $model \codewild\csubmboer\models\User
*/

use codewild\phpmvc\form\Form;

$this->title = 'Register';

?>

<div class="col-md-4 mx-auto">
    <?php $form = new Form(); echo $form->begin() ?>
        <div class="row">
            <div class="col">
                <?php echo $form->field($model, 'firstname') ?>
            </div>
            <div class="col">
                <?php echo $form->field($model, 'lastname') ?>
            </div>
        </div>
        <?php
            echo $form->field($model, 'username');
            echo $form->field($model, 'email');
            echo $form->field($model, 'password')->setType('password');
            echo $form->field($model, 'confirmPw')->setType('password');
            echo $form->end();
        ?>
    </div>
</div>
