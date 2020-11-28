<?php
echo $this->PForm->hidden('relatorio_atendimentos_totais', RELATORIO_ATENDIMENTOS_TOTAIS, 'relatorio_tipo');
echo $this->PForm->hidden('relatorio_agrupados', RELATORIO_AGRUPADOS, 'relatorio_tipo');
echo $this->PForm->hidden('relatorio_processos_publicados', RELATORIO_PROCESSOS_PUBLICADOS, 'relatorio_tipo');
echo $this->PForm->hidden('relatorio_atendimentos_por_perito', RELATORIO_ATENDIMENTOS_POR_PERITO, 'relatorio_tipo');
echo $this->PForm->hidden('relatorio_dias_de_licenca', RELATORIO_DIAS_DE_LICENCA, 'relatorio_tipo');

echo $this->PForm->hidden('relatorio_agrupados_por_municipio', RELATORIO_AGRUPADOS_POR_MUNICIPIO, 'relatorio_agrupamento');
echo $this->PForm->hidden('relatorio_agrupados_por_secretaria', RELATORIO_AGRUPADOS_POR_SECRETARIA, 'relatorio_agrupamento');
echo $this->PForm->hidden('relatorio_agrupados_por_cid', RELATORIO_AGRUPADOS_POR_CID, 'relatorio_agrupamento');


?>

<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">
            <header class="panel-heading">
                <div class="row">
                    <div class="col-md-10">
                        <div class="panel-title">Relatório</div>
                    </div>
                </div>
            </header>
            <div class="panel-body">
                <?=
                $this->Form->create('Relatorio', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'type' => 'post',
                    'data-acao' => 'relatorio',
                    'id' => 'formularioRelatorio',
                    'url' => array('controller' => 'Relatorio', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('processo_label_periodo') ?></legend>

                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_inicial', array('maxlength' => 150, 'label' => __('processo_label_de')."*", 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData',
                                        'required' => true));
                                    ?>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_final', array('maxlength' => 150, 'label' => __('processo_label_ate'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData inputSemLabel'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 form-group">
                        <?php
                        echo
                        $this->Form->input('tipo_relatorio', array('div' => array('class' => 'form-group'),
                            'id' => 'tipo_relatorio',
                            'class' => 'form-control',
                            'options' => $tipo_relatorios,
                            'label' => __('Tipo*'),
                            'empty' => __('label_selecione'),
                            'required'=>true));
                        ?>
                    </div>
                    <div class="col-md-5 form-group displayNone" id="div-tipo_agrupamento">
                        <?php
                        echo
                        $this->Form->input('tipo_agrupamento', array('div' => array('class' => 'form-group'),
                            'id' => 'tipo_agrupamento',
                            'class' => 'form-control',
                            'options' => $tipo_agrupamento,
                            'label' => __('Agrupado por*'),
                            'empty' => __('label_selecione')
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div id="div-tipologia_id" class="col-md-6 form-group displayNone">
                        <?php
                        echo
                        $this->Form->input('tipologia_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $tipologias,
                            'label' => __('atendimentos_pendentes_label_tipologia'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                </div>

                <?php
                echo
                $this->Form->input('impressao', array(
                    'div' => false,
                    'type' => 'hidden',
                    'value' => '0'));
                ?>

                <?= $this->element('botoes-relatorio'); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel" id="grid">
        </section>
    </div>
</div>

<?php 
        // echo $this->Html->script('Admin.Chart', array('block' => 'script')); 
        // echo $this->Html->script('Admin.relatorios', array('block' => 'script')); 
        // echo $this->Html->script('Admin.jspdf.min', array('block' => 'script')); 
        // echo $this->Html->script('Admin.html2canvas', array('block' => 'script')); 

        echo $this->Html->script('Web.Chart', array('block' => 'script')); 
        echo $this->Html->script('Web.relatorios', array('block' => 'script')); 
        echo $this->Html->script('Web.jspdf.min', array('block' => 'script')); 
        echo $this->Html->script('Web.html2canvas', array('block' => 'script')); 

?>


