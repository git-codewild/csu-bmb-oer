<?php

/**
 * @var \codewild\csubmboer\models\Outline $model
 */

$this->title = "$model->type $model->n: $model->title";

?>

<div class="container-md">

<?php
    echo $model->recursiveTree($model->children);
?>

</div>
