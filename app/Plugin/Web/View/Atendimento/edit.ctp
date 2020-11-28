<?php
// pr($arrAtendimento);die;

$formCreate['class'] = ($formDisabled) ? "formVisualizacao" : "";
echo $this->Form->create($controller, $formCreate);
echo $this->Form->input('usuario_id', array(
    'type' => 'hidden',
    'id' => 'hidUsuarioId',
    'value' => $dadosServidor['Usuario']['id']));

echo $this->Form->hidden('agendamento_id', array('id' => 'hidAgendamentoId'));
echo $this->Form->hidden('dependente_id', array( 'id' => 'hidDependenteId'));
echo $this->Form->hidden('atedimento_pai_id', array( 'id' => 'hidAtendimentoPaiId'));
echo $this->Form->hidden('status_atendimento', array( 'id' => 'hidStatusAtendimento'));
echo $this->Form->hidden('Agendamento.tipologia_id', array( 'id' => 'hidtipologia'));

echo $this->Form->hidden('tipologiaOrig', array(
    'value' => $tipologiaAgendamento,
    'id' => 'tipologiaOrig'));

echo $this->Form->hidden('emitir_laudo', array( 'id' => 'hidEmitirLaudo', 'value' => false));
	
echo $this->Form->input('vinculo_risco_vida_insalubridade', array(
    'type' => 'hidden',
    'id' => 'vinculo_risco_vida_insalubridade',
    'value' => $this->request->data['Agendamento']['vinculo'],
	 'disabled' => true
	));

echo $this->Form->input('ctd_vinculo_risco_vida_insalubridade', array(
    'type' => 'hidden',
    'id' => 'ctd_vinculo_risco_vida_insalubridade',
    'value' => CTD,
	 'disabled' => true
	));
?>

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

echo $this->PForm->hidden('tipologia_licenca_maternidade', TIPOLOGIA_LICENCA_MATERNIDADE);
echo $this->PForm->hidden('tipologia_licenca_maternidade_aborto', TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO);
echo $this->PForm->hidden('tipologia_licenca_natimorto', TIPOLOGIA_LICENCA_NATIMORTO);
echo $this->PForm->hidden('tipologia_aposentadoria_invalidez', TIPOLOGIA_APOSENTADORIA_INVALIDEZ);
echo $this->PForm->hidden('tipologia_isencao_contribuicao_previdenciaria', TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA);
echo $this->PForm->hidden('tipologia_reversao_aposentadoria_invalidez', TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ);
echo $this->PForm->hidden('tipologia_avaliacao_habilitacao_dependentes', TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES);
echo $this->PForm->hidden('tipologia_admissao_pensionista_maior_invalido', TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO);
echo $this->PForm->hidden('tipologia_informacao_seguro_compreensivo_habitacional', TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL);
echo $this->PForm->hidden('tipologia_readaptacao_funcao', TIPOLOGIA_READAPTACAO_FUNCAO);
echo $this->PForm->hidden('tipologia_remanejamento_funcao', TIPOLOGIA_REMANEJAMENTO_FUNCAO);
echo $this->PForm->hidden('tipologia_risco_vida_insalubridade', TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE);
echo $this->PForm->hidden('tipologia_recurso_administrativo', TIPOLOGIA_RECURSO_ADMINISTRATIVO);
echo $this->PForm->hidden('tipologia_exame_pre_admissional', TIPOLOGIA_EXAME_PRE_ADMISSIONAL);
echo $this->PForm->hidden('tipologia_licenca_acompanhamento_familiar', TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR);
echo $this->PForm->hidden('tipologia_atencipacao_licenca', TIPOLOGIA_ATECIPACAO_LICENCA);
echo $this->PForm->hidden('tipologia_licenca_medica_tratamento_saude', TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE);
echo $this->PForm->hidden('tipologia_sindicancia_inquerito_pad', TIPOLOGIA_SINDICANCIA_INQUERITO_PAD);
echo $this->PForm->hidden('tipologia_comunicacao_de_acidente_de_trabalho', TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO);
echo $this->PForm->hidden('tipologia_aposentadoria_especial',  TIPOLOGIA_APOSENTADORIA_ESPECIAL);
echo $this->PForm->hidden('tipologia_remocao',  TIPOLOGIA_REMOCAO);

