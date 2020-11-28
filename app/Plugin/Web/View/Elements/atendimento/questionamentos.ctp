<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('questionamentos', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('Questionamentos') . $isRequerid));
        ?>
    </div>
</div>