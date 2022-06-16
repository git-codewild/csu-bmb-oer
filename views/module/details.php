<?php

/**
 * @var \codewild\csubmboer\models\Module $model
 * @var ModuleVersion $version
 * @var string $articleRef
 * @var Outline $chapter
 *
 * */

use codewild\csubmboer\authorization\AuthHandler;
use codewild\phpmvc\components\Modal;
use codewild\phpmvc\form\Form;
use codewild\phpmvc\lists\DescriptionList;
use codewild\phpmvc\table\Table;
use codewild\csubmboer\models\ModuleVersion;
use codewild\csubmboer\views\article\_articleNav;

$this->title = is_null($chapter) ? $model->title :
    "<div class='row flex-row-reverse align-items-center'>
        <div class='col text-start'>$model->title</div>
        <div class='col flex-grow-0'> | </div>
        <div class='col text-end'><a href='/ch$chapter->n' class='link-secondary'>Chapter $chapter->n: $chapter->title</a></div>
        
    </div>";

$isAuthorizedCreate = AuthHandler::authorize(new ModuleVersion($model->id), 'create');
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
                    if ($version->status === ModuleVersion::STATUS_APPROVED && $isAuthorizedCreate) {
                        $forkForm = new Form('', 'forkVersion');
                        echo $forkForm->begin();
                        echo $forkForm->end('Fork this version', 'btn-info mt-2');
                    }
                    if ($isAuthorizedVersionDelete) {
                        $deleteForm = new Form('', 'deleteVersion');
                        echo $deleteForm->begin();
                        $deleteModal = new Modal('deleteModal', 'Delete Version', 'Are you sure?', $deleteForm->end('Confirm delete', 'btn-danger'));
                        $deleteModal->setClasses('btn-danger mt-2');
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




