<?php
$tipologiaId = key($tipologias);
echo $this->Form->hidden('dataAtual', array('id' => 'inputDataAtual', 'value' => date("Y-m-d")));
echo $this->Form->input('validacao_data_limite_exigencia', array('label' => false, 'class' => 'displayNone'));

$urlIntervaloDatas = Router::url(array('controller' => "Atendimento", 'action' => 'obterInternaloEntreDatas'), true);
?>
<div class="row">
    <div class="col-md-2">
        <?php
        $situacoes = array(TipoSituacaoParecerTecnico::APTO=>'Sim', TipoSituacaoParecerTecnico::NAO_APTO=>'Não');

        echo
        $this->Form->input('situacao_id', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $situacoes,
            'id' => 'idSituacao',
            'label' => __('Apto?') . $isRequerid,
            'empty' => __('label_selecione'),
            'disabled' => $formDisabled));
        ?>
    </div>
    <div class="col-md-6"></div>
</div>

<div class="row" id="rowDataParecerTecnico">
    <div class="col-md-2">
        <div class="form-group">
            <?php
            echo
            $this->Form->input('data_parecer', array('label' => __('atendimento_laudo_label_data') . $isRequerid,
                'type' => 'text',
                'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)',
                'disabled' => $formDisabled));
            ?>
        </div>
    </div>

    <div class="col-md-8"></div>
</div>


<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('parecer', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __('atendimento_laudo_label_parecer') . $isRequerid));
        ?>
    </div>
</div>
<div class="row displayNone">
    <div class="col-md-9">
        <?php
        echo $this->Form->input('observacoes_exigencias', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            'disabled' => $formDisabled,
            'id' => 'inputObservacoesExigencia',
            'onkeyup' => "limitarTamanho(this,1000);",
            'onblur' => "limitarTamanho(this,1000);",
            'label' => __('atendimento_popup_solicitacoes_informacoes_label_observacoes')));
        ?>
    </div>
    <div class="col-md-3 form-group">
        <?php
        echo $this->Form->input('data_limite_exigencia', array('div' => array('class' => 'form-group'),
            'class' => 'form-control inputData',
            'disabled' => $formDisabled,
            'type' => 'text',
            'id' => 'input_data_limite_exigencia',
            'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
            'onblur' => 'VerificaData(this,this.value)',
            'onmouseout' => 'VerificaData(this,this.value)',
            'label' => __('atendimento_popup_solicitacoes_informacoes_label_data_limite') . $isRequerid));
        ?>
    </div>
</div>
<div class="row displayNone">
    <div class="col-md-12 form-group">
        <?php
        echo $this->Form->input('RequisicaoDisponivel', array('options' => $requisicoes,
            'id' => 'picklistRequisicoes',
            'multiple' => 'multiple',
            'class' => 'sigas_multi_select alturaPickList',
            'disabled' => $formDisabled,
            'div' => array('class' => 'form-group multi-select '),
            'label' => __('atendimento_popup_solicitacoes_informacoes_label_requisicoes_disponiveis') . ': '));
        ?>

    </div>
</div>
<div id="dialog-informacoes_sobre_servidor" class="displayNone panel">
    <div class="row col-sm-12">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border"><?php echo __('atendimento_popup_solicitacoes_informacoes_titulo') ?></legend>
            <div class="row">
                <div class="col-md-6 form-group">
                    <?php
                    echo $this->Form->input('Usuario.nome', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'autofocus',
                        'disabled' => true,
                        'value' => $dadosServidor['Usuario']['nome'],
                        'label' => __('atendimento_popup_solicitacoes_informacoes_label_servidor')));
                    ?>
                </div>
                <div class="col-md-3 form-group">
                    <?php
                    echo $this->Form->input('cpf_servidor', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control cpf',
                        'disabled' => true,
                        'value' => $dadosServidor['Usuario']['cpf'],
                        'label' => __('atendimento_popup_solicitacoes_informacoes_label_cpf')));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <?php
                    echo
                    $this->Form->input('Agendamento.tipologia_id', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'options' => $tipologias,
                        'selected' => $tipologias,
                        'disabled' => true,
                        'label' => __('atendimento_popup_solicitacoes_informacoes_label_tipologia')));
                    ?>
                </div>
                <div class="col-md-3 form-group">
                    <?php
                    echo $this->Form->input('data_limite_exigencia', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control inputData',
                        'disabled' => $formDisabled,
                        'id' => 'data_limite_exigencia',
                        'type' => 'text',
                        'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                        'onblur' => 'VerificaData(this,this.value)',
                        'onmouseout' => 'VerificaData(this,this.value)',
                        'label' => __('atendimento_popup_solicitacoes_informacoes_label_data_limite') . $isRequerid));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo $this->Form->input('observacoes_exigencias', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'disabled' => $formDisabled,
                        'id' => 'observacoes_exigencia_modal',
                        'onkeyup' => "limitarTamanho(this,1000);",
                        'onblur' => "limitarTamanho(this,1000);",
                        'label' => __('atendimento_popup_solicitacoes_informacoes_label_observacoes')));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group" style="width: 55%;">
                    <?php echo $this->Form->label(null, __('atendimento_popup_solicitacoes_informacoes_label_requisicoes_disponiveis') . ': '); ?>
                </div>
            </div>
            <?= $this->element('componente_acoes_pickList', array("target" => 'requisicao_exigencia_modal')) ?>
            <div class="row">
                <div class="col-md-12 form-group">
                    <?php
                    echo $this->Form->input('RequisicaoDisponivel', array('options' => $requisicoes,
                        'id' => 'sigas_multi_select',
                        'multiple' => 'multiple',
                        'id' => 'requisicao_exigencia_modal',
                        'class' => 'sigas_multi_select alturaPickList',
                        'disabled' => $formDisabled,
                        'div' => array('class' => 'form-group multi-select '),
                        'label' => false));
                    ?>

                </div>
            </div>
            <br/>
        </fieldset>
    </div>
</div>