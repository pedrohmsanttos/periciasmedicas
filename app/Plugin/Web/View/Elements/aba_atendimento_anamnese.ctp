<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('historico_doenca_atual', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_historico_doenca_atual')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('antecedentes_pessoais_familiares', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_antecedentes_pessoais_familiares')));
        ?>
    </div>
</div>