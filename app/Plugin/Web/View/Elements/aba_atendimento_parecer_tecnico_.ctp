<?php
$tipologiaAgendamentoVinculo = ($this->request->data['Agendamento']['vinculo']);
$tipologiaId = intval($tipologiaAgendamento);


echo $this->Form->hidden('dataAtual', array('id' => 'inputDataAtual', 'value' => date("Y-m-d")));
echo $this->Form->input('validacao_data_limite_exigencia', array('label' => false, 'class' => 'displayNone'));

$urlIntervaloDatas = Router::url(array('controller' => "Atendimento", 'action' => 'obterInternaloEntreDatas'), true);
?>
<?php

if (TIPOLOGIA_LICENCA_MATERNIDADE === $tipologiaId ||
        TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO === $tipologiaId ||
        TIPOLOGIA_LICENCA_NATIMORTO === $tipologiaId ||
        TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE === $tipologiaId):
    ?>
    <div class="row">
        <div class="col-md-3 form-group">
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
    </div>
<?php endif; ?>

<?php if (TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA === $tipologiaId):
    ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $this->Form->input('validacao_tipo_beneficio', array('label' => false, 'class' => 'displayNone'));
            echo $this->Form->label(null, __('atendimento_laudo_label_beneficio_previdenciario')) . $isRequerid;
            echo "<br/>";
            echo $this->Form->checkbox('aposentado', array('disabled' => $formDisabled)) . __('atendimento_laudo_label_aposentado');
            echo "<br/>";
            echo $this->Form->checkbox('pensionista', array('disabled' => $formDisabled)) . __('atendimento_laudo_label_pensionista');
            ?>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-2">
            <?php
            echo
            $this->Form->input('isencao_id', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $tipoIsecao,
                'id' => 'selectTipoIsencao',
                'label' => __('atendimento_laudo_label_isencao') . $isRequerid,
                'empty' => __('label_selecione'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-2 displayNone" id="divDataIsencaoTemporaria">
            <?php
            echo $this->Form->input('isencaoTemporaria', array(
                'type' => 'hidden',
                'id' => 'hidIsencao',
                'value' => ISENCAO_TEMPORARIA,
                'disabled' => true
            ));

            echo
            $this->Form->input('data_insencao_temporaria', array('label' => __('Período Concedido') . $isRequerid,
                'type' => 'text',
                'data-url' => $urlIntervaloDatas,
                'data-label' => 'labelInternaloIsencaoTemporaria',
                'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)',
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-4">
            <label> </label>
            <label class="form-control" style="border: 0px;" id="labelInternaloIsencaoTemporaria"> </label>
        </div>
        <div class="col-md-4"></div>
    </div>

<?php endif; ?>
<div class="row">
    <?php if (TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR === $tipologiaId || TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE === $tipologiaId):
        ?>
        <div class="col-md-2">
            <?php
            echo
            $this->Form->input('modo', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $modos,
                'id' => 'inputModos',
                'label' => __('atendimento_laudo_label_modo') . $isRequerid,
                'empty' => __('label_selecione'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <?php
    endif;
    ?>
    <div class="col-md-3">
        <?php
        if(!in_array($tipologiaAgendamento,array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO))){
            $situacaoRequired = $isRequerid;
            if($tipologiaAgendamento == TIPOLOGIA_SINDICANCIA_INQUERITO_PAD) {
                $situacaoRequired = '';
            }
            $label =  __('atendimento_laudo_label_situacao');
            if($tipologiaAgendamento == TIPOLOGIA_APOSENTADORIA_ESPECIAL){
                $label = "Se enquadra?";
                $situacoes = array(
                    TipoSituacaoParecerTecnico::SE_ENQUADRA => "Sim",
                    TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA => "Não",
                    TipoSituacaoParecerTecnico::EM_EXIGENCIA => "Em Exigência");
            }
            echo
            $this->Form->input('situacao_id', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $situacoes,
                'id' => 'inputSituacao',
                'label' => $label. $situacaoRequired,
                'empty' => __('label_selecione'),
                'disabled' => $formDisabled));
        }
        ?>
    </div>
    <div class="col-md-2 displayNone" id="divBotaoSituacao">
        <?php
        echo $this->Form->label(null, "");
        echo $this->Form->button(__('bt_exigencia'), array(
            'class' => 'btn fa fa-search estiloBotao btn-info',
            'id' => 'botaoExigencias',
            'style' => 'top:19px;position:relative;',
            'type' => 'button',
        ));
        ?>
    </div>
    <div class="col-md-6"></div>
    <?php
    if (in_array($tipologiaId, array(
            TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO, TIPOLOGIA_LICENCA_NATIMORTO,
            TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE, TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR ))):
        ?>  
        <div class="row col-sm-4  displayNone" id="fieldSetSolicitarLicenca">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?php echo __('atendimento_perito_label_solicitar_licenca') ?></legend>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo $this->Form->input('data_parecer', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control inputData',
                            'type' => 'text',
                            'id' => 'aPartirDeParecer',
                            'disabled' => $formDisabled,
                            'label' => __('atendimento_perito_label_a_partir_de') . $isRequerid));
                        ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?php
                        echo $this->Form->input('duracao', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control soNumero',
                            'id' => 'duranteParecer',
                            'maxlength' => 3,
                            'disabled' => $formDisabled,
                            'label' => __('atendimento_perito_label_durante') . $isRequerid));
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>
    <?php endif; ?>
</div>
<?php
if (!in_array($tipologiaId, array(
        TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO, TIPOLOGIA_LICENCA_NATIMORTO,
        TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE, TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR))):
    ?>  
    <div class="row" id="rowDataParecerTecnico">
        <div class="col-md-3">
            <div class="form-group">
                <?php
                echo
                $this->Form->input('data_parecer', array('label' => __('Data do diagnóstico da doença') . $isRequerid,
                    'type' => 'text',
                    'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                    'onblur' => 'VerificaData(this,this.value)',
                    'onmouseout' => 'VerificaData(this,this.value)',
                    'disabled' => $formDisabled));
                ?>
            </div>
        </div>

        <?php
        $tipologiasCmpDurante = array(
            TIPOLOGIA_REMOCAO, 
            TIPOLOGIA_REMANEJAMENTO_FUNCAO, 
            TIPOLOGIA_READAPTACAO_FUNCAO, 
            TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ,
            TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
            TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA
        );
        if (in_array($tipologiaId, $tipologiasCmpDurante)): ?>
            <div class="col-md-2 form-group cmpDurante">
                <?php
                echo $this->Form->input('duracao', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control soNumero',
                    'maxlength' => 3,
                    'disabled' => $formDisabled,
                    'label' => __('atendimento_perito_label_durante') . $isRequerid));
                ?>
            </div>
        <?php endif; ?>
        <div class="col-md-8"></div>
    </div>
<?php endif; ?>

<?php
if (TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE === $tipologiaId && (CTD === $tipologiaAgendamentoVinculo || CLT === $tipologiaAgendamentoVinculo) ): ?>

<div class="row">
    <div class="col-md-3">
        <?php
        echo
        $this->Form->input('numero_nr', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'text',
            'label' => __('atendimento_laudo_label_numero_nr') . $isRequerid,
            'disabled' =>  $formDisabled));
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo
        $this->Form->input('numero_anexo', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'text',
            'label' => __('atendimento_laudo_label_numero_anexo') . $isRequerid,
            'disabled' =>  $formDisabled));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?php
        echo
        $this->Form->input('letra', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'text',
            'label' => __('atendimento_laudo_label_letra') . $isRequerid,
            'disabled' =>  $formDisabled));
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo
        $this->Form->input('natureza_agente', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'text',
            'label' => __('atendimento_laudo_label_natureza_agente') . $isRequerid,
            'disabled' =>  $formDisabled));
        ?>
    </div>
