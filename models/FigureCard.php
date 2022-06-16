<?php

namespace codewild\csubmboer\models;

use codewild\phpmvc\form\Form;

class FigureCard
{
    public Figure $model;
    public bool $isEditable = false;

    public function __construct(Figure $model)
    {
        $this->model = $model;
    }

    public function __toString(){
        return sprintf("
        <figure class='card'>
            <div class='card-header'>
                %s
            </div>
            <div class='card-body'>
                %s
            </div>
            <figcaption class='card-footer'>
                %s
            </figcaption>
        </figure>",
            $this->renderHeader(),
            $this->renderBody(),
            $this->renderFooter(),
        );
    }

    public function renderHeader(): string
    {
        $str = "<h5 class='float-start'>".$this->model->title."</h5>";

        if ($this->isEditable){
            $form = new Form('', 'editFigure');
            $str = sprintf("
            <h5 class='float-start'>
            %s
            </h5>
            <div class='float-end'>
                <form class='form-inline' action='' method='POST'>
                    <input hidden name='resourceId' value=".$this->model->id." />
                    <input type='submit' name='deleteSlide' value='&times;' />
                </form>
            </div>",
            $form->begin()
                .$form->field($this->model, 'id')->hiddenField()
                .$form->inputGroup($this->model, 'title')
                .'</form>'
            );
        }
        return $str;

    }

    public function renderBody(): string
    {
        return "<img src='".$this->model->image->path."' class='w-100'>";
    }

    public function renderFooter(): string
    {
        $str = $this->model->caption;
        if ($this->isEditable){
            $form = new Form('', 'editFigure');
            $str = $form->begin()
                .$form->field($this->model, 'id')->hiddenField()
                .$form->textarea($this->model, 'caption')
                .$form->end();
        }
        return $str;
    }

    public function editable()
    {
        $this->isEditable = true;
        return $this;
    }
}
