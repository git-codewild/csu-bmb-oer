<?php

/** @var $model Appendix[] */

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\models\Appendix;

$this->title = 'Appendix';

$isAuthor = AuthHandler::authorize(new Appendix(), 'create');

?>

<div class="container">

<?php
    if ($isAuthor) {
        echo "<a href='/appendix/create' class='link'>Create New</a>";
    }
    if (!empty($model)){
        $results = Table::begin($model[0], ['title', '']);
        foreach($model as $a){
            echo $results->row($a, ['title'])->lastColumn('link', '/appendix/'.$a->path);
        }
        Table::end();
    }
?>

</div>
