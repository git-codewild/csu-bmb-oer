<?php

/**
 * @var \codewild\csubmboer\models\ContactForm $model
 */

use codewild\phpmvc\table\Table;
use codewild\csubmboer\views\admin\_adminNav;

$this->title = 'Manage Contacts';

?>
<div class="row">
    <div class="col-sm-2">
        <?php new _adminNav(); ?>
    </div>
    <div class="col-sm-10">
    <?php
        if (!empty($model)) {
            $contactsTable = Table::begin($model[0]);
            foreach ($model as $contact) {
                echo $contactsTable->row($contact);
            }
            Table::end();
        }
    ?>
    </div>
</div>
