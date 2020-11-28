<?php
echo $this->Form->input('AgendamentoCAT.id', array(
    'type' => 'hidden'
));
if (!isset($formAtendimento)){
    $formAtendimento = false;
}

?>
<div class="row">
    <div class="col-md-5 div-pa" >
        <?php
        $arrTipo = array('0' => __('agendamento_label_cat_inicial'), '1' => __('agendamento_label_cat_reabertura'), '2' => __('agendamento_label_cat_comunicacao_obito'));

        $strAtivo = (isset($this->data['AgendamentoCAT']['tipo_cat'])) ? $this->data['AgendamentoCAT']['tipo_cat'] : null;
        $value = $strAtivo;

        echo
        $this->Form->input('AgendamentoCAT.tipo_cat', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $arrTipo,
            'value' => $value,
            'disabled' =>$formDisabledHomologa || $formAtendimento,
            'empty' => __('label_selecione'),
            'label' => __('agendamento_label_tipo_cat') . $isRequerid));
        ?>
    </div>
    <div class="col-md-5 div-pa" >
        <?php
        $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
        $strAtivo = (isset($this->data['AgendamentoCAT']['houve_afastamento'])) ? $this->data['AgendamentoCAT']['houve_afastamento'] : null;
        $value = null;
        if ($strAtivo == true):
            $value = 1;
        elseif ($strAtivo === false):
            $value = 0;
        endif;
        echo
        $this->Form->input('AgendamentoCAT.houve_afastamento', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $arrTipo,
            'value' => $value,
            'disabled' =>$formDisabledHomologa || $formAtendimento,
            'empty' => __('label_selecione'),
            'label' => __('agendamento_label_houve_afastamento') . $isRequerid));
        ?>
    </div>
</div>
<?php if(!$formAtendimento): ?>
<div class="row">
    <div class="col-lg-12">
        <fieldset class="scheduler-border" id="fieldsetServidor">
            <legend class="scheduler-border">Acidentado</legend>
				<div class="row" id="divDadosAcidentado">
					<div class="col-md-12">
                        <div class='row'>
                            <div class='col-md-8'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>Nome</label>
                                    <input disabled='disabled' class='form-control' id="nomeServidorAcidentado" maxlength='150' type='text' value=''>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>Matrícula</label>
                                    <input disabled='disabled' class='form-control' id="matriculaServidorAcidentado" maxlength='150' type='text' value=''>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>Data Nascimento</label>
                                    <input disabled='disabled' class='form-control' id="dataNascimentoServidorAcidentado" maxlength='150' type='text' value=''>

                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>CPF</label>
                                    <input disabled='disabled' class='form-control' id="CPFServidorAcidentado" maxlength='150' type='text' value=''>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>RG</label>
                                    <input disabled='disabled' class='form-control'id="RGServidorAcidentado" maxlength='150' type='text' value=''>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>Sexo</label>
                                    <input disabled='disabled' class='form-control' id="sexoServidorAcidentado" maxlength='150' type='text' value=''>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>Estado Civil</label>
                                    <input disabled='disabled' class='form-control' id="estadoCivilServidorAcidentado" maxlength='150' type='text' value=''>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    <label for='lotacao_orgao_cat'>Escolaridade</label>
                                    <input disabled='disabled' class='form-control' id="escolaridadeServidorAcidentado" maxlength='150' type='text' value'' >
                                </div>
                            </div>
                        </div>
					</div>
				</div>
		</fieldset>		
	</div>
</div>

<div class="row">
    <div class="col-lg-12">
        <fieldset class="scheduler-border" id="fieldsetServidor">
            <legend class="scheduler-border"><?php echo __('agendamento_input_orgao') ?></legend>

            <div class="row divOrgao">
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('secretaria_orgao_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'lotacao_orgao_cat',

                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_lotacao_cat') . $isRequerid,
                                    'disabled' => 'true'));
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('orgao_local_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'orgao_local_cat',
                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_orgao_local_lotado_cat') . $isRequerid,
                                    'disabled' => 'true'));
                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('orgao_cnpj_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'orgao_cnpj_cat',
                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_orgao_cnpj') . $isRequerid,
                                    'disabled' => 'true'));
                                ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                echo
                                $this->Form->input('endereco_orgao_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'endereco_orgao_cat',
                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_endereco_orgao_cat') . $isRequerid,
                                    'disabled' => 'true'));
                                ?>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('bairro_orgao_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'bairro_orgao_cat',
                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_bairro_orgao_cat') . $isRequerid,
                                    'disabled' => 'true'));

                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('municipio_orgao_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'municipio_orgao_cat',
                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_municipio_orgao_cat') . $isRequerid,
                                    'disabled' => 'true'));

                                ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo
                                $this->Form->input('fone_orgao_cat', array('div' => array('class' => 'form-group'),
                                    'class' => 'form-control',
                                    'id' => 'fone_orgao_cat',
                                    'maxlength' => '150',
                                    'label' => __('agendamento_label_fone_orgao_cat') . $isRequerid,
                                    'disabled' => 'true'));
                                ?>
                            </div>


                        </div>
                    </fieldset>
                </div>
            </div>

        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <fieldset class="scheduler-border" id="fieldsetServidor">
            <legend class="scheduler-border"><?php echo __('lotacao') ?></legend>
				<div class="row" id="divAcidentado">
					<div class="col-md-12">
					</div>
				</div>
		</fieldset>		
	</div>
