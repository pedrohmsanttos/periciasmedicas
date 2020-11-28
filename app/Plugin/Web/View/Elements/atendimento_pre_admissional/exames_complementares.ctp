<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('diagnostico', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'rows' => 30, 
            'disabled' => $formDisabled,
            'type' => 'textarea',
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => '- especificar apenas resultados significativos -'));
        ?>
    </div>
</div>