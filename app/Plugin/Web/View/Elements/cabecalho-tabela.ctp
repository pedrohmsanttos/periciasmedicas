<header class="panel-heading"> <?php echo isset($cabecalho) ? __('cabecalho_grid', $cabecalho) : __('Resultados') ?>
</header>
<?php
$idCampoLimite = !isset($idLimiteConsulta) ? 'registros_pagina' : $idLimiteConsulta;
?>
<div class="row">
    <div class="col-lg-6">
        <div id="editable-sample_length" class="dataTables_length">
            <label style="float:left"> <?php
$arrTipo = array(
    '10' => '10',
    '25' => '25',
    '50' => '50'
);

echo $this->Form->input ( 'limiteConsultaSelecionado', array (
'options' => $arrTipo,
 'id' => $idCampoLimite,
 'label' => false,
 'class' => 'form-control small ',
 'aria-controls' => 'editable-sample',
 'value' => $limiteConsultaSelecionado
) );
?> </label>
            <label class="estiloTamanhoPaginacao" style="float:left;margin-left:10px;">  registros por página.</label>
        </div>
    </div>
    <div class="col-lg-6"></div>
</div>