</div>
<?php endif; ?>
<div class="row">
    <div class="col-lg-12">
        <fieldset class="scheduler-border" id="fieldsetAcidente">
            <legend class="scheduler-border"><?php echo __('agendamento_input_informacoes_declaradas') ?></legend>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    echo $this->Form->input('localMsg', array(
                            'id' => 'localMsg',
                            'value' => '',
                            'label' => false,
                            'style' => 'display:none;'
                        ));
                    
                    
                    echo
                    $this->Form->input('AgendamentoCAT.local_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'local_acidente_cat',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'label' => __('agendamento_label_local_acidente') . $isRequerid));
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    echo
                         $this->Form->input('AgendamentoCAT.data_acidente_doenca', array('label' => __('agendamento_label_data_acidente'),
                        'type' => 'text',
                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                        'onblur' => 'VerificaData(this,this.value)',
                             'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'onmouseout' => 'VerificaData(this,this.value)'
                        ));
                    ?>
                </div>
                <div class="col-md-4  form-group">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.hora_acidente_doenca', array('label' => __('agendamento_label_hora_acidente') ,
                        'type' => 'text',
                        'class' => 'form-control hour',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'onblur' => 'if(!validarHora(this.value))invalideHour(this);'));

                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.horario_trabalho_inicio', array('label' => __('agendamento_label_horario_trabalho_cat') ,
                        'type' => 'text',
                        'class' => 'form-control hour',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'onblur' => 'if(!validarHora(this.value))invalideHour(this);'));

                    ?>
                </div>

                <div class="pull-left" style="margin-top: 30px;" id="agendamento_label_as">
                <?= __('agendamento_label_as'); ?>
                </div>

                <div class="col-md-2" style= "margin-top: 3px;">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.horario_trabalho_fim', array('label' => __('agendamento_label_horario_trabalho_cat_fim'),
                        'type' => 'text',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'class' => 'form-control hour'));
                      ?>
                </div>

                <div class="col-md-2">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.horario_cumprido_entrada', array('label' => __('agendamento_label_horario_trabalho_cumprido_entrada'),
                        'type' => 'text',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'class' => 'form-control hour'));
                      ?>
                </div>

                <div class="pull-left" style="margin-top: 30px;" id="agendamento_label_as">
                <?= __('agendamento_label_as'); ?>
                </div>


                <div class="col-md-2" style="margin-top: 3px;">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.horario_cumprido_saida', array('label' => __('agendamento_label_horario_trabalho_cumprido_saida'),
                        'type' => 'text',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'class' => 'form-control hour'));
                      ?>
                </div>

            
            <div class="col-md-3">
                <?php
                echo
                $this->Form->input('AgendamentoCAT.apos_quantas_horas_trabalho_acidente_doenca', array('label' => __('agendamento_label_registro_apos_qt_horas_trabalho_cat'),
                    'disabled' =>$formDisabledHomologa || $formAtendimento,
                    'class' => 'form-control soNumero'));
                  ?>


                </div>
            </div>


            
            <br>

        <div class="row">
            <div class="col-md-3">
                <?php
                echo
                $this->Form->input('AgendamentoCAT.lotacao', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'id' => 'lotacao_cat',
                    'disabled' =>$formDisabledHomologa || $formAtendimento,
                    'maxlength' => '150',
                    'label' => __('agendamento_label_lotacao_cat')));
                ?>
            </div>

            <div class="col-md-3">
                <?php
                echo
                $this->Form->input('AgendamentoCAT.setor', array('div' => array('class' => 'form-group'),
                    'class' => 'form-control',
                    'id' => 'setor_agendamento_cat',
                    'disabled' =>$formDisabledHomologa || $formAtendimento,
                    'maxlength' => '150',
                    'label' => __('agendamento_label_setor')));
                ?>
            </div>

            
                <div class="col-md-2">
                    <?php
                    $arrTipo = array('0' => __('agendamento_label_tipico'), '1' => __('agendamento_label_doenca'));
                    echo
                    $this->Form->input('AgendamentoCAT.tipo_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'tipo_acidente_cat',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'options' =>$arrTipo,
                        'empty'=>  __('label_selecione'),
                        'label' => __('agendamento_label_tipo_acidente_cat') . $isRequerid));
                    ?>
                </div>
        </div>

                <div class="col-md-3" style="margin-left: -18px;">
                    <?php
                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
                    echo
                    $this->Form->input('AgendamentoCAT.registro_policial_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'registro_policial_cat',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'empty' => __('label_selecione'),
                        'options' =>$arrTipo,
                        'label' => __('agendamento_label_registro_policial') . $isRequerid));
                    ?>
                </div>


                <div class="col-md-3 displayNone" id="anexo_registro_policial">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.descricao_registro_policial_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'type' => 'file',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'id' => 'registro_policial_anexo_cat',
                        'label' => __('agendamento_label_registro_policial_anexo') . $isRequerid));
                    ?>
                </div>
                <div class="col-md-1" style="margin-top: 24px;">
                    <?php


                    $existFile = (isset($this->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca_pat']) && isset($this->data['AgendamentoCAT']['id']));
                    if($existFile){?>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" type="button"><?php echo 'arquivo' ?> <span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu listaAcoes">
                                <li><?php echo $this->Html->link(__('bt_file'),$this->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca_pat'], array('class' => 'fa fa-file', 'title' => __('bt_file'),'target'=>'_blank','escape'=>false)); ?></li>
                                <?if(!$formDisabledHomologa && !$formAtendimento){?>
                                <li><?php echo $this->Html->link(__('bt_excluir'), array('action' => 'deleteFileCAT',$this->data['AgendamentoCAT']['id'],'descricao_registro_policial_acidente_doenca_pat') , array('class' => 'fa fa-trash-o','confirm' => 'Deseja realmente excluir esse arquivo?', 'title' => __('bt_excluir'))); ?></li>
                                <?}?>
                            </ul>
                        </div>

                    <? }?>

                </div>
            
            
        <div class="row">
            <div class="row">
                <div class="col-md-2 pull-left" style="margin-top: 2%; margin-left: -5%;">
                    <span class="pull-left">
                    <?= __('agendamento_chkbx_diarista'); ?>
                        
                    </span>
                    <span style="margin-left: 8%;">
                        <?=
                        $this->Form->checkbox('AgendamentoCAT.chkbx_diarista', array('label' => __('agendamento_chkbx_diarista') . $isRequerid,
                            'id' => 'chkbxDiarista',
                            'label' => array('id' => 'labelDecorrente', 'text' => __('agendamento_chkbx_diarista'). $isRequerid),
                            'default' => 'f',
                            'disabled' => $formDisabled || $formDisabledHomologa || $formAtendimento));
                        ?>
                    </span>

                </div>

                
                    <div class="col-md-2" style="margin-top: 2%; margin-left: -5%;">
                        <span class="pull-left">
                        <?= __('agendamento_chkbx_plantonista'); ?>
                        </span>
                        <span style="margin-left: 8%;">
                            <?=
                            $this->Form->checkbox('AgendamentoCAT.chkbx_plantonista', array('label' => __('agendamento_chkbx_plantonista') . $isRequerid,
                                'id' => 'chkbxPlantonista',
                                'label' => array('id' => 'labelDecorrente', 'text' => __('agendamento_chkbx_plantonista'). $isRequerid),
                                'default' => 'f',
                                'disabled' => $formDisabledHomologa || $formAtendimento));
                            ?>
                        </span>

                    </div>
            
                <div class="col-md-2 displayNone" id="div_expediente_plantonista">
                        <?php

                        echo $this->Form->input('expedienteMsg', array(
                            'id' => 'expedienteMsg',
                            'value' => '',
                            'label' => false,
                            'style' => 'display:none;'
                        ));

                        $arrTipo = array('1' => __('12/36'), '2' => __('12/60'), '3' => __('24h'), '4' => __('Outro'));
                        echo
                        $this->Form->input('AgendamentoCAT.expediente_plantonista', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'id' => 'expediente_plantonista',
                            'disabled' =>$formDisabledHomologa || $formAtendimento,
                            'options' =>$arrTipo,
                            'empty'=>  __('label_selecione'),
                            'label' => __('expediente_plantonista') . $isRequerid));
                        ?>
                    </div>

                    <div class="col-md-3 displayNone" id="div_outro" style="margin-left: 10px;">
                        <?php

                            echo $this->Form->input('plantonistaMsg', array(
                                    'id' => 'plantonistaMsg',
                                    'value' => '',
                                    'label' => false,
                                    'style' => 'display:none;'
                                )); 

                            echo
                            $this->Form->input('AgendamentoCAT.plantonista_outro', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'id' => 'plantonista_outro',
                                'disabled' =>$formDisabledHomologa || $formAtendimento,
                                'maxlength' => '150',
                                'label' => __('agendamento_label_plantonista_outro'). $isRequerid));
                        ?>
                </div>
            </div>
        <br>
        <br>
            <div class="row">
                <div class="col-md-6">
                    <?php

                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
                    echo
                    $this->Form->input('AgendamentoCAT.assistencia_medica_hospitalar_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id'=>'assistencia_medica_hospitalar_acidente_doenca',
                        'options' => $arrTipo,
                        'value' => $value,
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'empty' => __('label_selecione'),
                        'label' => __('agendamento_label_recebeu_assistencia_cat') . $isRequerid));


                    ?>
                </div>
                <div class="col-md-6 displayNone" id="local_assistencia_medica_hospitalar">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.local_assistencia_medica_hospitalar_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'local_assistencia_cat',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'label' => __('agendamento_label_local_assistencia_cat') . $isRequerid));
                    ?>
                </div>

            </div>
            <div class="row">

                <div class="col-md-12">
                    <?php
                    echo $this->Form->input('AgendamentoCAT.descricao_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'type'=>'textarea',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'id' => 'descricao_acidente_cat',
                        'label' => __('agendamento_label_descricao_acidente_cat') . $isRequerid));
                    ?>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <?php
                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
                    echo
                    $this->Form->input('AgendamentoCAT.testemunha_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'id' => 'testemunha_acidente_doenca',
                        'options' =>$arrTipo,
                        'empty' => __('label_selecione'),
                        'label' => __('agendamento_label_houve_testemunha_cat') . $isRequerid));
                    ?>
                </div>
                <div class="col-md-4 dvtestemunha_acidente displayNone" >
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.nome_testemunha_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'id' => 'nome_testemunha_cat',
                        'label' => __('agendamento_label_nome_testemunha_cat') . $isRequerid));
                    ?>
                </div>
                <div class="col-md-4 dvtestemunha_acidente displayNone">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.matricula_testemunha_acidente_doenca', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'disabled' =>$formDisabledHomologa || $formAtendimento,
                        'id' => 'matricula_testemunha_cat',
                        'label' => __('agendamento_label_matricula_testemunha_cat') . $isRequerid));
                    ?>
                </div>
            </div>

        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <fieldset class="scheduler-border" id="fieldsetServidor">
            <legend class="scheduler-border"><?php echo __('agendamento_input_chefia_imediata') ?></legend>
            <div class="row">
                <div class="col-md-3">
                    <?php
                    $idUsuario = CakeSession::read('Auth.User.id');

                    $disabilitarChefia = true;
                    if($this->data){
                        $chefiaMediata1 = isset($this->data['Agendamento']['chefe_imediato_um_id'])?$this->data['Agendamento']['chefe_imediato_um_id']:null;
                        $chefiaMediata2 =  isset($this->data['Agendamento']['chefe_imediato_dois_id'])?$this->data['Agendamento']['chefe_imediato_dois_id']:null;
                        $chefiaMediata3 = isset($this->data['Agendamento']['chefe_imediato_tres_id'])?$this->data['Agendamento']['chefe_imediato_tres_id']:null;
                        $arrChefiaMediata = array($chefiaMediata1,$chefiaMediata2,$chefiaMediata3);
                        $disabilitarChefia = !(in_array($idUsuario,$arrChefiaMediata)) ;
                    }


                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
                    echo
                    $this->Form->input('AgendamentoCAT.licenca_medica_chefia_imediata', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'options' => $arrTipo,
                        'value' => $value,
                        'empty' => __('label_selecione'),
                        'disabled' => $disabilitarChefia || $formAtendimento,
                        'label' => __('agendamento_label_houve_licenca_medica') . $isRequerid));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.nome_chefia_imediata', array('label' => __('nome_chefia_imediata'),
                        'type' => 'text',
                        'class' => 'form-control',
                        'disabled' => $disabilitarChefia || $formAtendimento

                    ));

                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.cpf_chefia_imediata', array('label' => __('cpf_chefia_imediata'),
                        'type' => 'text',
                        'class' => 'form-control cpf',
                        'disabled' => $disabilitarChefia || $formAtendimento

                    ));

                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.telefone_chefia_imediata', array('label' => __('telefone_chefia_imediata'),
                        'type' => 'text',
                        'class' => 'form-control telefone',
                        'disabled' => $disabilitarChefia || $formAtendimento

                    ));

                    ?>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.cargo_chefia_imediata', array('label' => __('cargo_chefia_imediata'),
                        'type' => 'text',
                        'class' => 'form-control',
                        'disabled' => $disabilitarChefia || $formAtendimento

                    ));

                    ?>
                </div>
                
                <div class="col-md-3">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.ultimo_dia_trabalhado_chefia_imediata', array('label' => __('agendamento_label_ultimo_dia_trabalhado'),
                        'type' => 'text',
                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                        'onblur' => 'VerificaData(this,this.value)',
                        'disabled' => $disabilitarChefia || $formAtendimento,
                        'onmouseout' => 'VerificaData(this,this.value)'

                    ));



                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
                    echo

                    $this->Form->input('AgendamentoCAT.executa_atividade_cargo_funcao_chefia_imediata', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'acidentado_em_atividade',
                        'options' =>$arrTipo,
                        'disabled' => $disabilitarChefia || $formAtendimento,
                        'empty' => __('label_selecione'),
                        'label' => __('agendamento_label_acidentado_em_atividade') . $isRequerid));
                    ?>
                </div>
            </div>            

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.medida_tomada_ocorrencia_chefia_imediata', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'medidas_evitar_ocorrencia',
                        'disabled' => $disabilitarChefia || $formAtendimento,
                        'label' => __('agendamento_label_registro_medidas_evitar_ocorrencia') . $isRequerid));
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.informacoes_complementares_chefia_imediata', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'informacoes_complementares_chefia_imediata',
                        'disabled' => $disabilitarChefia || $formAtendimento,
                        'label' => __('agendamento_label_informacoes_complementares_chefia_imediata') . $isRequerid));
                    ?>
                </div>
            </div>

    </div>

    </fieldset>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
    // CAT
    $('body').on('change', '#registro_policial_cat', function () {
        var rpi = $(this).val();
        if (rpi ==1) {
            $('#anexo_registro_policial').removeClass('displayNone');
        } else {
            $('#anexo_registro_policial').addClass('displayNone');
        }
    });

    $('body').on('change', '#assistencia_medica_hospitalar_acidente_doenca', function () {
        var rpi = $(this).val();
        if (rpi ==1) {
            $('#local_assistencia_medica_hospitalar').removeClass('displayNone');
        } else {
            $('#local_assistencia_medica_hospitalar').addClass('displayNone');
        }
    });

    $('body').on('change', '#testemunha_acidente_doenca', function () {
        var rpi = $(this).val();
        if (rpi ==1) {
            $('.dvtestemunha_acidente').removeClass('displayNone');
        } else {
            $('.dvtestemunha_acidente').addClass('displayNone');
        }
    });

    $('body').on('change', '#chkbxPlantonista', function () {
        if($('#chkbxPlantonista').is(":checked")){
            $('#div_expediente_plantonista').removeClass('displayNone');
        }else{
            $('#div_expediente_plantonista').addClass('displayNone');
            $('#expediente_plantonista').val('');
            $('#div_outro').addClass('displayNone');

        }       
    
    });

    $('body').on('change', '#expediente_plantonista', function () {
        var rpi = $(this).val();
        if (rpi == 4) {
            $('#div_outro').removeClass('displayNone');
        } else {
            $('#div_outro').addClass('displayNone');
        }
    });


    if($('#chkbxPlantonista').is(":checked")){
        $('#div_expediente_plantonista').removeClass('displayNone');
        if($('#expediente_plantonista').val() == 4){
            $('#div_outro').removeClass('displayNone');
        }
    }  

    $('#chkbxPlantonista').on('change', function() {
        $('#chkbxDiarista').not(this).prop('checked', false);  
    });

    $('#chkbxDiarista').on('change', function() {
        $('#chkbxPlantonista').not(this).prop('checked', false);
        $('#div_expediente_plantonista').addClass('displayNone');
        $('#expediente_plantonista').val('');
        $('#div_outro').addClass('displayNone');
    });


    
});

        
</script>
<!--Aqui tava perícias médicas -->

