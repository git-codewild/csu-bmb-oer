<?php

/**
 * @var \codewild\csubmboer\models\Module $model
 * @var ModuleVersion $version
 * @var string $articleRef
 *
 * */

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\components\Modal;
use codewild\csubmboer\core\form\Form;
use codewild\csubmboer\core\lists\DescriptionList;
use codewild\csubmboer\core\table\Table;
use codewild\csubmboer\models\ModuleVersion;
use codewild\csubmboer\views\article\_articleNav;

$this->title = $model->title;

$isAuthorizedModule = AuthHandler::authorize($model, 'update');
$isAuthorizedVersionUpdate = AuthHandler::authorize($version, 'update');
$isAuthorizedVersionDelete = AuthHandler::authorize($version, 'delete');

?>

<div class="row">
    <div class="col-sm-2">
        <?php

        new _articleNav($version, $articleRef);

        if (AuthHandler::authorize($version, 'approve')){
            $approveForm = new Form();
            echo $approveForm->begin();
            echo $approveForm->field($version, 'id')->hiddenField();
            echo "
                    <button type='submit' name='approve' class='btn btn-success'>Approve</button>
                    <button type='submit' name='reject' class='btn btn-warning'>Reject</button>
                    </form>
                ";
        }
        ?>
    </div>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-sm-5">
                <?php
                    echo DescriptionList::create($model);
                    if ($isAuthorizedModule) {
                        echo "<a href='/module/$model->path/edit' class='link'>Edit Module</a>";
                    }
                ?>
            </div>
            <div class="col-sm-5">
                <h5>This Version</h5>
                <?php
                    $shortId = $version->shortId();
                    echo DescriptionList::create($version, ['status', 'created_at', 'createdBy_displayName', 'updated_at', 'updatedBy_displayName']);
                    if ($isAuthorizedVersionUpdate) {
                        echo "<a href='/module/$model->path/v/$shortId/edit' class='link'>Edit Version</a>";
                    }
                    if ($version->status === ModuleVersion::STATUS_APPROVED) {
                        $forkForm = new Form('', 'forkVersion');
                        echo $forkForm->begin();
                        echo $forkForm->end('Fork this version', 'btn-info');
                    }
                    if ($isAuthorizedVersionDelete) {
                        $deleteForm = new Form('', 'deleteVersion');
                        echo $deleteForm->begin();
                        $deleteModal = new Modal('deleteModal', 'Delete Version', 'Are you sure?', $deleteForm->end('Confirm delete', 'btn-danger'));
                        $deleteModal->setClasses('btn-danger');
                        echo $deleteModal;
                    }

                ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-10">
                <h5>Version History</h5>
                <?php
                $versionsTable = Table::begin($version, ['status', 'created_at', 'created_by', 'updated_at', 'updated_by', '']);
                foreach ($model->versions as $v) {
                    echo $versionsTable->row($v, ['status', 'created_at', 'created_by', 'updated_at', 'updated_by'])->lastColumn('link', $v->url);
                }
                echo Table::end();
                ?>
            </div>
        </div>
    </div>
</div>




