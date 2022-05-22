<?php

namespace codewild\csubmboer\models;

use codewild\csubmboer\core\components\BaseCard;
use codewild\csubmboer\core\components\Modal;
use codewild\csubmboer\core\components\TabList;
use codewild\csubmboer\core\form\Form;

class JSmolCard extends BaseCard
{

    public TabList $tablist;
    public DataFile $inputModel;

    public function __construct(Script_JSmol $model, DataFile $inputModel, array $classes = [])
    {
        $this->tablist = new TabList();
        $this->inputModel = $inputModel;
        parent::__construct($model, $classes);
    }

    public function renderHeader(): string
    {
        $form = new Form('', 'deleteSlide');
        $formBody = $form->begin('').$form->field($this->model, 'id')->hiddenField().$form->end('&times;', 'btn-outline-danger');

        return $this->tablist->begin('scriptTab', 'nav-tabs flex-column flex-sm-row')."
            <li class='nav-item flex-sm-grow-0' role='presentation'>
                <button class='nav-link' id='appletTab' data-bs-toggle='tab' data-bs-target='#applet' type='button' role='tab' aria-controls='applet'>Applet</button>
            </li>
            <li class='nav-item flex-sm-grow-0' role='presentation'>
                <button class='nav-link' id='scriptEditorTab' data-bs-toggle='tab' data-bs-target='#scriptEditor' type='button' role='tab' aria-controls='scriptEditor'>Editor</button>
            </li>
            <li class='nav-item flex-sm-grow-1 text-end'>$formBody</li>
            
        ";

    }

    public function renderBody(): string
    {
        $createFileForm = new Form('multipart', 'createDataFile');
        $createFileModalBody = $createFileForm->begin().
            $createFileForm->field($this->model, 'id')->hiddenField().
            $createFileForm->field($this->inputModel, 'title').
            $createFileForm->radioInputGroup($this->inputModel, 'name')->setType('file').
            $createFileForm->radioInputGroup($this->inputModel,'path').
            $createFileForm->end();
        $createFileModalFooter = "<button class='btn btn-primary' data-bs-target='#editFileModal' data-bs-toggle='modal'>Back to models</button>";
        $createFileModal = new Modal('createFileModal', 'Upload Model', $createFileModalBody, $createFileModalFooter);

        $inlineFileForms = '';
        foreach ($this->model->dataNavs as $dataNav){
            $n = $dataNav->n;
            $renameForm = new Form('', 'renameDataFile');
            $deleteForm = new Form('', 'deleteDataFile');
            $inlineFileForms .= "<div class='row row-cols-3 g-3 align-items-center mb-2'>";
            $inlineFileForms .= $renameForm->begin('col-6').
                $renameForm->field($dataNav->file, 'id')->hiddenField().
                "<div class='input-group'>
                    <label class='visually-hidden' for='model$n'>Model $n</label>
                    <div class='input-group'>
                        <div class='input-group-text'>$n</div>
                        <input type='text' class='form-control disabled' id='model$n' value='".$dataNav->file->title."' placeholder='Model $n'>
                        <button type='submit' class='btn btn-primary' name='renameDataFile'>Rename</button>
                    </div>
                </div>
                </form>"
            ;
            $inlineFileForms .= $deleteForm->begin('col-6').
                $deleteForm->field($dataNav, 'dataFileId')->hiddenField().
                $deleteForm->field($dataNav, 'scriptId')->hiddenField().
                $deleteForm->end('&times;', 'btn-outline-danger');
            $inlineFileForms .= "</div>";
        }

        $appendDataFile = new Form('', 'appendDataFile');
        $editFileModalBody =
            $inlineFileForms.
            $appendDataFile->begin().
            $appendDataFile->field($this->model, 'id')->hiddenField().
            $appendDataFile->selectField($this->inputModel, 'dataFileId').
            $appendDataFile->end('Append');

        $editFileModal = new Modal('editFileModal', 'Edit Models', $editFileModalBody, $createFileModal);

        $form = new Form('', 'scriptEdit');

        return $this->tablist->contentStart('scriptTabContent', 'h-100')."
            <div class='tab-pane fade h-100' id='applet' role='tabpanel' aria-labelledby='appletTab'>
                <object id='jsmolApplet' class='h-100 w-100'></object>
            </div>
            <div class='tab-pane fade h-100' id='scriptEditor' role='tabpanel' aria-labelledby='scriptEditorTab'>".
                $form->begin('h-100')."
                    <label>Models</label>
                    <div class='input-group'>
                        <input type='text' name='' class='form-control' value='".$this->model->filesList."' disabled>".
                        $editFileModal
                    ."</div>".
                    $form->field($this->model, 'id')->hiddenField().
                    $form->field($this->model, 'title').
                    $form->textarea($this->model, 'vars').
                    $form->textarea($this->model, 'config').
                    $form->textarea($this->model, 'display').
                    $form->textarea($this->model, 'labels').
                    $form->textarea($this->model, 'camera').
                    $form->textarea($this->model, 'functions').
                    $form->end()."
            </div>
        </div>";
    }

    public function renderFooter(): string
    {
        return '';
    }
}
