<?php $urlGetCid = Router::url(['controller'=>'Cid','action'=>'getCidId']); ?>
<div id="blockAll" style="
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    z-index: 900;
    background-color: rgba(0,0,0,0.5);
    margin-top: -15px;
    margin-left: -15px;
    cursor: not-allowed;
"></div>
<style>
    .blockDestaque{
        z-index: 999;
        background-color: white;
        padding: 15px;
    }
    .fields-designacao{
        display:none;
    }
</style>
<script>
    var arrTipologiasSemCid = <?=$tipologiasSemCid ?>;
</script>
<?php
$fileform = array('enctype' => 'multipart/form-data');
$formCreateNew = array_merge($formCreate,$fileform);
?>
<?= $this->Form->create($controller, $formCreateNew); ?>
<?php


echo $this->Form->input('idAgendamentoVigente', array(
    'type' => 'hidden',
    'id' => 'id_agendamento_vigente',
    'disabled' => true
));

echo $this->Form->input('qualidade_outros', array(
    'type' => 'hidden',
    'id' => 'qualidade_outros',
    'value' => QUALIDADE_OUTROS,
    'disabled' => true
));

echo $this->PForm->hidden('tipologia_licenca_maternidade',TIPOLOGIA_LICENCA_MATERNIDADE);
echo $this->PForm->hidden('tipologia_licenca_maternidade_aborto',TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO);
echo $this->PForm->hidden('tipologia_licenca_medica_tratamento_saude', TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE);
echo $this->PForm->hidden('tipologia_licenca_natimorto', TIPOLOGIA_LICENCA_NATIMORTO);
echo $this->PForm->hidden('tipologia_aposentadoria_invalidez', TIPOLOGIA_APOSENTADORIA_INVALIDEZ);
echo $this->PForm->hidden('tipologia_isencao_contribuicao_previdenciaria', TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA);
echo $this->PForm->hidden('tipologia_reversao_aposentadoria_invalidez', TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ);
echo $this->PForm->hidden('tipologia_avaliacao_habilitacao_dependentes',TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES);
echo $this->PForm->hidden('tipologia_pcd', TIPOLOGIA_PCD);
echo $this->PForm->hidden('tipologia_admissao_pensionista_maior_invalido', TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO);
echo $this->PForm->hidden('tipologia_informacao_seguro_compreensivo_habitacional',TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL);
echo $this->PForm->hidden('tipologia_readaptacao_funcao', TIPOLOGIA_READAPTACAO_FUNCAO);
echo $this->PForm->hidden('tipologia_remocao',TIPOLOGIA_REMOCAO);
echo $this->PForm->hidden('tipologia_remanejamento_funcao', TIPOLOGIA_REMANEJAMENTO_FUNCAO);
echo $this->PForm->hidden('tipologia_risco_vida_insalubridade', TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE);
echo $this->PForm->hidden('tipologia_recurso_administrativo', TIPOLOGIA_RECURSO_ADMINISTRATIVO);
echo $this->PForm->hidden('tipologia_exame_pre_admissional', TIPOLOGIA_EXAME_PRE_ADMISSIONAL);
echo $this->PForm->hidden('tipologia_designacao_de_assistente_tecnico', TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO);
echo $this->PForm->hidden('tipologia_licenca_acompanhamento_familiar', TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR);
echo $this->PForm->hidden('tipologia_atencipacao_licenca',TIPOLOGIA_ATECIPACAO_LICENCA);
echo $this->PForm->hidden('tipologia_sindicancia_inquerito_pad', TIPOLOGIA_SINDICANCIA_INQUERITO_PAD);
echo $this->PForm->hidden('tipologia_comunicacao_de_acidente_de_trabalho', TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO);
echo $this->PForm->hidden('tipologia_aposentadoria_especial' , TIPOLOGIA_APOSENTADORIA_ESPECIAL);
echo $this->PForm->hidden('tipologia_inspecao' , TIPOLOGIA_INSPECAO);


echo $this->PForm->hidden('tipo_isencao_servidor' , TIPO_ISENCAO_SERVIDOR);
echo $this->PForm->hidden('tipo_isencao_pensionista' , TIPO_ISENCAO_PENSIONISTA);


echo $this->Form->input('usuario_perito_credenciado', array(
    'type' => 'hidden',
    'id' => 'usuario_perito_credenciado',
    'value' => USUARIO_PERITO_CREDENCIADO,
    'disabled' => true
));

echo $this->Form->input('usuario_perito_servidor', array(
    'type' => 'hidden',
    'id' => 'usuario_perito_servidor',
    'value' => USUARIO_PERITO_SERVIDOR,
    'disabled' => true
));

echo $this->Form->input('usuario_interno', array(
    'type' => 'hidden',
    'id' => 'usuario_interno',
    'value' => USUARIO_INTERNO,
    'disabled' => true
));

echo $this->Form->input('usuario_servidor', array(
    'type' => 'hidden',
    'id' => 'usuario_servidor',
    'value' => USUARIO_SERVIDOR,
    'disabled' => true
));



echo $this->Form->input('vinculo_estatutario', array(
    'type' => 'hidden',
    'id' => 'vinculo_estatutario',
    'value' => ESTATUTARIO,
    'disabled' => true
));




echo $this->Form->input('vinculo_ctd', array(
    'type' => 'hidden',
    'id' => 'vinculo_ctd',
    'value' => CTD,
    'disabled' => true
));

echo $this->Form->input('vinculo_clt', array(
    'type' => 'hidden',
    'id' => 'vinculo_clt',
    'value' => CLT,
    'disabled' => true
));


$isHomologa = 0;
if($formDisabledHomologa){
    $isHomologa = 1;
}

echo $this->Form->input('is_homologa', array(
    'type' => 'hidden',
    'id' => 'is_homologa',
    'value' => $isHomologa,
    'disabled' => true
));

if (($this->params['action'] != 'adicionar')):
    echo $this->Form->input('id', array(
        'type' => 'hidden'
    ));
    echo $this->Form->input('data_inclusao', array(
        'type' => 'hidden'
    ));
endif;

