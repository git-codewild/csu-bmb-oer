<?php

/**
 * @var \codewild\csubmboer\models\Outline $model
 */

$this->title = "$model->type $model->n: $model->title";

?>

<div class="container">
    <div class="col-md-4 mx-auto">

<?php
    echo $model->chapterList();
?>
    </div>
</div>
