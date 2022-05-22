<?php

namespace codewild\csubmboer\core\form;

class SelectField extends BaseField
{
    public ?string $id = null;

    public function renderInput(): string{
        return sprintf('<select%sname="%s" class="form-select%s%s" aria-label="">%s</select>',
            !is_null($this->id) ? " id='$this->id' " : ' ',
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            array_key_exists('textarea', $this->classes) ? ' '.$this->classes['textarea'] : '',
            $this->renderOptions()
        );
    }

    public function renderOptions(){
        $output = "<option selected>Select below...</option>";
        $options = $this->model::findAll();
        foreach ($options as $option){
            $output.= sprintf("<option value='%s'>%s</option>"
                ,$option->id, $option->title);
        }
        return $output;
    }

    public function id(string $id){
        $this->id = $id;
        return $this;
    }
}