?>

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Agendamento')))); ?>
            <?php
            echo $this->Form->input('tipoUsuarioLogado', array(
                'type' => 'hidden',
                'id' => 'tipoUsuarioLogado',
                'value' => CakeSession::read('Auth.User.tipo_usuario_id'),
                'disabled' => true
            ));

            echo $this->Form->input('baseUrlDefault', array(
                'type' => 'hidden',
                'id' => 'baseUrlDefault',
                // 'data-url' => Router::url('/admin/Agendamento/', true),
                'data-url' => Router::url('/web/Agendamento/', true),
                'disabled' => true
            ));

            $usuarioServidor = '';
            $dataObito = '';
            $cpfServidor = '';
            $nomeServidor = '';
            $designacaoPerito = '';
            if (CakeSession::read('Auth.User.tipo_usuario_id') != USUARIO_INTERNO && !$formDisabledHomologa) {
                $usuarioServidor = CakeSession::read('Auth.User.id');
                $instanceUsuario = new Usuario();
                $usuarioObj = $instanceUsuario->obterUsuario(['Usuario.id'=>$usuarioServidor], 'all');
                if(!empty($usuarioObj)){
                    $dataObito = Util::toBrDataHora($usuarioObj[0]['Usuario']['data_obito']);
                    $cpfServidor = $usuarioObj[0]['Usuario']['cpf'];
                    $nomeServidor = $usuarioObj[0]['Usuario']['nome'];
                }
            } else {
                if (isset($this->request->data)){
                    if (isset($this->request->data['Agendamento']['usuario_servidor_id'])) {
                        $usuarioServidor = $this->request->data['Agendamento']['usuario_servidor_id'];
                    }
                    if (isset($this->request->data['Agendamento']['nome'])) {
                        $nomeServidor = $this->request->data['Agendamento']['nome'];
                    }
                    if (isset($this->request->data['Agendamento']['cpf'])) {
                        $cpfServidor = $this->request->data['Agendamento']['cpf'];
                    }
                    if (isset($this->request->data['Agendamento']['designacao_usuario_perito_id'])) {
                        $designacaoPerito = $this->request->data['Agendamento']['designacao_usuario_perito_id'];
                    }
                }
            }

            echo $this->Form->input('usuario_servidor_id', array(
                'type' => 'text',
                'class' => 'displayNone',
                'label' => false,
                'id' => 'hiddenServidorId',
                'value' => $usuarioServidor,
                'disabled' => $formDisabled || $formDisabledHomologa
            ));

            echo $this->Form->input('designacao_usuario_perito_id', array(
                'type' => 'text',
                'class' => 'displayNone',
                'label' => false,
                'id' => 'hiddenPeritoId',
                'value' => $designacaoPerito,
                'disabled' => $formDisabled || $formDisabledHomologa
            ));

            echo $this->Form->input('data_obito_usuario', array(
                'type' => 'hidden',
                'id' => 'data_obito_servidor',
                'value' => $dataObito,
                'label' => false));

            echo $this->Form->input('validacao_data_obito', array(
                'class' => 'displayNone',
                'label' => false));
            ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="scheduler-border" id="fieldsetServidor">
                            <legend class="scheduler-border"><?php echo __('agendamento_input_servidor') ?></legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    echo
                                    $this->Form->input('cpf', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control cpf',
                                        'id' => 'cpfServidor',
                                        'value' => $cpfServidor,
                                        'label' => __('agendamento_input_cpf_servidor') . $isRequerid,
                                        'disabled' => $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>
                                <div class="col-md-8">
                                    <?php
                                    echo
                                    $this->Form->input('nome', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'id' => 'nomeServidor',
                                        'maxlength' => '150',
                                        'value' => $nomeServidor,
                                        'label' => __('agendamento_input_nome_servidor') . $isRequerid,
                                        'disabled' => $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row" id="fieldSetSolicitarLicenca">
                    <div class="col-md-10">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('agendamento_input_solicitar_licenca') ?></legend>
                            <div class="row">
                                <div class="col-md-10">
                                    <?php
                                    $desabilitarTipologia = ($formDisabled || $formDisabledHomologa || $acao == Configure::read('ACAO_ALTERAR'));
                                    echo
                                    $this->Form->input('tipologia_id', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control carregarHorarios',
                                        'id' => 'comboTipologia',
                                        // 'data-url' => Router::url('/admin/Agendamento/carregarHorariosAtendimento', true),
                                        'data-url' => Router::url('/web/Agendamento/carregarHorariosAtendimento', true),
                                        'options' => $tipologias,
                                        'empty' => __('label_selecione'),
                                        'label' => __('agendamento_input_tipologia') . $isRequerid,
                                        'disabled' => $desabilitarTipologia));
                                    if ($desabilitarTipologia):
                                        echo $this->Form->hidden('tipologia_id');
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <div class="row displayNone" id="divReadaptacaoDefinitiva">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <?php
                                        echo $this->Form->checkbox('readaptacao_definitiva', array('div' => array('class' => 'form-group'),
                                            'disabled' => $formDisabled || $formDisabledHomologa,
                                            'id' => 'hidReadaptacaoDefinitiva'
                                        ));
                                        ?>
                                        <label for="hidReadaptacaoDefinitiva"><?= 'Remanejamento definitivo'; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group colunaObito displayNone">
                                    <?php
                                    if(isset($this->request->data['Agendamento'])){
                                        if(isset($this->request->data['Agendamento']['data_obito'])){
                                            $this->request->data['Agendamento']['data_obito'] = Util::toBrData($this->request->data['Agendamento']['data_obito']);
                                        }
                                    }
                                    echo
                                    $this->Form->input('data_obito', array('label' =>"Data Óbito" . $isRequerid,
                                        'type' => 'text',
                                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)',
                                        'disabled' => $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>

                                <div class="col-md-3 form-group colunaApartirDe">
                                    <?php
                                    echo
                                    $this->Form->input('data_a_partir', array('label' => __('agendamento_input_partir_de') . $isRequerid,
                                        'type' => 'text',
                                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)',
                                        'disabled' => $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>
                                <div class="col-md-2 form-group" id="divDurante">
                                    <?=
                                    $this->Form->input('duracao', array('label' => __('agendamento_input_durante') . $isRequerid,
                                        'type' => 'text',
                                        'maxlength' => '3',
                                        'label' => array('id' => 'labelDurante', 'text' => __('agendamento_input_durante'). $isRequerid),
                                        'id' => 'inputDuracao',
                                        'class' => 'form-control soNumero',
                                        'disabled' => $formDisabled || $formDisabledHomologa));
                                    ?>
                                    <div class="float-right" style="margin-right: -30px;margin-top: -30px;" id="labelDiasDuracao">
                                        <?= __('agendamento_label_dias'); ?>
                                    </div>
                                </div>
                                
                                <!-- checkbox atendimento em exigência 
                                <div class="form-group" id="nomeExigencia" style="display: inline-block;">-->
                                    
                                
                                    <div class="col-md-3" id="divExigencia" style="margin-left: 5%;">
                                        <span style="margin-left: 8%;">
                                            <b>
                                            <?= __('agendamento_chkbx_exigencia'); ?>
                                            </b>
                                        </span>
                                        <span class="pull-left" style="margin-top: -5%;">
                                        <?=
                                        $this->Form->checkbox('chkbx_exigencia', array('label' => __('agendamento_chkbx_exigencia') . $isRequerid,
                                            'id' => 'chkbxExigencia',
                                            'label' => array('id' => 'labelDecorrente', 'text' => __('agendamento_chkbx_exigencia'). $isRequerid),
                                            'default' => 'f',
                                            'disabled' => $formDisabled || $formDisabledHomologa));
                                        ?> 
                                        </span>                                                                      
                                    </div>
                                    <!-- input num atendimento da exigência -->
                                    <div class="col-md-3">
                                        <div style="display: none;">
                                           <?php 
                                                echo $this->Form->hidden('numero_exigencia', array('id' => 'numero_exigencia'));
                                                $urlAutoCompleteExigencia = Router::url(array('controller' => "Agendamento", 'action' => 'getExigencia/'), true);
                                                $valor = (isset($this->data['Agendamento']['numero_processo'])) ? $this->data['Agendamento']['numero_processo'] : null;
                                                $desabilitarNumProcesso = ($formDisabledHomologa || $acao == Configure::read('ACAO_ALTERAR'));
                                                echo
                                                $this->Form->input('num_exigencia', array('label' => __('agendamento_num_exigencia'). $isRequerid,
                                                    'type' => 'text',
                                                    'id' => 'escolha_exig',
                                                    'maxlength' => 10,
                                                    'data-url' => $urlAutoCompleteExigencia,
                                                    'class' => 'form-control soNumero',
                                                    'label' => array('id' => 'labelExigencia', 'text' => __('agendamento_num_exigencia'). $isRequerid),
                                                    'value' => $valor,
                                                    'disabled' => $desabilitarNumProcesso));

                                                $displayNone = ($desabilitarNumProcesso?'':'displayNone');

                                                $txtDisplay = isset($this->data['Agendamento']['numero_processo'])?$this->data['Agendamento']['numero_processo'].' ':'';
                                                $txtDisplay .= isset($this->data['Agendamento']['tipologia_processo'])?$this->data['Agendamento']['tipologia_processo']:''
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="processo_selecionado"> &nbsp;</label>
                                                <label id="label_processo_selecionado" class="<?=$displayNone?> form-control" style="border: 0px"></label>
                                            </div>
                                    </div>
                                
                                <div>
                                    <?php
                                    echo
                                    $this->Form->input('data_ate', array('label' => __('agendamento_input_ate') . $isRequerid,
                                        'type' => 'text',
                                        'id' => 'inputAte',
                                        'label' => array('class' => 'displayNone', 'id' => 'labelAte', 'text' => __('agendamento_input_ate'). $isRequerid),
                                        'class' => 'inputData form-control displayNone form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)',
                                        'disabled' => $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>
                            </div>
                            <div id="div-tip<?=TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA?>" class="row displayNone">
                                <div class="col-md-3 form-group" >
                                    <?php
                                    $arrTipoDeIsencao = array(
                                        TIPO_ISENCAO_SERVIDOR => "Servidor",
                                        TIPO_ISENCAO_PENSIONISTA => "Pensionista"
                                    );
                                    echo
                                    $this->Form->input('tipo_isencao', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'options' => $arrTipoDeIsencao,
                                        'empty' => __('label_selecione'),
                                        'label' => __('Tipo') . $isRequerid,
                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>
                                <div id="div-data_aposentadoria" class="col-md-3 displayNone">
                                    <?php
                                    echo
                                    $this->Form->input('data_aposentadoria', array('label' => __('Data Aposentadoria') . $isRequerid,
                                        'type' => 'text',
                                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)',
                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <!--vinculo -->
                <div id="" class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                        <div class="col-md-5">
                            <?php
                            $arrTipo = array('0' => __('agendamento_label_estatutario'), '1' => __('agendamento_label_ctd'), '2' => __('agendamento_label_clt'));

                            $value = (isset($this->data['Agendamento']['vinculo'])) ? $this->data['Agendamento']['vinculo'] : null;
                            //echo "value:::".$value;

                            echo
                            $this->Form->input('vinculo', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $arrTipo,
                                'value' => $value,
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_vinculo') . $isRequerid,
                                'disabled' => $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                        <div class="col-md-5">
                            <?php
                            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                            $strAtivo = (isset($this->data['Agendamento']['gratificacao_risco_vida_saude'])) ? $this->data['Agendamento']['gratificacao_risco_vida_saude'] : null;
                            $value = null;
                            if ($strAtivo == true):
                                $value = 1;
                            elseif ($strAtivo === false):
                                $value = 0;
                            endif;
                            echo
                            $this->Form->input('gratificacao_risco_vida_saude', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $arrTipo,
                                'value' => $value,
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_gratificacao_risco_vida_saude') . $isRequerid,
                                'disabled' => $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                    </div>
                </div>
                <!--end row vinculo-->
                <!--contrato trabalho -->
                <div id="fileContratoTrabalho" class="displayNone">
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            echo
                            $this->Form->input('contrato_trabalho', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'type' => 'file',
                                'label' => __('agendamento_contrato_trabalho') . $isRequerid,
                                'disabled' => $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>

                        <div class="col-md-5" style="margin-top: 24px;">
                            <?php

                            $existFile = (isset($this->data['Agendamento']['contrato_trabalho_path']) && isset($this->data['Agendamento']['id']));
                            if($existFile){?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                        <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['contrato_trabalho_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                        <?if(!$formExtra){?>
                                        <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'contrato_trabalho') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                        <?}?>
                                    </ul>
                                </div>
                            <? }?>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-5">
                            <?php

                            echo
                            $this->Form->input('edital_concurso', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'type' => 'file',
                                'label' => __('agendamento_edital_concurso') . $isRequerid,
                                'disabled' => $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                        <div class="col-md-5" style="margin-top: 24px;">
                            <?php

                            $existFile = (isset($this->data['Agendamento']['edital_concurso_path'])  && isset($this->data['Agendamento']['id']));
                            if($existFile){?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                        <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['edital_concurso_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                        <?php if(!$formExtra){?>
                                        <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'edital_concurso') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                        <?}?>
                                    </ul>
                                </div>
                            <? }?>
                        </div>
                    </div>
                 </div>
                <!--end contrato trabalho-->

                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                            <div class="col-md-3 form-group">
                                <?php
                                echo
                                $this->Form->input('horario_trabalho_inicial', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control hour',
                                    'type' => 'text',
                                    'label' => __('agendamento_label_horario_inicial') . $isRequerid,
                                    'disabled' =>  $formDisabled || $formDisabledHomologa));
                                ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <?php
                                echo
                                $this->Form->input('horario_trabalho_final', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control hour',
                                    'type' => 'text',
                                    'label' => __('agendamento_label_horario_final') . $isRequerid,
                                    'disabled' =>  $formDisabled || $formDisabledHomologa));
                                ?>
                            </div>
                    </div>
                </div>
                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <?=
                            $this->Form->input('atribuicao_funcao', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'class' => 'form-control',
                                'onkeyup' => "limitarTamanho(this,2000);",
                                'onblur' => "limitarTamanho(this,2000);",
                                'label' => __('agendamento_label_atribuicao_funcao') . ': ',
                                'disabled' =>  $formDisabled || $formDisabledHomologa,
                                'type' => 'textarea'
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <?=
                            $this->Form->input('atividade_executada', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'class' => 'form-control',
                                'onkeyup' => "limitarTamanho(this,2000);",
                                'onblur' => "limitarTamanho(this,2000);",
                                'label' => __('agendamento_label_atividade_executada') . ': ',
                                'disabled' =>  $formDisabled || $formDisabledHomologa,
                                'type' => 'textarea'
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <?=
                            $this->Form->input('informacao_adicional', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'class' => 'form-control',
                                'onkeyup' => "limitarTamanho(this,2000);",
                                'onblur' => "limitarTamanho(this,2000);",
                                'label' => __('agendamento_label_informacao_adicional') . ': ',
                                'disabled' =>  $formDisabled || $formDisabledHomologa,
                                'type' => 'textarea'
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                         <div class="col-md-5">
                                <?php
                                $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                                $strAtivo = (isset($this->data['Agendamento']['curso_formacao'])) ? $this->data['Agendamento']['curso_formacao'] : null;
                                $value = null;
                                if ($strAtivo == true):
                                    $value = 1;
                                elseif ($strAtivo === false):
                                    $value = 0;
                                endif;
                                echo
                                $this->Form->input('curso_formacao', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'options' => $arrTipo,
                                    'value' => $value,
                                    'empty' => __('label_selecione'),
                                    'label' => __('agendamento_input_curso_formacao') . $isRequerid,
                                    'disabled' =>  $formDisabled || $formDisabledHomologa));
                                ?>
                        </div>
                    </div>
                </div>
                <div id="fileCursoFormacao" class="displayNone">
                    <div class="row">
                        <div class="col-md-5">
                            <?php

                            echo
                            $this->Form->input('curso_formacao_certificado', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'type' => 'file',
                                'label' => __('agendamento_curso_formacao_certificado') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>

                        <div class="col-md-5" style="margin-top: 24px;">
                            <?php

                            $existFile = (isset($this->data['Agendamento']['curso_formacao_certificado_path'])  && isset($this->data['Agendamento']['id']));
                            if($existFile){?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                        <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['curso_formacao_certificado_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                        <?if(!$formExtra){?>
                                        <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'curso_formacao_certificado') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                        <?}?>
                                    </ul>
                                </div>
                            <? }?>
                        </div>
                    </div>
                </div>

                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                        <div class="col-md-5">
                            <?php
                            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                            $strAtivo = (isset($this->data['Agendamento']['desvio_funcao'])) ? $this->data['Agendamento']['desvio_funcao'] : null;
                            $value = null;
                            if ($strAtivo == true):
                                $value = 1;
                            elseif ($strAtivo === false):
                                $value = 0;
                            endif;
                            echo
                            $this->Form->input('desvio_funcao', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $arrTipo,
                                'value' => $value,
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_desvio_funcao') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                    </div>
                </div>


                <div class="selectedRiscoVidaInsalubridade displayNone">
                    <div class="row">
                        <div class="col-md-5">
                            <?php
                            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                            $strAtivo = (isset($this->data['Agendamento']['treinamento_desvio_funcao'])) ? $this->data['Agendamento']['treinamento_desvio_funcao'] : null;
                            $value = null;
                            if ($strAtivo == true):
                                $value = 1;
                            elseif ($strAtivo === false):
                                $value = 0;
                            endif;
                            echo
                            $this->Form->input('treinamento_desvio_funcao', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $arrTipo,
                                'value' => $value,
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_treinamento_desvio_funcao') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                    </div>
                </div>
                <div id="selectedTreinamentoDesvioFuncao" class="displayNone">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <?=
                            $this->Form->input('descricao_desvio_funcao', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'class' => 'form-control',
                                'onkeyup' => "limitarTamanho(this,2000);",
                                'onblur' => "limitarTamanho(this,2000);",
                                'label' => __('agendamento_label_descricao_desvio_funcao') . ': ',
                                'disabled' =>  $formDisabled || $formDisabledHomologa,
                                'type' => 'textarea'
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="displayNone" id="hidCAT">
                    <?php echo $this->element('agendamento/cat'); ?>
                </div>
                <div id="afterSolicitarLicenca" class="displayNone ">

                    <div class="row">
                        <div class="col-md-5 div-pad" >
                            <?php
                            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                            $strAtivo = (isset($this->data['Agendamento']['processo_administrativo'])) ? $this->data['Agendamento']['processo_administrativo'] : null;
                            $value = null;
                            if ($strAtivo == true):
                                $value = 1;
                            elseif ($strAtivo === false):
                                $value = 0;
                            endif;
                            echo
                            $this->Form->input('processo_administrativo', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $arrTipo,
                                'value' => $value,
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_processo_administrativo') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>

                        <div class="n-pad displayNone" >
                            <div class="div-pad col-md-3 form-group" >
                                <?php
                                echo $this->Form->hidden('numero_pad', array('id' => 'numero_pad'));
                                $urlAutoCompleteInspecao = Router::url(array('controller' => "Agendamento", 'action' => 'getPAD/'), true);

                                $valor = (isset($this->data['Agendamento']['numero_pad'])) ? $this->data['Agendamento']['numero_pad'] : null;
                                $desabilitarNumPAD = ($formDisabled || $formDisabledHomologa || $acao == Configure::read('ACAO_ALTERAR'));
                                echo
                                $this->Form->input('escolha_pad', array('label' => 'Número PAD' . $isRequerid,
                                    'type' => 'text',
                                    'id' => 'escolha_pad',
                                    'maxlength' => 10,
                                    'data-url' => $urlAutoCompleteInspecao,
                                    'class' => 'form-control',
                                    'value' => $valor,
                                    'disabled' => $desabilitarNumPAD));

                                $displayNone = ($desabilitarNumPAD?'':'displayNone');

                                $txtDisplay = isset($this->data['Agendamento']['numero_pad'])?$this->data['Agendamento']['numero_pad'].' ':'';
                                ?>
                            </div>
                            <div class="col-md-3">
                                <label for="pad_selecionado"> &nbsp;</label>
                                <label id="label_pad_selecionado" class="<?=$displayNone?> form-control" style="border: 0px">PAD Nº <a><?=$txtDisplay?></a></label>
                            </div>
                        </div>
                        <div class="col-md-6 <?=$displayNone?>" >
                            <label for="processo_selecionado"> &nbsp;</label>
                            <label id="label_processo_selecionado" class="form-control" style="border: 0px">Processo / Laudo Nº <a><?=$txtDisplay?></a></label>
                        </div>
                        <div id="div-tratamento_acidente" class="col-md-5 displayNone" >
                            <?php
                            $arrOption = array('1' => 'Sim', '0' => 'Não');

                            echo
                            $this->Form->input('tratamento_acidente', array('div' => array('class' => 'form-group'),
                                'id' => 'tratamento_acidente',
                                'class' => 'form-control',
                                'options' => $arrOption,
                                'empty' => __('label_selecione'),
                                'label' => 'A licença é em decorrência de um acidente de trabalho?' . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>

                        <div id="div-tratamento_acidente_sim" class="col-md-2 displayNone" >
                            <?php
                            echo
                            $this->Form->input('tratamento_acidente_processo', array('div' => array('class' => 'form-group'),
                                'id' => 'tratamento_acidente_processo',
                                'class' => 'form-control',
                                'options' => array(),
                                'type' => 'select',
                                'empty' => __('label_selecione'),
                                'label' => 'Processo CAT' . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>

                        <div class="col-md-2 div-tipo displayNone" >
                            <?php
                            $valor = (isset($this->data['Agendamento']['tipo'])) ? $this->data['Agendamento']['tipo'] : null;

                            $arrTipoTipologia = array(
                                TIPO_SINDICANCIA => 'Sindicância',
                                TIPO_INQUERITO => 'Inquérito',
                                TIPO_PROCESSO_ADMINISTRATIVO => 'PAD'
                            );
                            echo $this->Form->input('tipo', array('div' => array('class' => 'form-group'),
                                'id' => 'tipoTipologia',
                                'class' => 'form-control',
                                'options' => $arrTipoTipologia,
                                'value' => $valor,
                                'empty' => __('label_selecione'),
                                'label' => __('Tipo') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                    </div>

                    <div id="div-recurso_adm" class="row displayNone">
                        <div class="col-md-3 form-group" >
                            <?php
                            echo $this->Form->hidden('numero_processo', array('id' => 'numero_processo'));
                            $urlAutoCompleteInspecao = Router::url(array('controller' => "Agendamento", 'action' => 'getProcesso/'), true);

                            $valor = (isset($this->data['Agendamento']['numero_processo'])) ? $this->data['Agendamento']['numero_processo'] : null;
                            $desabilitarNumProcesso = ($formDisabled || $formDisabledHomologa || $acao == Configure::read('ACAO_ALTERAR'));
                            echo
                            $this->Form->input('escolha_processo', array('label' => 'Número do Processo / Laudo' . $isRequerid,
                                'type' => 'text',
                                'id' => 'escolha_processo',
                                'maxlength' => 10,
                                'data-url' => $urlAutoCompleteInspecao,
                                'class' => 'form-control',
                                'value' => $valor,
                                'disabled' => $desabilitarNumProcesso));

                            $displayNone = ($desabilitarNumProcesso?'':'displayNone');

                            $txtDisplay = isset($this->data['Agendamento']['numero_processo'])?$this->data['Agendamento']['numero_processo'].' ':'';
                            $txtDisplay .= isset($this->data['Agendamento']['tipologia_processo'])?$this->data['Agendamento']['tipologia_processo']:''
                            ?>
                        </div>
                        <div class="col-md-6">
                            <label for="processo_selecionado"> &nbsp;</label>
                            <label id="label_processo_selecionado" class="<?=$displayNone?> form-control" style="border: 0px">Processo / Laudo Nº <a><?=$txtDisplay?></a></label>
                        </div>
                    </div>
                    <style>
                        .withHelper{
                            float:left;
                            width:80%;
                        }
                    </style>


                    <div class="row divDeclaracaoAtribuicao">
                        <div class="col-md-5">
                            <?php
                            
                            echo
                            $this->Form->input('declaracao_atribuicoes', array('div' => array('class' => 'form-group withHelper'),
                                'class' => 'form-control',
                                'type' => 'file',
                                'label' => 'Declaração de Atribuições *',
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                            <div id="helperDeclaracao" style="margin: 23px 0 0 5px; float: left; cursor: help;">
                                <?= $this->Html->image(('question_mark_blue.png')); ?>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top: 24px;">
                            <?php

                            $existFile = (isset($this->data['Agendamento']['declaracao_atribuicoes_path'])  && isset($this->data['Agendamento']['id']));
                            if($existFile){?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                        <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['declaracao_atribuicoes_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                        <?if(!$formExtra){?>
                                            <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'ppp') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                        <?}?>
                                    </ul>
                                </div>
                            <? }?>
                        </div>
                    </div>



                    <div class="row divAposentadoriaEspecial">
                        <div class="col-md-5">
                            <?php
                            //Possui PPP?
                            echo
                            $this->Form->input('ppp', array('div' => array('class' => 'form-group withHelper'),
                                'class' => 'form-control',
                                'type' => 'file',
                                'label' => 'Possui PPP? *',
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                            <div id="helperPPP" style="margin: 23px 0 0 5px; float: left; cursor: help;">
                                <?= $this->Html->image(('question_mark_blue.png')); ?>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top: 24px;">
                            <?php

                            $existFile = (isset($this->data['Agendamento']['ppp_path'])  && isset($this->data['Agendamento']['id']));
                            if($existFile){?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                        <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['ppp_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                        <?if(!$formExtra){?>
                                            <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'ppp') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                        <?}?>
                                    </ul>
                                </div>
                            <? }?>
                        </div>
                    </div>
                    <div class="row divAposentadoriaEspecial">
                        <div class="col-md-5">
                            <?php
                            echo
                            $this->Form->input('ltcat', array('div' => array('class' => 'form-group withHelper'),
                                'class' => 'form-control',
                                'type' => 'file',
                                'label' => 'Possui LTCAT? *',
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                            <div id="helperLTCAT" style="margin: 23px 0 0 5px; float: left; cursor: help;">
                                <?= $this->Html->image('question_mark_blue.png'); ?>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top: 24px;">
                            <?php
                            $existFile = (isset($this->data['Agendamento']['ltcat_path'])  && isset($this->data['Agendamento']['id']));
                            if($existFile){?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                    <ul role="menu" class="dropdown-menu listaAcoes">
                                        <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['ltcat_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                        <?if(!$formExtra){?>
                                            <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'ltcat') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                        <?}?>
                                    </ul>
                                </div>
                            <? }?>
                        </div>
                    </div>
                    <fieldset class="scheduler-border areaCids" data-url="<?=$urlGetCid?>">
                        <legend class="scheduler-border">CIDs</legend>
                        <div class="row">
                            <? $aux = 1;
                            $cids = $this->data['Cids'] ;
                            $cids = empty($cids)?array(array('nome'=>'', 'id'=>'','nome_doenca'=> '' )):$cids;
                            ?>
                            <?php foreach ($cids as $cid): ?>
                                <? $cid= isset($cid['Cid'])?$cid['Cid']:$cid;?>
                                <div class="row itemCid" style="margin-left: 1%" >
                                    <div class="col-md-2" >
                                        <div class="form-group">
                                            <label >Código</label>
                                            <input class="form-control ui-autocomplete-input buscaCid" value="<?= $cid['nome'] ?>" maxlength="255" autocomplete="off" type="text" <?php ($formDisabled) ?  'disabled' : ""  ?>>
                                        </div>

                                        <div class="form-group">
                                            <input class="form-control ui-autocomplete-input selectedCid" name="data[Cids][]" value="<?= $cid['id'] ?>" maxlength="255" autocomplete="off" type="hidden">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label >Descrição</label>
                                            <input class="form-control ui-autocomplete-input descricaoCid" value="<?= $cid['nome_doenca'] ?>" maxlength="255" autocomplete="off" type="text" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-5" style="float: right; margin-top: 1.5%;">
                                        <button class="btn fa fa-plus estiloBotao btn-success btnAddCid" value="true" type="button" <?php ($formDisabled) ?  'disabled' : ""  ?>></button>
                                        <button class="btn fa fa-trash-o estiloBotao btn-danger btnRemCid <?=($aux == 1)?'displayNone':'' ?>" value="true" type="button" > </button>
                                    </div>
                                </div> <!-- END CID 1 -->
                                <?php $aux++; ?>
                            <?php  endforeach; ?>
                        </div>
                    </fieldset>

                    <fieldset class="scheduler-border dadosPretenso displayNone">
                        <legend class="scheduler-border">Dados Pretenso Pensionista</legend>
                            <div class="row" >
                                <div class="col-md-4" >
                                    <div class="form-group">
                                        <?php echo $this->Form->input('cpf_pretenso', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control cpf',
                                            'label' => 'CPF',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa)); ?>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('nome_pretenso', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'label' => 'Nome',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa)); ?>
                                    </div>
                                </div>
                            </div> 
                            <div class="row" >
                                <div class="col-md-3" >
                                    <div class="form-group">
                                        <?php echo $this->Form->input('data_nascimento_pretenso', array('div' => array('class' => 'form-group'),
                                            'type' => 'text',
                                            'class' => 'form-control inputData',
                                            'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                            'onblur' => 'VerificaData(this,this.value)',
                                            'onmouseout' => 'VerificaData(this,this.value)',
                                            'label' => 'Data de Nascimento',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa)); ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('sexo_id_pretenso', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'label' => 'Sexo',
                                            'options' => array('1' => 'Masculino', '2' => 'Feminino'),
                                            'empty' => 'Selecione',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa)); ?>
                                    </div>
                                </div>
                            </div> 
                    </fieldset>

                    <div class="row">
                        <div class="col-md-3">
                        <?
                        echo $this->Form->label(null, __('agendamento_input_licenca_concedida') . $isRequerid, array('class' => 'displayNone', 'id' => 'labelLicencasConcedidas'));
                        echo
                        $this->Form->input('atendimento_vigente_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control displayNone',
                            'id' => 'comboLicencasConcedidas',
                            // 'data-url' => Router::url('/admin/Agendamento/carregarLicencasConcedidas', true),
                            'data-url' => Router::url('/web/Agendamento/carregarLicencasConcedidas', true),
                            'options' => isset($atendimentosVigentes) ? $atendimentosVigentes : array(),
                            'empty' => __('label_selecione'),
                            'label' => false,
                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                        ?>
                        </div>
                        <?php if(Util::temPermissao("Agendamento.domiciliar")){ ?>
                        <div class="col-md-3 col-domiciliar" id="divAtendimentoDomicilio">
                            <?php
                            $tipoUsuario = AuthComponent::user('TipoUsuario')['id'];
                            $perfilUsuario = CakeSession::read('perfil');
                            if($tipoUsuario != TipoUsuario::SERVIDOR_GESTOR && $perfilUsuario != PERFIL_SERVIDOR_GESTOR):
                            ?>
                            <div class="form-group" style="margin-top: 22px">
                                <?php
                                $atendiDisabled = false;
                                if ( $formDisabled || $formDisabledHomologa) {
                                    $atendiDisabled =  $formDisabled || $formDisabledHomologa;
                                }

                                echo $this->Form->input('atendimento_domiciliar', array(
                                    // 'type' => 'checkbox',
                                    'label' => __('Deseja atendimento em domicílio?'),
                                    'div' => array('class' => 'form-group'),
                                    'class' =>false,
                                    'disabled' => $atendiDisabled

                                ));
                                ?>
                            </div>
                            <?php
                            endif; ?>
                        </div>
                        <?php } ?>
                        <div class="col-md-3" id="RowAtendUnidadeProxima" style="display:none">
                            <div class="form-group">
                                <?php
                                     echo $this->Form->input('atend_domicilio_unidade_proxima', array('div' => false,
                                            'class' => 'form-control',
                                            'id' => 'AtendUnidadeProxima',
                                            'options' => array('1' => 'Sim', '0' => 'Não'),
                                            'label' => __('Atendimento será na unidade mais próxima?') . $isRequerid,
                                            'empty' => __('label_selecione'),
                                            // 'url-data' => Router::url('/admin/Agendamento/', true)
                                            'url-data' => Router::url('/web/Agendamento/', true)
                                        )
                                     );

                                ?>
                            </div>
                        </div>

                        <div class="col-md-3" style="display:none" id="RowEnderecoAtendimento">
                                <div class="form-group">
                                    <?php
                                         echo $this->Form->input('atend_domic_endereco', array('div' => false,
                                                'class' => 'form-control',
                                                'id' => 'AtendEndereco',
                                                'options' => array('1' => 'Sim', '0' => 'Não'),
                                                'label' => __('O atendimento será em sua residência?') . $isRequerid,
                                                'empty' => __('label_selecione'),
                                                // 'url-data' => Router::url('/admin/Agendamento/', true)
                                                'url-data' => Router::url('/web/Agendamento/', true)
                                            )
                                         );

                                    ?>
                                </div>
                        </div>


                        <div class="col-md-3" id="RowMunicipio" style="display:none">
                            <div class="form-group">
                                <?php
                                     echo $this->Form->input('municipio_id_atend_domicilio', array(
                                            'div' => false,
                                            'class' => 'form-control',
                                            'id' => 'MunicipioAtendimento',
                                            'options' => $municipiosAtendimento,
                                            'label' => __('Município para atendimento em domicílio') . $isRequerid,
                                            'empty' => __('label_selecione'),
                                            // 'url-data' => Router::url('/admin/Agendamento/', true)
                                            'url-data' => Router::url('/web/Agendamento/', true)
                                        )
                                     );

                                ?>
                            </div>
                        </div>

                        <div class="col-md-3" id="RowUnidadeAtendimento">
                            <?php

                            echo
                            $this->Form->input('unidade_atendimento_id', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control carregarHorarios',
                                'options' => $unidadeAtendimento,
                                'empty' => __('label_selecione'),
                                // 'data-url' => Router::url('/admin/Agendamento/carregarHorariosAtendimento', true),
                                'data-url' => Router::url('/web/Agendamento/carregarHorariosAtendimento', true),
                                'label' => __('agendamento_input_unidade_atendimento') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>

                        <div class="col-md-3 fields-inspecao fields-designacao" id="data-livre">
                            <?php

                            echo
                            $this->Form->input('data_livre', array('label' => __('Data') . $isRequerid,
                                'type' => 'text',
                                'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                'onblur' => 'VerificaData(this,this.value)',
                                'onmouseout' => 'VerificaData(this,this.value)',
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                        <div class="col-md-3 fields-inspecao fields-designacao" id="hora-livre">
                            <?php
                            echo
                            $this->Form->input('hora_livre', array('label' => __('Hora') . $isRequerid,
                                'type' => 'text',
                                'class' => 'form-control hour',
                                'onblur' => 'if(!validarHora(this.value))invalideHour(this);',
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                        <div class="col-md-3 fields-inspecao" id="div-orgao">
                            <?php
                            echo
                            $this->Form->input('orgao', array('label' => __('Hora') . $isRequerid,
                                'options' => $orgaoOrigem,
                                'empty' => __('label_selecione'),
                                'label' => 'Local – Orgão*',
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12"  style="display:none" id="RowCadastroEnderecoAtendimento">
                                <?php
                                echo $this->element('componente_endereco', ['model' => 'EnderecoAtendimentoDomicilio', 'requerid' => '',
                                    'municipios' => (isset($municipiosUsuarios)) ? $municipiosUsuarios : [],
                                    'idComboMunicipio' => 'EnderecoAtendimentoDomicilioMunicipioId'])
                                ?>
                        </div>
                    </div>
                    <div class="row row-periodo" id="divEncaixeDiaDataHora">

                   <?php if(Util::temPermissao("Agendamento.encaixe")): ?>
                        <?php if (CakeSession::read('Auth.User.tipo_usuario_id') == USUARIO_INTERNO): ?>
                            <div class="col-md-1" >
                                <div class="form-group">
                                    <label for="hidEncaixe"><?= __('agendamento_input_encaixe'); ?></label>
                                    <?php
                                    $encaixeDisabled = true;
                                    if ($currentAction !== 'deletar') {
                                        if (empty($dataHoraAgenda)) {
                                            $encaixeDisabled = false;
                                        }
                                        if ( $formDisabled || $formDisabledHomologa) {
                                            $encaixeDisabled =  $formDisabled || $formDisabledHomologa;
                                        }
                                    }

                                    echo $this->Form->checkbox('encaixe', array(
                                        'disabled' => $encaixeDisabled,
                                        // 'data-url' => Router::url('/admin/Agendamento/carregarHorariosAtendimento', true),
                                        'data-url' => Router::url('/web/Agendamento/carregarHorariosAtendimento', true),
                                        'class' => 'checkEncaixe carregarHorarios',
                                        'id' => 'hidEncaixe'
                                    ));
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <?php
                            echo
                            $this->Form->input('dia_semana', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control carregarHorarios',
                                'options' => $diasSemana,
                                'id' => 'comboDiaSemana',
                                // 'data-url' => Router::url('/admin/Agendamento/carregarHorariosAtendimento', true),
                                'data-url' => Router::url('/web/Agendamento/carregarHorariosAtendimento', true),
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_dia_semana') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                        <?php if(!isset($isVisualizar)): ?>
                        <div class="col-md-3" >

                            <?php
                            if (isset($this->data['Agendamento']['data_hora']) && !empty($this->data['Agendamento']['data_hora'])) :
                                echo "<!-- ".$this->data['Agendamento']['data_hora']." || ".Util::toDBDataHora($this->data['Agendamento']['data_hora']) ." -->";
                                $this->request->data['Agendamento']['data_hora'] = date("d/m/Y H:i", strtotime(Util::toDBDataHora($this->data['Agendamento']['data_hora'])));
                            endif;

                            echo $this->Form->hidden('data_hora', array(
                                'id' => 'inputDataHora',
                                'disabled' =>  $formDisabled || $formDisabledHomologa
                            ));
                            echo $this->Form->input('agenda_atendimento_id', array(
                                'type' => 'hidden',
                                'id' => 'agendaAtendimentoId',
                                'value' => '',
                                'disabled' =>  $formDisabled || $formDisabledHomologa
                            ));
                            echo
                            $this->Form->input('data_hora', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => array(),
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_data_hora') . $isRequerid,
                                'disabled' =>  $formDisabled || $formDisabledHomologa));
                            ?>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($isVisualizar)): ?>
                            <div class="col-md-3" >

                                <?php

                                echo $this->Form->input('data_hora_label', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'label' => __('agendamento_input_data_hora') . $isRequerid,
                                    'disabled' =>  $formDisabled || $formDisabledHomologa,
                                    'value' => date("d/m/Y H:i", strtotime(Util::toDBDataHora($this->data['Agendamento']['data_hora'])))
                                    ));
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="div-oficio" class="displayNone">
                        <div class="row">
                            <div class="col-md-5">
                                <?php
                                echo
                                $this->Form->input('oficio', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'type' => 'file',
                                    'label' => __('Ofício'),
                                    'disabled' =>  $formDisabled || $formDisabledHomologa));
                                ?>
                            </div>

                            <div class="col-md-5" style="margin-top: 24px;">
                                <?php

                                $existFile = (isset($this->data['Agendamento']['oficio_path'])  && isset($this->data['Agendamento']['id']));
                                if($existFile){?>
                                    <div class="btn-group">
                                        <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                        <ul role="menu" class="dropdown-menu listaAcoes">
                                            <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['oficio_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                            <?if(!$formExtra){?>
                                                <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFile',$id,'oficio') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                            <?}?>
                                        </ul>
                                    </div>
                                <? }?>
                            </div>
                        </div>
                    </div>
                    <?php
                    //#
                    ?>



                    <div class="row displayNone" id="fieldSetInformacoesAcompnhado">
                        <div class="col-md-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('agendamento_input_informacoes_acompanhado') ?></legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        echo $this->Form->input('acompanhado_cid_id', array(
                                            'type' => 'hidden',
                                            'id' => 'hidAcompanhadoCidId',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa
                                        ));
                                        echo
                                        $this->Form->input('searchAcompnhadoCid', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'id' => 'acompanhadoCidId',
                                            'label' => __('agendamento_input_acompanhado_cid') . $isRequerid,
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        echo
                                        $this->Form->input('searchAcompnhadoDoenca', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'id' => 'acompanhadoDoencaId',
                                            'label' => __('agendamento_input_acompanhado_doenca') . $isRequerid,
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        echo
                                        $this->Form->input('nome_acompanhado_sem_abreviacao', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'id' => 'acompanhadoNomeSemAbreviacao',
                                            'label' => __('nome_sem_abreviacao') . $isRequerid,
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        echo
                                        $this->Form->input('data_nascimento_acompanhado', array('label' => __('agendamento_data_nascimento_acompanhado') . $isRequerid,
                                            'type' => 'text',
                                            'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                            'onblur' => 'VerificaData(this,this.value)',
                                            'onmouseout' => 'VerificaData(this,this.value)',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php
                                        echo
                                        $this->Form->input('certidao_nascimento_acompanhado', array('label' => __('agendamento_certidao_nascimento_acompanhado') . $isRequerid,
                                            'class' => 'form-control',
                                            'id' => 'certidaoNascimentoAcompanhado',
                                            'label' => __('agendamento_certidao_nascimento_acompanhado'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <?php
                                        echo
                                        $this->Form->input('cpf_acompanhado', array('label' => __('agendamento_cpf_acompanhado') . $isRequerid,
                                            'class' => 'form-control cpf',
                                            'id' => 'cpfAcompanhado',
                                            'label' => __('agendamento_cpf_acompanhado'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <?php
                                        echo
                                        $this->Form->input('rg_acompanhado', array('label' => __('agendamento_rg_acompanhado') . $isRequerid,
                                            'class' => 'form-control soNumero',
                                            'id' => 'rgAcompanhado',
                                            'label' => __('agendamento_rg_acompanhado'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <?php
                                        echo
                                        $this->Form->input('orgao_expedidor_acompanhado', array('label' => __('agendamento_orgao_expedidor_acompanhado') . $isRequerid,
                                            'class' => 'form-control',
                                            'id' => 'orgaoExpedidorAcompanhado',
                                            'label' => __('agendamento_orgao_expedidor_acompanhado'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <?php
                                        echo
                                        $this->Form->input('nome_mae_acompanhado', array('label' => __('agendamento_nome_mae_acompanhado') . $isRequerid,
                                            'class' => 'form-control',
                                            'id' => 'nomeMaeAcompanhado',
                                            'label' => __('agendamento_nome_mae_acompanhado'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <?php
                                        echo
                                        $this->Form->input('qualidade_id', array('label' => __('agendamento_qualidade') . $isRequerid,
                                            'class' => 'form-control',
                                            'options' => $qualidades,
                                            'empty' => __('label_selecione'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-3 form-group qualidadeOutros">
                                        <?php
                                        echo
                                        $this->Form->input('outros', array('label' => __('agendamento_outros'),
                                            'class' => 'form-control',
                                            'empty' => __('label_selecione'),
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo __('agendamento_tratamento_fora_domicilio');
                                        echo $this->Form->radio('tratamento_fora_municipio', [true => 'Sim', false => 'Não'], array('default' => __('Atendimento fora do'),
                                            'legend' => false, 'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo __('agendamento_assistencia_incompativel_cargo');
                                        echo $this->Form->radio('assistencia_incompativel_cargo', [true => 'Sim', false => 'Não'], array('default' => __('Atendimento fora do'),
                                            'legend' => false, 'class' => 'ClassAssistenciaIncompativelCargo', 'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                                <div class="row displayNone" id="divPorqueAssistenciaIncompativel">
                                    <div class="col-md-12">
                                        <?php
                                        echo $this->Form->input('porque_assistencia_incompativel_cargo', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'id' => 'porqueAssistenciaIncompativel',
                                            'type' => 'textarea',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa,
                                            'onkeyup' => "limitarTamanho(this,1000);",
                                            'onblur' => "limitarTamanho(this,1000);",
                                            'label' => __('agendamento_porque_assistencia_incompativel_cargo')));
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo $this->Form->input('porque_voce_unica_pessoa_cuidar', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'id' => 'porqueVoceUnicaPessoaCuidar',
                                            'type' => 'textarea',
                                            'disabled' =>  $formDisabled || $formDisabledHomologa,
                                            'onkeyup' => "limitarTamanho(this,1000);",
                                            'onblur' => "limitarTamanho(this,1000);",
                                            'label' => __('agendamento_porque_voce_unica_pessoa_cuidar')));
                                        ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row chefiaImediata">
                        <div class="col-md-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('agendamento_input_chefia_imediata') ?></legend>
                                <div class="row">
                                    <table class="table table-striped table-hover table-bordered" id="tableFuncao">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%"><?= __('agendamento_input_orgao_origem') ?></th>
                                                <th style="width: 25%"><?= __('agendamento_input_lotacao') ?></th>
                                                <th id="hNomeChefe" style="width: 25%"><?= __('agendamento_input_nome_chefe') . $isRequerid ?></th>
                                                <th id="hMatriculaChefe" style="width: 25%"><?= __('agendamento_input_matricula') . $isRequerid ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyFuncao">
                                            <!-- Chefe Imediato UM -->
                                            <tr class="">
                                                <td>
                                                    <?php
                                                    echo $this->Form->input('getmsgvalidate', array(
                                                        'id' => 'getmsgvalidate',
                                                        'value' => '',
                                                        'label' => false,
                                                        'style' => 'display:none;'
                                                    ));
                                                    echo $this->Form->input('chefe_imediato_um_id', array(
                                                        'type' => 'hidden',
                                                        'id' => 'hidChefeImediatoUmId',
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa
                                                    ));

                                                    echo
                                                    $this->Form->input('chefe_imediato_um_orgao_origem_id', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control orgaoOrigemChefia',
                                                        'options' => $orgaoOrigem,
                                                        'id' => 'ChefiaImediataUmOrgaoOrigemId',
                                                        'data-chefia' => 'Um',
                                                        'empty' => __('label_selecione'),
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('chefe_imediato_um_lotacao_id', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control lotacaoChefia',
                                                        'options' => $lotacoesUm,
                                                        'id' => 'ChefiaImediataUmLotacao',
                                                        'data-chefia' => 'Um',
                                                        'empty' => __('label_selecione'),
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('nomeChefiaUm', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control chefiaNome chefias',
                                                        'id' => 'chefiaImediataUm',
                                                        'data-chefia' => 'Um',
                                                        'maxlength' => '150',
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('matriculaChefiaUm', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control chefias',
                                                        'id' => 'chefiaImediataMatriculaUm',
                                                        'data-chefia' => 'Um',
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                            </tr>
                                            <!-- Chefe Imediato Dois -->
                                            <tr class="">
                                                <td>
                                                    <?php
                                                    echo $this->Form->input('chefe_imediato_dois_id', array(
                                                        'type' => 'hidden',
                                                        'id' => 'hidChefeImediatoDoisId',
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa
                                                    ));

                                                    echo
                                                    $this->Form->input('chefe_imediato_dois_orgao_origem_id', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control orgaoOrigemChefia',
                                                        'options' => $orgaoOrigem,
                                                        'data-chefia' => 'Dois',
                                                        'id' => 'ChefiaImediataDoisOrgaoOrigemId',
                                                        'empty' => __('label_selecione'),
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('chefe_imediato_dois_lotacao_id', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control lotacaoChefia',
                                                        'options' => $lotacoesDois,
                                                        'id' => 'ChefiaImediataDoisLotacao',
                                                        'data-chefia' => 'Dois',
                                                        'empty' => __('label_selecione'),
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('nomeChefiaDois', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control chefiaNome chefias',
                                                        'id' => 'chefiaImediataDois',
                                                        'data-chefia' => 'Dois',
                                                        'maxlength' => '150',
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('matriculaChefiaDois', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control chefias',
                                                        'id' => 'chefiaImediataMatriculaDois',
                                                        'data-chefia' => 'Dois',
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                            </tr>
                                            <!-- Chefe Imediato TRÊS -->
                                            <tr class="">
                                                <td>
                                                    <?php
                                                    echo $this->Form->input('chefe_imediato_tres_id', array(
                                                        'type' => 'hidden',
                                                        'id' => 'hidChefeImediatoTresId',
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa
                                                    ));

                                                    echo
                                                    $this->Form->input('chefe_imediato_tres_orgao_origem_id', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control orgaoOrigemChefia',
                                                        'options' => $orgaoOrigem,
                                                        'data-chefia' => 'Tres',
                                                        'id' => 'ChefiaImediataTresOrgaoOrigemId',
                                                        'empty' => __('label_selecione'),
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('chefe_imediato_tres_lotacao_id', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control lotacaoChefia',
                                                        'options' => $lotacoesTres,
                                                        'id' => 'ChefiaImediataTresLotacao',
                                                        'data-chefia' => 'Tres',
                                                        'empty' => __('label_selecione'),
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('nomeChefiaTres', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control chefiaNome chefias',
                                                        'id' => 'chefiaImediataTres',
                                                        'data-chefia' => 'Tres',
                                                        'label' => false,
                                                        'maxlength' => '150',
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo
                                                    $this->Form->input('matriculaChefiaTres', array('div' => array('class' => 'form-group'),
                                                        'class' => 'form-control chefias',
                                                        'data-chefia' => 'Tres',
                                                        'id' => 'chefiaImediataMatriculaTres',
                                                        'label' => false,
                                                        'disabled' =>  $formDisabled || $formDisabledHomologa));
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row fields-designacao">
                        <div class="col-lg-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('Perito') ?></legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?php
                                        $cpfPerito='';
                                        if (isset($this->request->data['DesignacaoUsuarioPerito'])) {
                                            $cpfPerito= $this->request->data['DesignacaoUsuarioPerito']['cpf'];
                                        }
                                        echo
                                        $this->Form->input('cpf_perito', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control cpf',
                                            'id' => 'cpfPerito',
                                            'value' => $cpfPerito,
                                            'label' => __('CPF') . $isRequerid,
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?php
                                        $nomePerito='';
                                        if (isset($this->request->data['DesignacaoUsuarioPerito'])) {
                                            $nomePerito= $this->request->data['DesignacaoUsuarioPerito']['nome'];
                                        }
                                        echo
                                        $this->Form->input('nome_perito', array('div' => array('class' => 'form-group'),
                                            'class' => 'form-control',
                                            'id' => 'nomePerito',
                                            'maxlength' => '150',
                                            'value' => $nomePerito,
                                            'label' => __('Nome') . $isRequerid,
                                            'disabled' =>  $formDisabled || $formDisabledHomologa));
                                        ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="divConfirmaDivulgacao">
                                <style>
                                    .fleft{
                                        float:left;
                                    }
                                    .confirmarDivulgacao{
                                        width: 90%;
                                        margin-left: 1%;
                                        margin-bottom: 20px;
                                    }
                                </style>
                                <?php
                                echo $this->Form->input('validacao_autorizacao_cmf', array('class' => 'displayNone', 'label' => false));
                                echo $this->Form->checkbox('confirmar_divulgacao', array('div' => array('class' => 'form-group fleft'),
                                    'disabled' =>  $formDisabled || $formDisabledHomologa,
                                    'id' => 'hidAutorizoDivulgacao'
                                ));
                                ?>
                                <label id="labelAutorizoDivulgacao" for="hidAutorizoDivulgacao" class="confirmarDivulgacao"><?//CONTEUDO NO JS configuracaoPadrao();?></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group displayNone" id="divConfirmaAcordo" >
                                <?php
                                echo $this->Form->input('validacao_acordo_risco_vida', array('class' => 'displayNone', 'label' => false));
                                echo $this->Form->checkbox('confirmar_acordo_insalubridade', array('div' => array('class' => 'form-group'),
                                    'id' => 'hidAutorizoAcordo'
                                ));
                                ?>
                                <label for="hidAutorizoAcordo"><?= __('agendamento_input_acordo_risco_vida_insalubridade'); ?></label>
                            </div>
                        </div>
                        <div class="col-md-5 <?=(($formDisabledHomologa)?'':'displayNone')?>"  >
                            <?php
                            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                            $strAtivo = (isset($this->data['Agendamento']['homologa'])) ? $this->data['Agendamento']['homologa'] : null;
                            $value = null;
                            if ($strAtivo == true):
                                $value = 1;
                            elseif ($strAtivo === false):
                                $value = 0;
                            endif;
                            echo
                            $this->Form->input('homologa', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $arrTipo,
                                'value' => $value,
                                'empty' => __('label_selecione'),
                                'label' => __('agendamento_input_homologar') . $isRequerid,
                                'disabled' => $formDisabled && $formDisabledHomologa));
                            ?>
                        </div>
                    </div>

                    <div class="float-right">
                        <?php
                        if ($currentAction === 'deletar') {
                            if ($acao == Configure::read('ACAO_EXCLUIR')) {
                                echo $this->Form->button(__('bt_excluir'), array(
                                    'class' => 'btn fa fa-trash-o estiloBotao btn-danger',
                                ));
                            }

                            $urlConsulta = Router::url(array('controller' => $controller, 'action' => 'index'));

                            echo $this->Form->button(__('bt_ir_consulta'), array(
                                'class' => 'btn fa fa-search estiloBotao btn-info',
                                'type' => 'button',
                                'onclick' => "location.href = '$urlConsulta'"
                            ));
                        }
                        ?>

                        <?php if ($acao == Configure::read('ACAO_INSERIR') || $acao == Configure::read('ACAO_ALTERAR')  || $acao == Configure::read('ACAO_HOMOLOGAR')): ?>
                            <!-- <i class="btn fa fa-check-square-o estiloBotao btn-success" id="btSave" data-url="<?= Router::url('/admin/Agendamento/', true); ?>"> -->
                            <i class="btn fa fa-check-square-o estiloBotao btn-success" id="btSave" data-url="<?= Router::url('/web/Agendamento/', true); ?>">
                                <?= __('bt_confirmar') ?>
                            </i>
                        <?php endif; ?>
                        <?php
                        $urlCadastro = Router::url(array('controller' => "$controller", 'action' => 'adicionar'));

                        if ($acao == Configure::read('ACAO_INSERIR')) {
                            echo $this->Form->button(__('bt_cancelar'), array(
                                'class' => 'btn fa fa-eraser estiloBotao btn-danger',
                                'type' => 'button',
                                'onclick' => "location.href = '$urlCadastro'"
                            ));
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Modal informando caso a tipologia insalubridade seja selecionada -->
<div id="dialog-alerta-tipologia-insalubridade" class="displayNone">
    <p><?= __('agendamento_mensagem_tipologia_insalubridade') ?></p>
    <p><?= __('agendamento_mensagem_tipologia_insalubridade2') ?></p>
</div>

<!-- Modal informativo caso o usuário não concorde com a resolução CMF nº 1658/2002 -->
<div id="dialog-alerta-resolucao-cmf" class="displayNone">
    <p><?= __('agendamento_usuario_nao_concorda_resolucao_cmf') ?></p>
</div>
<!-- Modal informativo caso o usuário escolha a tipologia Licença de Acompanhamento familiar -->
<div id="dialog-alerta-tipologia-licenca-familiar" class="displayNone">
    <p><?= __('agendamento_sujeito_comprovacao_acompanhado') ?></p>
</div>
<?= $this->element('dialog_validacao_acompanhado') ?>
<!-- Modal informativo caso já exista uma agendamento vigente -->
<div id="dialog-alerta-unicidade-agendamento-vigente" class="displayNone">
    <p><?= __('agendamento_vigente_existente') ?></p>
</div>
<style>
    .zTop{z-index:999}
</style>
<!-- Modal de alerta de PAD -->
<div id="dialog-alerta-PAD" class="displayNone zTop" >
    <p>Para servidores respondendo há um PAD será preciso informar o mesmo.</p>
</div>

<div id="dialog-alerta" class="displayNone zTop" >
    <p>Texto Personalizado</p>
</div>
<div id="dialog-alerta-CAT2" class="displayNone zTop" >
    <p>É preciso escolher quem é o servidor da solicitação.</p>
</div>

<div id="dialog-PPP" class="displayNone zTop" >
    <p>PPP – Perfil Profissiográfico Previdenciario - Onde encontrar? RH de sua instituição.</p>
</div>

<div id="dialog-Declaracao" class="displayNone zTop" >
    <p>Declaração das Atribuições preenchida e assinada pela Chefia Imediata</p>
</div>

<div id="dialog-LTCAT" class="displayNone zTop" >
    <p>LTCAT – Laudo Técnico de Condições Ambientais do Trabalho - Onde encontrar? RH de sua instituição.</p>
</div>


<div id="dialog-ConfirmarAgendamento" class="displayNone zTop" >
    <p>É necessário apresentar-se para o atendimento com 15 minutos de antecedência para confirmar a presença, sob risco de ter que reagendar o atendimento para outra data.</p>
</div>

<div id="hidden-dialog" class="displayNone"></div>

<?= $this->Form->end(); ?>

<?//$this->Html->script('Admin.agendamento', array('block' => 'script')); ?>
<?= $this->Html->script('agendamento', array('block' => 'script')); ?>
<? //echo $this->Html->css('Admin.tokenize2', array('block' => 'script')); ?>
<? //echo $this->Html->script('Admin.tokenize2', array('block' => 'script')); ?>
<? //echo $this->Html->script('Admin.loadcid', array('block' => 'script')); ?>

<?= $this->Html->css('Web.tokenize2', array('block' => 'script')); ?>
<?= $this->Html->script('Web.tokenize2', array('block' => 'script')); ?>
<?= $this->Html->script('Web.loadcid', array('block' => 'script')); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        //$("#labelExigencia").parent().hide();

        if($('#chkbxExigencia').is(":checked"))
            $("#labelExigencia").parent().parent().toggle(); 

        $("#chkbxExigencia").click(function(){
           $("#labelExigencia").parent().parent().toggle(); 
        });


        function carregaCid(target) {
            var url = $('.areaCids').data('url');
            var autocompleteTarget = $(target).find('.buscaCid');
            var selectedCid = $(target).find('.selectedCid');
            var descricaoCid = $(target).find('.descricaoCid');
            $(autocompleteTarget).autocomplete({
                source: url,
                response: function (event, ui) {
                    $(selectedCid).val('');
                    $(descricaoCid).val('');
                },
                select: function (a, b) {
                    $(selectedCid).val(b.item.id);
                    $(descricaoCid).val(b.item.descricao);
                    mudancaCid();//agendamento.js
                }
            });
        }
        var itemCid = $('.areaCids .itemCid:first').clone();
        function replicaCid(){
            var newItem = itemCid.clone();
            $('.areaCids .row:first').append(newItem);
            $('.itemCid:last .btnRemCid').removeClass('displayNone');
            $('.itemCid:last input').val('');
            carregaCid(newItem);
        }

        function removeCid(target){
            $(target).parents('.itemCid:first').remove();
            mudancaCid();//agendamento.js
        }
        $('.areaCids').on('click', '.btnAddCid', replicaCid);
        $('.areaCids').on('click', '.btnRemCid', function(){ removeCid(this)});
        $('.areaCids .itemCid').each(function(){
            carregaCid(this);
        });

        $( "#comboTipologia" ).on( "change", function() {
            $("#escolha_exig").empty();
            $("#escolha_exig").val(' ');
        });

        $("#AgendamentoUnidadeAtendimentoId").prop('disabled',true);

    });

</script>