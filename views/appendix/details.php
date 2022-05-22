<?php

/** @var $model Appendix */

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\models\Appendix;

$this->title = $model->title;

$isAuthorized = AuthHandler::authorize($model, 'update');

?>

<div class="container">
    <?php
        if ($isAuthorized){
            echo "
                <a class='btn btn-outline-primary' role='link' href='/appendix/$model->path/edit'>Edit</a>
                <hr>
            ";
        }
        echo html_entity_decode($model->html);
    ?>
</div>


