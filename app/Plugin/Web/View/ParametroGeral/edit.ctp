<?php
$formCreate['class'] = ($formDisabled) ? "formVisualizacao" : "";
echo $this->Form->create($controller, $formCreate);
?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_inserir', __('ParametroGeral')))); ?>
            <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
            <header class="panel-heading tab-bg-dark-navy-blue">
                <ul class="nav nav-tabs nav-justified ">
                    <li class="active">
                        <a data-toggle="tab" href="#consultas">
                            <?php echo __('parametro_geral_consulta') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#textos-parecer">
                            <?php echo __('parametro_geral_textos_parecer_tecnico') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#config-interna">
                            <?php echo __('parametro_geral_config_interna') ?>
                        </a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
                    <div id="consultas" class="tab-pane active">
                        <div class="row">
                            <?= $this->element('aba-parametro-geral-consulta'); ?>
                        </div>
                    </div>
                    <div id="textos-parecer" class="tab-pane">
                        <div class="row">
                            <?= $this->element('aba-parametro-geral-textos-parecer'); ?>
                        </div>
                    </div>
                    <div id="config-interna" class="tab-pane">
                        <div class="row">
                            <?= $this->element('aba-parametro-geral-config-interna'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <div class="panel-body">
                <?=
                $this->Form->button(__('bt_salvar'), array(
                    'class' => 'btn float-right fa fa-save estiloBotao btn-success',
                    'value' => 'true',
                    'name' => 'salvarButton',
                    'id' => 'salvarButton'
                ));
                ?>
            </div>
        </section>
    </div>
</div>

<script>

$("#salvarButton").click(function(e) {
    var X = 5;

    var numero = parseInt($("#ParametroGeralTempoConsulta").val());
    if (!isNaN(numero)) {
        if (numero % X === 0) {
            $("#formBody").submit();
        } else {
            e.preventDefault();
            gerarMensagem('O Tempo da consulta só pode ser múltiplo de 5.', 'danger');
        }
    } else {
            e.preventDefault();
            gerarMensagem('Entrada não é um número.', 'danger');
        }
});
</script>

<?= $this->Form->end(); ?>