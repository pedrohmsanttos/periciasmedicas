<!-- edit_pre_admissiona.ctp -->
<?php
$formCreate['class'] = ($formDisabled) ? "formVisualizacao" : "";
echo $this->Form->create($controller, $formCreate);
echo $this->Form->input('usuario_id', array(
    'type' => 'hidden',
    'id' => 'hidUsuarioId',
    'value' => $dadosServidor['Usuario']['id']));
echo $this->Form->hidden('agendamento_id', array(
    'id' => 'hidAgendamentoId'));
echo $this->Form->hidden('dependente_id', array(
    'id' => 'hidDependenteId'));
echo $this->Form->hidden('atedimento_pai_id', array(
    'id' => 'hidAtendimentoPaiId'));
echo $this->Form->hidden('status_atendimento', array(
    'id' => 'hidStatusAtendimento'));
echo $this->Form->hidden('Agendamento.tipologia_id', array(
    'id' => 'hidtipologia'));
echo $this->Form->hidden('emitir_laudo', array(
    'id' => 'hidEmitirLaudo', 'value' => false));

echo $this->Form->hidden( 'PreAdmissional.id', array(
    'id' =>'hidPreAdmissional'
));
?>

<?php
echo $this->Form->input('tipologia_licenca_maternidade', array(
    'type' => 'hidden',
    'id' => 'tipologia_licenca_maternidade',
    'value' => TIPOLOGIA_LICENCA_MATERNIDADE,
    'disabled' => true
));

echo $this->Form->input('tipologia_licenca_maternidade_aborto', array(
    'type' => 'hidden',
    'id' => 'tipologia_licenca_maternidade_aborto',
    'value' => TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO,
    'disabled' => true
));

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

echo $this->Form->input('tipologia_licenca_natimorto', array(
    'type' => 'hidden',
    'id' => 'tipologia_licenca_natimorto',
    'value' => TIPOLOGIA_LICENCA_NATIMORTO,
    'disabled' => true
));

echo $this->Form->input('tipologia_aposentadoria_invalidez', array(
    'type' => 'hidden',
    'id' => 'tipologia_aposentadoria_invalidez',
    'value' => TIPOLOGIA_APOSENTADORIA_INVALIDEZ,
    'disabled' => true
));
echo $this->Form->input('tipologia_isencao_contribuicao_previdenciaria', array(
    'type' => 'hidden',
    'id' => 'tipologia_isencao_contribuicao_previdenciaria',
    'value' => TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA,
    'disabled' => true
));
echo $this->Form->input('tipologia_reversao_aposentadoria_invalidez', array(
    'type' => 'hidden',
    'id' => 'tipologia_reversao_aposentadoria_invalidez',
    'value' => TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ,
    'disabled' => true
));
echo $this->Form->input('tipologia_avaliacao_habilitacao_dependentes', array(
    'type' => 'hidden',
    'id' => 'tipologia_avaliacao_habilitacao_dependentes',
    'value' => TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES,
    'disabled' => true
));

echo $this->Form->input('tipologia_admissao_pensionista_maior_invalido', array(
    'type' => 'hidden',
    'id' => 'tipologia_admissao_pensionista_maior_invalido',
    'value' => TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO,
    'disabled' => true
));
echo $this->Form->input('tipologia_informacao_seguro_compreensivo_habitacional', array(
    'type' => 'hidden',
    'id' => 'tipologia_informacao_seguro_compreensivo_habitacional',
    'value' => TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL,
    'disabled' => true
));
echo $this->Form->input('tipologia_readaptacao_funcao', array(
    'type' => 'hidden',
    'id' => 'tipologia_readaptacao_funcao',
    'value' => TIPOLOGIA_READAPTACAO_FUNCAO,
    'disabled' => true
));
echo $this->Form->input('tipologia_remanejamento_funcao', array(
    'type' => 'hidden',
    'id' => 'tipologia_remanejamento_funcao',
    'value' => TIPOLOGIA_REMANEJAMENTO_FUNCAO,
    'disabled' => true
));
echo $this->Form->input('tipologia_risco_vida_insalubridade', array(
    'type' => 'hidden',
    'id' => 'tipologia_risco_vida_insalubridade',
    'value' => TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,
    'disabled' => true
));