echo $this->Form->input('acaoTela', array(
    'type' => 'hidden',
    'id' => 'acaoTela',
    'value' => $this->params['action'],
    'disabled' => true
));
?>
<?php
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
            <?= $this->element('titulo-pagina', array('titulo' => $tituloHeadEdit)); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('atendimento_laudo_label_dados_servidor') ?> </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    echo
                                    $this->Form->input('Usuario.nome', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'id' => 'nomeServidor',
                                        'maxlength' => '150',
                                        'value' => $dadosServidor['Usuario']['nome'],
                                        'label' => __('atendimento_laudo_label_nome'),
                                        'disabled' => true));
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <?php
                                    echo
                                    $this->Form->input('Usuario.cpf', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control cpf',
                                        'id' => 'cpfServidor',
                                        'label' => __('atendimento_laudo_label_cpf'),
                                        'value' => $dadosServidor['Usuario']['cpf'],
                                        'disabled' => true));
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    echo
                                    $this->Form->input('Usuario.sexo_id', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'options' => $sexo,
                                        'label' => __('usuario_label_sexo'),
                                        'value' => $dadosServidor['Usuario']['sexo_id'],
                                        'empty' => __('label_selecione'),
                                        'disabled' => true));
                                    ?>
                                </div>
                                <div class="col-md-3">
                                    <?php
                                    echo
                                    $this->Form->input('Usuario.data_nascimento', array('label' => __('atendimento_laudo_label_data_nascimento'),
                                        'type' => 'text',
                                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)',
                                        'value' => $dadosServidor['Usuario']['data_nascimento'],
                                        'disabled' => true));
                                    ?>
                                </div>
                                <div class="col-md-2 float-left">
                                    <?= $this->Form->label(null, "  "); ?>
                                    <?php
                                    if (isset($dadosServidor['Usuario']['idade'])):
                                        ?>
                                        <?= $this->Form->label(null, $dadosServidor['Usuario']['idade'] . " Anos", array('class' => 'form-control', 'style' => 'border: none;')); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
	                        <?php if( $tipologiaAgendamento == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO ): ?>
                            <div class="row">
                                <div class="col-md-2 float-left">
                                    <?php echo
                                    $this->Form->input('Agendamento.data_obito', array('label' => 'Data de Óbito',
                                        'type' => 'text',
                                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)',
                                        'value' => Util::toBrData($this->data['Agendamento']['data_obito']),
                                        'disabled' => true)); ?>

	                              <!--   <label>
                                    Data de Óbito
	                                </label><br> -->
	                                <?php
	                                // echo Util::toBrData($this->data['Agendamento']['data_obito']);
	                                ?>
                                </div>
                                <div class="col-md-2 float-left">

                                </div>
                            </div>
							<?php endif; ?>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('atendimento_laudo_label_vinculos') ?> </legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="tableVinculo">
                                        <thead>
                                            <tr>
                                                <th style="width: 19%"><?= __('atendimento_laudo_label_orgao_origem'); ?></th>
                                                <th style="width: 19%"><?= __('atendimento_laudo_label_cargo'); ?></th>
                                                <th style="width: 19%"><?= __('atendimento_laudo_label_funcoes'); ?></th>
                                                <th style="width: 19%"><?= __('atendimento_laudo_label_lotacoes'); ?></th>
                                                <th style="width: 19%"><?= __('atendimento_laudo_label_data_admissao_anos_servico'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($dadosServidor['Vinculos'])): ?>
                                                <tr id="emptyVinculo" class="displayNone">
                                                    <td colspan="6" style="text-align: center;">
                                                        <?= __('nenhum_registro_encontrado') ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                if (!empty($dadosServidor['Vinculos'])):
                                                    foreach ($dadosServidor['Vinculos'] as $key => $line) :
                                                        ?>
                                                        <tr class="">
                                                            <td><?= $line['OrgaoOrigem']['orgao_origem']; ?></td>
                                                            <td><?= $line['Cargo']['nome']; ?></td>
                                                            <td><?= $line['funcao']['nome']; ?></td>
                                                            <td><?= $line['lotacao']['nome']; ?></td>
                                                            <td><?= isset($line['Vinculo']['data_admissao_servidor'])?(date('d/m/Y', strtotime($line['Vinculo']['data_admissao_servidor'])) . $line['Vinculo']['stringVerificaAnos']):''; ?></td>
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    ?>
                                                    <tr id="emptyVinculo">
                                                        <td colspan="5" style="text-align: center;">
                                                            <?= __('nenhum_registro_encontrado') ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <tr id="emptyVinculo">
                                            <td colspan="6" style="text-align: center;">
                                                <?= __('nenhum_registro_encontrado') ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </fieldset>
                        
                        <?php if(in_array($tipologiaAgendamento, array(TIPOLOGIA_READAPTACAO_FUNCAO, TIPOLOGIA_REMANEJAMENTO_FUNCAO, TIPOLOGIA_REMOCAO))):?>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Arquivos</legend>
                                <div class="row">
                                    <?php
                                    $existFile = (isset($this->data['Agendamento']['declaracao_atribuicoes_path'])  && isset($this->data['Agendamento']['id']));
                                    if($existFile){?>
                                    <div class="col-md-3" >
                                        <label style="font-weight: bold">Declaração de Atribuições &nbsp;</label>
                                        <div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                            <ul role="menu" class="dropdown-menu listaAcoes">
                                                <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['declaracao_atribuicoes_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <? }?>
                                </div>
                            </fieldset>
                        <?php endif; ?>


                        <?php if(in_array($tipologiaAgendamento, array(TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))):?>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Arquivos</legend>
                                <div class="row">
                                    <?php
                                    $existFile = (isset($this->data['Agendamento']['ppp_path'])  && isset($this->data['Agendamento']['id']));
                                    if($existFile){?>
                                    <div class="col-md-3" >
                                        <label style="font-weight: bold">PPP &nbsp;</label>
                                        <div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                            <ul role="menu" class="dropdown-menu listaAcoes">
                                                <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['ppp_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <? }?>
                                    <?php
                                    $existFile = (isset($this->data['Agendamento']['ltcat_path'])  && isset($this->data['Agendamento']['id']));
                                    if($existFile){?>
                                        <div class="col-md-3">
                                            <label style="font-weight: bold">LTCAT &nbsp;</label>
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                                <ul role="menu" class="dropdown-menu listaAcoes">
                                                    <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['ltcat_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                                </ul>
                                            </div>

                                        </div>
                                    <? }?>
                                    <?php
                                    $existFile = (isset($this->data['Agendamento']['oficio_path'])  && isset($this->data['Agendamento']['id']));
                                    if($existFile){?>
                                        <div class="col-md-3" >
                                            <label style="font-weight: bold">Ofício &nbsp;</label>
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo __('label_arquivo') ?> <span class="caret"></span></button>
                                                <ul role="menu" class="dropdown-menu listaAcoes">
                                                    <li><?php echo $this->Html->link(__('bt_file'),$this->data['Agendamento']['oficio_path'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <? }?>
                                </div>
                            </fieldset>
                        <?php endif; ?>

                        <?php if ($tipologiaAgendamento == TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES || $tipologiaAgendamento == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO): ?>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('atendimento_laudo_label_dependente') ?> </legend>
                                <div class="row">
                                    <table class="table table-striped table-hover table-bordered" id="tableVinculo">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th><?= __('atendimento_laudo_label_nome'); ?></th>
                                                <th><?= __('atendimento_laudo_label_data_nascimento'); ?></th>
                                                <th><?= __('atendimento_laudo_label_cpf'); ?></th>
                                                <th><?= __('atendimento_laudo_label_rg'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($dadosServidor['Dependentes'])): ?>
                                                <tr id="emptyVinculo" class="displayNone">
                                                    <td colspan="6" style="text-align: center;">
                                                        <?= __('nenhum_registro_encontrado') ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                if (!empty($dadosServidor['Dependentes'])):
                                                    foreach ($dadosServidor['Dependentes'] as $key => $line) :
                                                        $dataNascimento = Util::toBrData($line['Dependente']['data_nascimento']);
                                                        $id = $line['Dependente']['id'];
                                                        ?>
                                                        <tr class="">
                                                            <td style="text-align: center; width: 4%;"><input type="radio" <?= $formDisabled ? 'disabled="disabled"' : '' ?>  data-id='<?= $id ?>' <?= $id == $this->request->data['Atendimento']['dependente_id'] ? 'checked="checked"' : '' ?> class="selecaoDependente"></td>
                                                            <td><?= $line['Dependente']['nome']; ?></td>
                                                            <td><?= $dataNascimento; ?></td>
                                                            <td><?= $line['Dependente']['cpf'] ? Util::mask($line['Dependente']['cpf'], '###.###.###-##') : ''; ?></td>
                                                            <td><?= $line['Dependente']['rg']; ?></td>
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    ?>
                                                    <tr id="emptyVinculo">
                                                        <td colspan="5" style="text-align: center;">
                                                            <?= __('nenhum_registro_encontrado') ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <tr id="emptyVinculo">
                                            <td colspan="6" style="text-align: center;">
                                                <?= __('nenhum_registro_encontrado') ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            </fieldset>
                        <?php endif; ?>
                        <?php if ($tipologiaAgendamento == TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR): ?>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?php echo __('atendimento_laudo_label_dados_acompanhado') ?> </legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                echo
                                                $this->Form->input('CidAcompanhante.nome', array('div' => array('class' => 'form-group'),
                                                    'class' => 'form-control',
                                                    'id' => 'acompanhadoCidId',
                                                    'value' => $acompanhado['CidAcompanhante']['nome'],
                                                    'label' => __('agendamento_input_acompanhado_cid'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                echo
                                                $this->Form->input('CidAcompanhante.nome_doenca', array('div' => array('class' => 'form-group'),
                                                    'class' => 'form-control',
                                                    'id' => 'acompanhadoDoencaId',
                                                    'value' => $acompanhado['CidAcompanhante']['nome_doenca'],
                                                    'label' => __('agendamento_input_acompanhado_doenca'),
                                                    'disabled' => TRUE));
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
                                                    'value' => $acompanhado['Agendamento']['nome_acompanhado_sem_abreviacao'],
                                                    'label' => __('nome_sem_abreviacao'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                echo
                                                $this->Form->input('data_nascimento_acompanhado', array('label' => __('agendamento_data_nascimento_acompanhado'),
                                                    'type' => 'text',
                                                    'id' => 'AgendamentoDataNascimentoAcompanhado',
                                                    'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                                    'onblur' => 'VerificaData(this,this.value)',
                                                    'value' => $acompanhado['Agendamento']['data_nascimento_acompanhado'],
                                                    'onmouseout' => 'VerificaData(this,this.value)',
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                echo
                                                $this->Form->input('certidao_nascimento_acompanhado', array('label' => __('agendamento_certidao_nascimento_acompanhado'),
                                                    'class' => 'form-control',
                                                    'id' => 'certidaoNascimentoAcompanhado',
                                                    'value' => $acompanhado['Agendamento']['certidao_nascimento_acompanhado'],
                                                    'label' => __('agendamento_certidao_nascimento_acompanhado'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 form-group">
                                                <?php
                                                echo
                                                $this->Form->input('cpf_acompanhado', array('label' => __('agendamento_cpf_acompanhado'),
                                                    'class' => 'form-control cpf',
                                                    'id' => 'cpfAcompanhado',
                                                    'label' => __('agendamento_cpf_acompanhado'),
                                                    'value' => $acompanhado['Agendamento']['cpf_acompanhado'],
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <?php
                                                echo
                                                $this->Form->input('rg_acompanhado', array('label' => __('agendamento_rg_acompanhado'),
                                                    'class' => 'form-control soNumero',
                                                    'id' => 'rgAcompanhado',
                                                    'value' => $acompanhado['Agendamento']['rg_acompanhado'],
                                                    'label' => __('agendamento_rg_acompanhado'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <?php
                                                echo
                                                $this->Form->input('orgao_expedidor_acompanhado', array('label' => __('agendamento_orgao_expedidor_acompanhado'),
                                                    'class' => 'form-control',
                                                    'id' => 'orgaoExpedidorAcompanhado',
                                                    'value' => $acompanhado['Agendamento']['orgao_expedidor_acompanhado'],
                                                    'label' => __('agendamento_orgao_expedidor_acompanhado'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <?php
                                                echo
                                                $this->Form->input('nome_mae_acompanhado', array('label' => __('agendamento_nome_mae_acompanhado'),
                                                    'class' => 'form-control',
                                                    'id' => 'nomeMaeAcompanhado',
                                                    'value' => $acompanhado['Agendamento']['nome_mae_acompanhado'],
                                                    'label' => __('agendamento_nome_mae_acompanhado'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 form-group">
                                                <?php
                                                echo
                                                $this->Form->input('qualidade_id', array('label' => __('agendamento_qualidade'),
                                                    'class' => 'form-control',
                                                    'options' => $qualidades,
                                                    'value' => $acompanhado['Agendamento']['qualidade_id'],
                                                    'empty' => __('label_selecione'),
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                            <div class="col-md-3 form-group displayNone" id="qualidadeOutros">
                                                <?php
                                                echo
                                                $this->Form->input('outros', array('label' => __('agendamento_outros'),
                                                    'class' => 'form-control',
                                                    'id' => 'inputOutros',
                                                    'empty' => __('label_selecione'),
                                                    'value' => $acompanhado['Agendamento']['outros'],
                                                    'disabled' => TRUE));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php endif; ?>
                        <?php if($tipologiaAgendamento == TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE && $tratamentoAcidente == 1): ?>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Arquivos</legend>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <?php
                                    $urlLaudo = Router::url(['controller' => 'Atendimento', 'action' => 'download_laudo', $tratamentoAcidenteProcesso], true);
                                    ?>
                                    <label>&nbsp;</label>
                                    <label>Download de laudo CAT: <a href="<?= $urlLaudo ?>">aqui</a></label>
                                </div>
                            </div>
                        </fieldset>
                        <?php endif; ?>

                        <?php if ($tipologiaAgendamento == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO): ?>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Dados Pretenso Pensionista</legend>
                                   <div class="row" >
                                    <div class="col-md-4" >
                                        <div class="form-group">
                                            <?php echo $this->Form->input('cpf_pretenso', array('div' => array('class' => 'form-group'),
                                                'class' => 'form-control cpf',
                                                'label' => 'CPF',
                                                'value' => $dadosPretenso['cpf_pretenso'],
                                                'disabled' =>  true)); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('nome_pretenso', array('div' => array('class' => 'form-group'),
                                                'class' => 'form-control',
                                                'label' => 'Nome',
                                                'value' => $dadosPretenso['nome_pretenso'],
                                                'disabled' =>   true)); ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row" >
                                    <div class="col-md-3" >
                                        <div class="form-group">
                                            <?php echo $this->Form->input('data_nascimento_pretenso', array('div' => array('class' => 'form-group'),
                                                'type' => 'text',
                                                'class' => 'form-control',
                                                'label' => 'Data de Nascimento',
                                                'value' => $dadosPretenso['data_nascimento_pretenso'],
                                                'disabled' =>   true)); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->input('sexo_id_pretenso', array('div' => array('class' => 'form-group'),
                                                'class' => 'form-control',
                                                'label' => 'Sexo',
                                                'options' => array('1' => 'Masculino', '2' => 'Feminino'),
                                                'value' => $dadosPretenso['sexo_id_pretenso'],
                                                'empty' => 'Selecione',
                                                'disabled' =>   true)); ?>
                                        </div>
                                    </div>
                                </div> 
                            </fieldset>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading tab-bg-dark-navy-blue">
                <ul class="nav nav-tabs nav-justified abasAtendimento">
                    <?php
                    if (in_array($tipologiaAgendamento, array(
                            TIPOLOGIA_APOSENTADORIA_INVALIDEZ,              TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ,
                            TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA,  TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES,
                            TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO,  TIPOLOGIA_PCD,
                            TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL,  TIPOLOGIA_EXAME_PRE_ADMISSIONAL,
                            TIPOLOGIA_RECURSO_ADMINISTRATIVO))):
                        ?>
                        <li class="active">
                            <a data-toggle="tab" href="#anamnese<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                                <?php echo __('atendimento_laudo_label_anamese') ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#exame_fisico_mental<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                                <?php echo __('atendimento_laudo_label_exame_fisico_mental') ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#diagnostico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                                <?php echo __('atendimento_laudo_label_diagnostico') ?>
                            </a>
                        </li>
                        <?php
                    elseif (in_array($tipologiaAgendamento, array(
                            TIPOLOGIA_REMOCAO,      TIPOLOGIA_REMANEJAMENTO_FUNCAO,     TIPOLOGIA_READAPTACAO_FUNCAO,
                            TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR,  TIPOLOGIA_LICENCA_MATERNIDADE,
                            TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO,       TIPOLOGIA_LICENCA_NATIMORTO,
                            TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE))):
                        ?>
                        <li class="active">
                            <a data-toggle="tab" href="#cid<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                                <?php echo __('atendimento_laudo_label_cid') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(in_array($tipologiaAgendamento, array(
                            TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD ))): ?>
                        <li class="active">
                            <a data-toggle="tab" href="#questionamentos<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                                <?php echo __('Questionamentos') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(in_array($tipologiaAgendamento,array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO))): ?>
                        <li class="active">
                            <a data-toggle="tab" href="#informacoes_declaradas<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                                <?php echo __('Informações Declaradas') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
                    $abaActive = '';
                    if(in_array($tipologiaAgendamento, array(TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_INSPECAO))){
                        $abaActive  = 'active';
                    }
                    ?>
                    <li class="<?=$abaActive; ?>" >
                        <a data-toggle="tab" href="#parecer_tecnico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                            <?php echo __('atendimento_laudo_label_parecer_tecnico') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#historico_licenca<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                            <?php echo __('atendimento_laudo_label_historico_licencas') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#junta_peritos<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                            <?php echo __('atendimento_laudo_label_junta_peritos') ?>
                        </a>
                    </li>
                    
                    <?php if($tipologiaAgendamento == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO): ?>
                        <li>
                            <a data-toggle="tab" href="#seguranca_trabalho<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                                <?php echo __('label_aba_seguraca_trabalho') ?>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#medicina_trabalho<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                                <?php echo __('label_aba_medicina_trabalho') ?>
                            </a>
                        </li>
                    <?php endif; ?>
					<li>
                        <a data-toggle="tab" href="#histor_medico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                            <?php echo __('label_aba_historico_medico') ?>
                        </a>
                    </li>



                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
                    <?php
                    if (in_array($tipologiaAgendamento , array(
                            TIPOLOGIA_APOSENTADORIA_INVALIDEZ,              TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ,
                            TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA,  TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES,
                            TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO,  TIPOLOGIA_PCD,
                            TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL,
                            TIPOLOGIA_EXAME_PRE_ADMISSIONAL,    TIPOLOGIA_RECURSO_ADMINISTRATIVO))):
                        ?>
                        <div id="anamnese<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane active">
                            <?php echo $this->element('aba_atendimento_anamnese'); ?>
                        </div>
                        <div id="exame_fisico_mental<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane">
                            <?php echo $this->element('aba_atendimento_exame_fisico_mental'); ?>
                        </div>
                        <div id="diagnostico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                            <?php echo $this->element('aba_atendimento_diagnostico'); ?>
                        </div>
                        <?php
                    elseif (in_array($tipologiaAgendamento, array(
                            TIPOLOGIA_REMOCAO,   TIPOLOGIA_REMANEJAMENTO_FUNCAO,  TIPOLOGIA_READAPTACAO_FUNCAO,
                            TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR,      TIPOLOGIA_LICENCA_MATERNIDADE,
                            TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO,   TIPOLOGIA_LICENCA_NATIMORTO,
                            TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE))):
                        ?>
                        <div id="cid<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane active">
                            <?php echo $this->element('aba_atendimento_cid'); ?>
                        </div>
                        <?php
                    endif;
                    ?>
                    <?php if (in_array($tipologiaAgendamento, array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))): ?>
                    <div id="questionamentos<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane active">
                        <?php echo $this->element('atendimento/questionamentos'); ?>
                    </div>
                    <?php endif; ?>
                    <?php if(in_array($tipologiaAgendamento,array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO))): ?>
                    <div id="informacoes_declaradas<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane active">
                        <?php
                            if ($tipologiaAgendamento == TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE) {
                                echo $this->element('aba_atendimento_informacoes_declaradas');
                            }else if($tipologiaAgendamento == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
                                echo $this->element('agendamento/cat');
                            }?>
                    </div>
                    <?php endif; ?>

                    <div id="parecer_tecnico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane <?=$abaActive; ?>">
                        <?php
                        if($tipologiaAgendamento == TIPOLOGIA_INSPECAO){
                            echo $this->element('atendimento/inspecao_parecer_tecnico');
                        }else{
                            echo $this->element('aba_atendimento_parecer_tecnico');
                        }
                        ?>
                    </div>
                    <div id="historico_licenca<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('aba_atendimento_historico_licencas'); ?>
                    </div>
                    <div id="junta_peritos<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('aba_atendimento_junta_peritos'); ?>
                    </div>
                    <div id="histor_medico<?=isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('aba_atendimento_historico_medico'); ?>
                    </div>
                    <?php if($tipologiaAgendamento == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO): ?>
                        <?php
                        echo $this->Form->hidden('AtendimentoCAT.salvo_medico');
                        echo $this->Form->hidden('AtendimentoCAT.salvo_engenheiro');
                        ?>

                        <div id="seguranca_trabalho<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                            <?php echo $this->element('aba_atendimento_seguranca_trabalho'); ?>
                        </div>
                        <div id="medicina_trabalho<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                            <?php echo $this->element('aba_atendimento_medicina_trabalho'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <div class="panel-body">
                <div class="row float-right btn-edit">
                    <?php
                    if (!$formDisabled):
                        // echo $this->Form->hidden('url', array('data-url' => Router::url('/admin/Agendamento/', true), 'id' => "btSave"));
                        echo $this->Form->hidden('url', array('data-url' => Router::url('/web/Agendamento/', true), 'id' => "btSave"));
                        echo $this->Form->button(__('bt_salvar_continuar'), array(
                            'class' => 'btn fa fa-save estiloBotao btn-success',
                            'value' => 'true',
                            'id' => 'salvarContinuar',
                            'name' => 'salvarButton'
                        ));

                        echo $this->Form->button(__('bt_emitir_laudo'), array(
                            'class' => 'btn fa fa-save estiloBotao btn-info',
                            'value' => 'true',
                            'type' => 'button',
                            'id' => 'btEmitirLaudo',
                            'name' => 'emitirLaudo'
                        ));
                        echo $this->Form->button(__('bt_finalizar_atendimento'), array(
                            'class' => 'btn fa fa-save estiloBotao btn-info displayNone',
                            'value' => 'true',
                            'id' => 'btFinalizarAtendimento',
                            'name' => 'finalizarAtendimento'
                        ));
                        ?>
                        <?php
                    endif;
                    ?>
                    <?php
                    if (isset($detalharArquivo) && !isset($isModal)):
                        ?>
                        <li class="btn fa fa-arrow-left estiloBotao btn-danger botaoVoltarAtendimento" 
                            data-url="<?= Router::url(array('controller' => "Atendimento", 'action' => 'voltarAtendimentoAnterior')) ?>" 
                            data-id="<?= $id ?>" 
                            data-acao='<?= $currentAction ?>' 
                            data-anterior="<?= $idAnterior ?>">
                                <?= __('bt_voltar') ?>
                        </li>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?= $this->element('dialog_validacao_acompanhado') ?>
<div id="dialog-emissao_laudo" class="displayNone">
    <p><?php echo __('info_emissao_laudo'); ?></p>
</div>
<?= $this->Form->end(); ?>
<input type="hidden" name="finalizarAtendimento" class="finalizarAtendimento"/>
<div id="modais"></div>
<? //echo  $this->Html->script('Admin.atendimentoEdit', array('block' => 'script')); ?>
<? echo  $this->Html->script('Web.atendimentoEdit', array('block' => 'script')); ?>

<? echo  $this->Html->script('Web.atendimentoEdit', array('block' => 'script')); ?>
<? echo  $this->Html->script('Web.atendimentoEdit', array('block' => 'script')); ?>