</div>
<?php endif; ?>



<?php
if (TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO === $tipologiaId):
    ?>
    <div class="row">
        <div class="col-md-3">
            <?php
            $arrTipo = array('1' => __('label_sim'), '0' => __('label_nao'));

            $strDependenteMaiorIdade = (isset($this->data['Atendimento']['dependente_maior_invalido'])) ? $this->data['Atendimento']['dependente_maior_invalido'] : null;

            $value = null;
            if ($strDependenteMaiorIdade == true):
                $value = 1;
            elseif ($strDependenteMaiorIdade === false):
                $value = 0;
            endif;
            echo
            $this->Form->input('dependente_maior_invalido', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $arrTipo,
                'id' => 'inputDependenteMaior',
                'value' => $value,
                'empty' => __('label_selecione'),
                'label' => __('atendimento_laudo_dependente_maior_invalido') . $isRequerid,
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-4 displayNone divInvalidezFisica">
            <?php
            echo
            $this->Form->input('invalidez_fisica_id', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $tiposInvalidezFisica,
                'id' => 'inputInvalidezFisica',
                'label' => __('atendimento_laudo_invalidez_fisica') . $isRequerid,
                'empty' => __('label_selecione'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-2 displayNone divDataDependenteInvalido">
            <?php
            echo
            $this->Form->input('data_dependente_invalido', array('label' => __('atendimento_laudo_label_data') . $isRequerid,
                'type' => 'text',
                'data-url' => $urlIntervaloDatas,
                'data-label' => 'labelInternaloInvalidez',
                'id' => 'inputDataInvalidezFisica',
                'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)',
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-3">
            <label> </label>
            <label class="form-control" style="border: 0px;" id="labelInternaloInvalidez"> </label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 displayNone divAtosVidaCivil">
            <?php
            echo
            $this->Form->input('incap_atos_vida_civil_id', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $tiposInvalidezFisica,
                'id' => 'inputTipoAtosVida',
                'label' => __('atendimento_laudo_incapacidade_atos_vida'),
                'empty' => __('label_selecione'),
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-2 displayNone divDataAtosVida">
            <?php
            echo
            $this->Form->input('data_dependente_inc_atos_vida', array('label' => __('atendimento_laudo_label_data') . $isRequerid,
                'type' => 'text',
                'id' => 'inputDataIncAtosVida',
                'data-url' => $urlIntervaloDatas,
                'data-label' => 'labelInternaloAtosVida',
                'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                'onblur' => 'VerificaData(this,this.value)',
                'onmouseout' => 'VerificaData(this,this.value)',
                'disabled' => $formDisabled));
            ?>
        </div>
        <div class="col-md-3">
            <label> </label>
            <label class="form-control" style="border: 0px;" id="labelInternaloAtosVida"> </label>
        </div>
    </div>
<?php else: ?>
    <?php
    if (!in_array($tipologiaId , array(
            TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO ,TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO,
            TIPOLOGIA_LICENCA_NATIMORTO, TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE, TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,
            TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
            TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR, TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES,
            TIPOLOGIA_REMOCAO, TIPOLOGIA_READAPTACAO_FUNCAO, TIPOLOGIA_REMANEJAMENTO_FUNCAO,
            TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA
    ))):
        ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $this->Form->checkbox('patologia_remonta_lc', array('disabled' => $formDisabled));
                ?>
                <label for="hidEncaixe"><?= __('atendimento_laudo_label_patologia_remonta_vigencia'); ?></label>
            </div>
        </div>
    <?php endif; ?>
    <? if( $tipologiaId === TIPOLOGIA_APOSENTADORIA_ESPECIAL): ?>
        <style>
            .fleft{
                float:left;
                margin-left: 10px;;
            }
        </style>
        <div class="row">
            <?php
            echo $this->PForm->radioYN(array(
                'title' => 'Será necessário inspeção?' . $isRequerid,
                'name' => 'necessario_inspecao',
                'disabled' => $formDisabled,
                'class' => 'fleft input_necessario_inspecao',
                'column' => 3
            ));
            if ($this->Form->isFieldError('necessario_inspecao')) {
                echo $this->Form->error('necessario_inspecao');
            }
            ?>
            <div class="col-md-3 necessario_inspecao displayNone" >
                <div class="fleft">
                    <?php
                    $displayNone = 'displayNone';
                    $numeroInspecao = '';
                    if(isset($this->data['Atendimento']['numero_inspecao']) && !empty($this->data['Atendimento']['numero_inspecao'])) {
                        $displayNone = '';
                        $numeroInspecao = $this->data['Atendimento']['numero_inspecao'];
                    }

                    echo $this->Form->hidden('numero_inspecao', array('id' => 'numero_inspecao'));
                    $urlAutoCompleteInspecao = Router::url(array('controller' => "Atendimento", 'action' => 'getNumeroInspecao/'.$dadosServidor['Usuario']['id']), true);
                    echo
                    $this->Form->input('escolha_inspecao', array('label' => 'Informe o número da inspeção' . $isRequerid,
                        'type' => 'text',
                        'id' => 'escolha_inspecao',
                        'maxlength' => 10,
                        'data-url' => $urlAutoCompleteInspecao,
                        'class' => 'form-control',
                        'value' => $numeroInspecao,
                        'disabled' => $formDisabled));
                    ?>
                </div>
            </div>
            <div class="col-md-3 fleft necessario_inspecao <?=$displayNone?>">
                <label for="inspecao_selecionada"> &nbsp;</label>
                <label id="label_inspecao_selecionada" class="<?=$displayNone?> form-control" style="border: 0px">Inspeção Nº <a><?=$numeroInspecao?></a></label>
            </div>
        </div>
    <? endif; ?>
<?php endif; ?>
<div class="row">
    <div class="col-md-12">
        <?php
        $parecerRequired = $isRequerid;
        if (!in_array($tipologiaId, array(TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO, TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO,
            TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_INSPECAO)))
        {
            $parecerRequired = '';
	        $labelLaudo = 'Complemento do Laudo';
        }
        $labelLaudo = (TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE !==$tipologiaId)?'atendimento_laudo_label_parecer':'atendimento_laudo_label_observacao';

        /*if(in_array($tipologiaId, array(
            TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES,
            TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA)))
        {
			$labelLaudo = 'Complemento do Laudo';
        }*/

        echo $this->Form->input('parecer', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => __("$labelLaudo") . $parecerRequired ));
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