echo $this->Form->input('tipologia_recurso_administrativo', array(
    'type' => 'hidden',
    'id' => 'tipologia_recurso_administrativo',
    'value' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
    'disabled' => true
));
echo $this->Form->input('tipologia_exame_pre_admissional', array(
    'type' => 'hidden',
    'id' => 'tipologia_exame_pre_admissional',
    'value' => TIPOLOGIA_EXAME_PRE_ADMISSIONAL,
    'disabled' => true
));
echo $this->Form->input('tipologia_licenca_acompanhamento_familiar', array(
    'type' => 'hidden',
    'id' => 'tipologia_licenca_acompanhamento_familiar',
    'value' => TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR,
    'disabled' => true
));
echo $this->Form->input('tipologia_atencipacao_licenca', array(
    'type' => 'hidden',
    'id' => 'tipologia_atencipacao_licenca',
    'value' => TIPOLOGIA_ATECIPACAO_LICENCA,
    'disabled' => true
));
echo $this->Form->input('tipologia_licenca_medica_tratamento_saude', array(
    'type' => 'hidden',
    'id' => 'tipologia_licenca_medica_tratamento_saude',
    'value' => TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE,
    'disabled' => true
));
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
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    echo $this->PForm->radio(array(
                                        'class'=>'pneChoice',
                                        'title' => 'Portador de Necessidades Especiais?',
                                        'name' => 'PreAdmissional.pne_pne',
                                        'disabled' => $formDisabled,
                                        'options' => array('1'=> 'Sim', '0'=> 'Não' ),
                                        'column' => 12
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="row row-item">
                                <?php
                                echo $this->PForm->radio(array(
                                    'class'=>'vinculoPP',
                                    'title' => 'Tem algum vínculo público ou privado?',
                                    'name' => 'PreAdmissional.vinculopp',
                                    'disabled' => $formDisabled,
                                    'options' => array('1'=> 'Sim', '0'=> 'Não' ),
                                    'column' => 6
                                ));
                                ?>

                                <div class="col-md-6">
                                    <?php
                                    echo $this->Form->input('PreAdmissional.vinculopp_quando', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control vinculopp_quando',
                                        'type' => 'text',
                                        'label' => 'Se sim, há quanto tempo?',
                                        'disabled' => $formDisabled));
                                    ?>
                                </div>
                            </div>
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
                                                            <td><?= date('d/m/Y', strtotime($line['Vinculo']['data_admissao_servidor'])) . $line['Vinculo']['stringVerificaAnos']; ?></td>
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
                    <li class="active">
                        <a data-toggle="tab" href="#revisao_habitos<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                            <?php echo __('Revisão de Hábitos') ?>
                        </a>
                    </li>
                    <li >
                        <a data-toggle="tab" href="#historia_familial<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                            <?php echo __('História Familial') ?>
                        </a>
                    </li>
                    <li >
                        <a data-toggle="tab" href="#historia_patologia_interrogatorio_sistemico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                            <?php echo __('História Patológica / Interrogatório Sistêmico') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#exame_fisico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                            <?php echo __('Exame Físico') ?>
                        </a>
                    </li>
                    <li style="display:none">
                        <a data-toggle="tab" id="abaPNE" href="#pne<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                            <?php echo __('PNE') ?>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#exames_complementares<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>">
                            <?php echo __('Exames Complementares') ?>
                        </a>
                    </li>
                    <li>
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
                    <li>
                        <a data-toggle="tab" href="#histor_medico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="contact-map">
                            <?php echo __('label_aba_historico_medico') ?>
                        </a>
                    </li>

                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content tasi-tab">
                    <div id="revisao_habitos<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane active">
                        <?php echo $this->element('atendimento_pre_admissional/revisao_habitos'); ?>
                    </div>
                    <div id="historia_familial<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('atendimento_pre_admissional/historia_familial'); ?>
                    </div>
                    <div id="historia_patologia_interrogatorio_sistemico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('atendimento_pre_admissional/historia_patologia_interrogatorio_sistemico'); ?>
                    </div>
                    <div id="exame_fisico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane">
                        <?php echo $this->element('atendimento_pre_admissional/exame_fisico'); ?>
                    </div>
                    <div id="pne<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('atendimento_pre_admissional/pne'); ?>
                    </div>
                    <div id="exames_complementares<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('atendimento_pre_admissional/exames_complementares'); ?>
                    </div>
                    <div id="parecer_tecnico<?= isset($complementoIdTabs) ? $complementoIdTabs : '' ?>" class="tab-pane ">
                        <?php echo $this->element('atendimento_pre_admissional/parecer_tecnico'); ?>
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
                    if (isset($detalharArquivo)):
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
<? //echo $this->Html->script('Admin.atendimentoEdit', array('block' => 'script')); ?>
<? echo $this->Html->script('Web.atendimentoEdit', array('block' => 'script')); ?>
<?= $this->Html->script('formatNumber', array('block' => 'script')); ?>

