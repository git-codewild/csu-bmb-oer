<?php

/**
 * @var \codewild\csubmboer\models\Image[] $model
 */

use codewild\phpmvc\table\Table;
use codewild\csubmboer\views\admin\_adminNav;

$this->title = 'Manage Images';

?>

<div class="row">
    <div class="col-sm-2">
        <?php new _adminNav(); ?>
    </div>
    <div class="col-sm-10">
        <?php
            if (!empty($model)): ?>
        <h5>Database Images</h5>
        <?php
            $imagesTable = Table::begin($model[0]);
            foreach ($model as $image) {
                echo $imagesTable->row($image);
            }
            Table::end();
            endif;
        ?>
    </div>
</div>
