<?php
App::uses('AppHelper', 'View/Helper');

class PFormHelper extends AppHelper {
    public $helpers = array('Form');

    public function radio($arrPar) {
        $title = strval(isset($arrPar['title'])?$arrPar['title']:'');
        $name = strval(isset($arrPar['name'])?$arrPar['name']:'');
        $options = isset($arrPar['options'])?$arrPar['options']:array(''=>'');
        $disabled = boolval(isset($arrPar['disabled'])?$arrPar['disabled']:false);
        $column = intval(isset($arrPar['column'])?$arrPar['column']:1);
        $column = ($column<=0)?1:(($column>=12)?12:$column);
        $class = isset($arrPar['class'])?$arrPar['class']:'';

        $attribute = array('legend'=>false, 'separator'=> " </div><div class='radio-item'>", 'disabled' => $disabled);

        return '<div class="col-md-'.$column.'"><div class="form-group '.$class.'"><label>'.$title.'</label><br><div class="radio-item">'
            .$this->Form->radio($name, $options, $attribute).'</div></div></div>';
    }

    public function checkbox($arrPar, $html = '') {
        $title = strval(isset($arrPar['title'])?$arrPar['title']:'');
        $name = strval(isset($arrPar['name'])?$arrPar['name']:'');
        $options = isset($arrPar['options'])?$arrPar['options']:array(''=>'');
        $disabled = boolval(isset($arrPar['disabled'])?$arrPar['disabled']:false);

        $column = intval(isset($arrPar['column'])?$arrPar['column']:1);
        $column = ($column<=0)?1:(($column>=12)?12:$column);
        $class = isset($arrPar['class'])?$arrPar['class']:'';

        $attributes = array(
            'legend'=>false,
            'disabled' => $disabled,
            'multiple'=> 'checkbox',
            'hiddenField' => false,
            'class' => 'checkbox margin5'
        );
        if (isset($arrPar['selected']))$attributes['selected']=$arrPar['selected'];
        if (isset($arrPar['id']))$attributes['id']=$arrPar['id'];

        return
            '<div class="col-md-'.$column.' '.$class.'">
                <label style="float:left; margin: 8px 12px 0 5px;">'.$title.'</label>
                '.$this->Form->select($name, $options, $attributes).
            '</div>';
    }

    public function radioYN($arrPar){
        $options = array( '1' => 'Sim', '0' => 'Não');
        $arrPar['options'] = $options;
        $arrPar['column'] = isset($arrPar['column'])?$arrPar['column']:5;
        return $this->radio($arrPar);
    }

    public function text($arrPar){
        $title = strval(isset($arrPar['title'])?$arrPar['title']:'');
        $name = strval(isset($arrPar['name'])?$arrPar['name']:'');
        $disabled = boolval(isset($arrPar['disabled'])?$arrPar['disabled']:false);
        $column = intval(isset($arrPar['column'])?$arrPar['column']:1);
        $column = ($column<=0)?1:(($column>=12)?12:$column);

        return '<div class="col-md-'.$column.'">'.$this->Form->input($name, array('div' => array('class' => 'form-group'),
            'class' => 'form-control '.$name,
            'type' => 'text',
            'label' => $title,
            'disabled' => $disabled)).'</div>';
    }

    public function radioYND($arrPar, $col1=5, $col2=7, $classMod=''){
        $html = '<div class="row row-item'.$classMod.'" >';
        $arrPar['column'] = $col1;
        $html .= $this->radioYN($arrPar);
        $arrPar['column'] = $col2;
        $arrPar['title'] = 'Detalhe';
        $arrPar['name'] = $arrPar['name'].'_desc';
        return $html . $this->text($arrPar).'</div>';
    }

    public function radioYNDT($arrPar, $col1=5, $col2=7){
        return $this->radioYND($arrPar, $col1, $col2, '-top');
    }
    public function radioYNDM($arrPar, $col1=5, $col2=7){
        return $this->radioYND($arrPar, $col1, $col2, '-mid');
    }
    public function radioYNDB($arrPar, $col1=5, $col2=7){
        return $this->radioYND($arrPar, $col1, $col2, '-bottom');
    }

    public function hidden($name, $value, $class='global'){
        return $this->Form->input($name, array(
            'type' => 'hidden',
            'id' => $name,
            'value' => $value,
            'class' => 'hid_'.$class,
            'disabled' => true
        ));
    }
}