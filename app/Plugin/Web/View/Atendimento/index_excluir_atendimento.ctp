<?php
echo $this->Form->input('baseUrlDefault', array(
    'type' => 'hidden',
    'id' => 'baseUrlDefault',
    // 'data-url' => Router::url('/admin/Agendamento/', true),
    'data-url' => Router::url('/web/Atendimento/', true),
    'disabled' => true
)); ?>

<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">
            <header class="panel-heading">
                <div class="row">
                    <div class="col-md-10">
                        <div class="panel-title"><?= __('processo_titulo') ?></div>
                    </div>
                </div>
            </header>
            <div class="panel-body">
                <?=
                $this->Form->create('Atendimento', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'type' => 'get',
                    'id' => 'formularioConsulta',
                    'data-acao' => 'consultarProcessos',
                    'url' => array('controller' => 'Atendimento', 'action' => 'consultarExcluirAtendimento')
                ));
                ?>
                <div class="displayNone">
                    <?php
                    echo $this->Form->select("procesos_selecionados", array(), array('multiple' => true, 'id' => 'procesos_selecionados'));
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('processo_label_periodo') ?></legend>

                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_inicial', array('maxlength' => 150, 'label' => __('processo_label_de'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
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
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('tipologia_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $tipologias,
                            'label' => __('atendimentos_pendentes_label_tipologia'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?php
                        echo
                        $this->Form->input('tipo_situacao', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $situacoes,
                            'label' => __('Status Atendimento'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>
<style>
    .zTop{z-index:999}
</style>
<div id="dialog-exclusao" class="displayNone zTop" >
    <p>Deseja realmente excluir o atendimento <span id="excluirId" ></span> ?</p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="input-group" style="margin-bottom: 5px">
                <input class="inputsExclusao" type="hidden" id="idSolicitante" />
                <span class="input-group-addon"><span style="font-weight: bold">CPF do Solicitante *</span></span>
                <input id="cpfSolicitante" type="text" class="form-control inputsExclusao" placeholder="CPF do solicitante">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><span style="font-weight: bold">Solicitante</span></span>
                <input id="nomeSolicitante" type="text" class="form-control inputsExclusao" placeholder="Nome do solicitante" readonly />
            </div><br>
            <div class="row" style="margin: 0px 1%;">
                <label for="motivoExclusao">Motivo *</label>
                <textarea id="motivoExclusao" class="form-control inputsExclusao" rows="3" style="resize: none;"></textarea>
            </div>
        </div>
    </div>

</div>
<? echo $this->Html->script('Web.index_excluir_atendimento', array('block' => 'script')); ?>
