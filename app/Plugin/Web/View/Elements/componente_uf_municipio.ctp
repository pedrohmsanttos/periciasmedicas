<?php
if (!isset($model)) {
    $model = 'Endereco';
}

if (!isset($requerid)) {
    $requerid = $isRequerid;
}

if(isset($formDisabledEndereco)){
    $formDisabled = $formDisabledEndereco;
}    

?>
<div class="row">
    <div class="col-md-2 form-group">
        <?php
        echo
        $this->Form->input($model . '.estado_id', array('div' => array('class' => 'form-group'),
            'class' => 'form-control comboEstado',
            'label' => __('componente_endereco_label_estado') . $requerid,
            'disabled' => $formDisabled,
            'options' => $estados,
            'municipio_id' => $idComboMunicipio,
            'data-url' => $this->Html->url(array('action' => 'listarMunicipiosEstado', 'controller' => 'municipio'), false),
            'empty' => __('label_selecione')));
        ?>
    </div>
    <div class="col-md-3 form-group">
        <?php
        echo
        $this->Form->input($model . '.municipio_id', array('div' => array('class' => 'form-group'),
            'class' => 'form-control comboMunicipio',
            'label' => __('componente_endereco_label_municipio') . $requerid,
            'disabled' => $formDisabled,
            'id' => $idComboMunicipio,
            'options' => isset($municipios)? $municipios : array(),
            'empty' => __('label_selecione')));
        ?>
    </div>
</div>
<?php
// echo $this->Html->script('Admin.componente_uf_municipio', array('block' => 'script'));
echo $this->Html->script('Web.componente_uf_municipio', array('block' => 'script'));
?>