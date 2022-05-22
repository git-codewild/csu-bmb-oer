<?php

/** @var \codewild\csubmboer\models\User $model;
 *
 */

use codewild\csubmboer\core\form\Form;

$this->title = 'Your Profile';

?>

<div class="container">
    <div class="col-lg-6 mx-auto">
        <div class="row justify-content-center mb-4">
            <h5>Edit Profile</h5>
            <?php
                $form = new Form();
                echo $form->begin()
            ?>
            <div class="row">
                <?php
                    echo $form->field($model, 'username')->disabledField();
                ?>
                <div class="col">
                    <?php echo $form->field($model, 'firstname') ?>
                </div>
                <div class="col">
                    <?php echo $form->field($model, 'lastname') ?>
                </div>
            </div>
            <?php

                echo $form->field($model, 'email');
                echo $form->end('Update Profile', 'mt-2 btn-info');
            ?>
        </div>
        <hr>
        <div class="row justify-content-center mb-4">
            <h5>Change Password</h5>
            <?php
                $form = new Form();
                echo $form->begin();
                echo $form->field($model, 'password')->setType('password');
                echo $form->field($model, 'confirmPw')->setType('password');
                echo $form->end('Update Password', 'mt-2 btn-info');
            ?>
        </div>
    </div>
</div>



