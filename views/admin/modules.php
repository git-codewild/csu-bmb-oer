<?php

/** @var \codewild\csubmboer\models\ModuleVersion $model
 */

use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\views\admin\_adminNav;

$this->title = 'Manage Modules';

?>
<div class="row">
    <div class="col-sm-2">
        <?php new _adminNav(); ?>
    </div>
    <div class="col-sm-10">
        <?php
            if (!empty($model)) {
                $versionsTable = Table::begin($model[0]);
                foreach ($model as $version) {
                    echo $versionsTable->row($version)->lastColumn('link', \codewild\csubmboer\core\Request::createUrl('/module/{path}/v/{id}/1', ['path' => $version->module->path, 'id' => $version->shortId()]));
                }
                Table::end();
            }
        ?>
    </div>
</div>
