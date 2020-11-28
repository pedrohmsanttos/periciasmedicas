<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('PreAdmissional.pne_necessidade_especial', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'text',
            'label' => 'Necessidade Especial'));
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('PreAdmissional.pne_analise_pericial', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'rows' => 10,
            'disabled' => $formDisabled,
            'type' => 'textarea',
            'onkeyup' => "limitarTamanho(this,2500);",
            'onblur' => "limitarTamanho(this,2500);",
            'label' => 'Análise Pericial'));
        ?>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('PreAdmissional.pne_resultado', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'text',
            'label' => 'Resultado'));
        ?>
    </div>
</div>
