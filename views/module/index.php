<?php

/**
 * @var ModuleVersion[] $model
 * */

use codewild\csubmboer\core\Request;
use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\ModuleVersion;

$this->title = 'Modules';

$isAuthorized = AuthHandler::authorize(new Module(), 'create');

?>

<div class="container-md">

<?php
    if ($isAuthorized){
        echo '<a href="modules/create" class="link">Create New</a>';
    }

    if (!empty($model)) {
        $table = Table::begin(current($model), ['Title', 'Subtitle', 'Keywords', 'status', 'created_at',  ''], 'table-secondary table-striped table-hover');
        foreach ($model as $version) {
            $path = Request::createUrl('/module/{path}', ['path' => $version->module->path]);
            echo $table->row($version->module, ['title', 'subtitle', 'keywords', $version->status, $version->created_at])->lastColumn('link', $path);
        }
        Table::end();
    } else {
        echo "<div class='alert alert-info'>No modules could be found!</div>";
    }
?>

</div>

