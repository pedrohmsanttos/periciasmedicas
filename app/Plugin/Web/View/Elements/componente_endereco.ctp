<?php

if (!isset($model)) {
    $model = 'Endereco';
}

if (!isset($requerid)) {
    $requerid = $isRequerid;
}

if(isset($formDisabledEndereco)){
    $formDisabled = $formDisabledEndereco;
}else{
    $formDisabledEndereco = "";
}    

echo $this->Form->hidden($model . '.id');
?>
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?= __('componente_endereco_label_endereco') ?></legend>
    <div class="row">
        <div class="col-md-2 form-group">
            <?php
            echo $this->Form->input($model . '.cep', array('div' => array('class' => 'form-group'),
                'class' => 'form-control cep',
                'label' => __('componente_endereco_label_cep') . $requerid,
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-10 form-group">
            <?php
            echo $this->Form->input($model . '.logradouro', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'label' => __('componente_endereco_label_logradouro') . $requerid,
                'disabled' => $formDisabled));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 form-group">
            <?php
            echo $this->Form->input($model . '.numero', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'maxlength' => 10,
                'label' => __('componente_endereco_label_numero') . $requerid,
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-6 form-group">
            <?php
            echo $this->Form->input($model . '.complemento', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'label' => __('componente_endereco_label_complemento'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-4 form-group">
            <?php
            echo $this->Form->input($model . '.bairro', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'label' => __('componente_endereco_label_bairro') . $requerid,
                'disabled' => $formDisabled));
            ?>
        </div>

    </div>
    <?php echo $this->element('componente_uf_municipio', ['model' => $model, 'requerid' => $requerid, 'idComboMunicipio' => $idComboMunicipio, 'municipios' => $municipios, 'formDisabledEndereco' => $formDisabledEndereco]); ?>
</fieldset>