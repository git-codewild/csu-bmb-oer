<?php

/** @var $model Appendix */

use codewild\phpmvc\form\Form;
use codewild\csubmboer\models\Appendix;

$this->title = 'Create New Appendix';

?>

<div class="container h-100">

<?php
$inputForm = new Form('', 'createAppendix');
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



