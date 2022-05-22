<?php

/** @var $model \codewild\csubmboer\models\Appendix */

use codewild\csubmboer\core\form\Form;

$this->title = $model->title.' - Edit';

?>

<div class="container">

<?php
    $inputForm = new Form('', 'editAppendix');
    echo $inputForm->begin('d-flex flex-column h-100');
    echo $inputForm->field($model, 'title');
    echo $inputForm->textarea($model, 'html', ['div' => 'd-flex flex-column flex-grow-1', 'textarea' => 'flex-grow-1 overflow-auto'])->id('editor');
    echo $inputForm->end('Create', 'btn-warning');
?>

</div>

<?php
array_push($this->scripts,
    '<script src="/lib/ckeditor5/ckeditor.js"></script>')
?>
