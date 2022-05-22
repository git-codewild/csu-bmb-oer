<?php

/** @var \codewild\csubmboer\models\UserVM $model;

 */

use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\views\admin\_adminNav;

$this->title = 'Manage Users';

?>
<div class="row">
    <div class="col-sm-2">
        <?php new _adminNav(); ?>
    </div>
    <div class="col-sm-10">
    <?php
        $usersTable = Table::begin($model[0]);
        foreach ($model as $contact){
            echo $usersTable->row($contact);
        }
        Table::end();
    ?>
    </div>
</div>

