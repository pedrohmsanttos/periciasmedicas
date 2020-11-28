<?php
$tipologiaId = intval($tipologiaAgendamento);
echo $this->Form->input('AtendimentoCAT.id', array(
    'type' => 'hidden'
));

$afastamento = $dataForView['acompanhado']['AgendamentoCAT']['houve_afastamento']; // verificar se houve afastamento
if(!$formDisabled){
    $disabled = !$isMedico || $this->data['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO;
}else{
    $disabled = $formDisabled;
}
?>

<!-- Area de Perícias Médicas -->
<div class="row">
    <div class="col-lg-12">
        <fieldset id="">
           <!-- <legend class="scheduler-border"<?php //echo __('agendamento_input_pericia_medica') ?></legend>-->
            <div class="row">
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.data_afastamento_pericia_medica', array('div' => array('class' => 'form-group'),
                        'type' => 'text',
                        'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                        'onblur' => 'VerificaData(this,this.value)',
                        //'value' => $agCat['data_afastamento_pericia_medica'],
                        'disabled' => $disabled,
                        'onmouseout' => 'VerificaData(this,this.value)'));
                    ?>
                </div>
                <div class="col-md-3" >
                    <?php
                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

                    echo
                    $this->Form->input('AgendamentoCAT.internacao_pericia_medica', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'options' => $arrTipo,
                        //'value' => $agCat['internacao_pericia_medica'],
                        'disabled' => $disabled,
                        'empty' => __('label_selecione'),
                        'label' => __('agendamento_label_houve_internacao')));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.duracao_tratamento_pericia_medica', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control soNumero',
                        'id' => 'provavel_duracao_tratamento',
                        //'value' => $agCat['duracao_tratamento_pericia_medica'],
                        'disabled' => $disabled,
                        'label' => __('agendamento_label_provavel_duracao_tratamento')));
                    ?>
                </div>

                <?php if ($tipologiaId !== TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO):
                ?> 

                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('artigo_lei', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'artigo_lei',
                        'value' => 'Art. 91 Item IX (Lei Nº 6.123/1968)',
                        'disabled' => 'true'));
                    ?>
                </div>
            <?php endif; ?>

            </div>
            <div class="row">

                <div class="col-md-9">
                    <?php
                    echo $this->Form->input('AgendamentoCAT.tipo_local_lesao_pericia_medica', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'type'=>'textarea',
                        //'value' => $agCat['tipo_local_lesao_pericia_medica'],
                        'disabled' => $disabled,
                        'id' => 'tipo_local_lesao',
                        'label' => __('agendamento_label_tipo_local_lesao_cat')));
                    ?>
                </div>


                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AgendamentoCAT.cid_10', array('div' => array('class' => 'form-group'),
                        'label' => 'CID 10',
                        'type' =>'text',
                        //'value' => $agCat['cid_10'],
                        'class' => 'form-control',
                        'id' => 'pericia_medica_cid',
                        'disabled' => $disabled,
                        ));
                    ?>
                </div>

            </div>
            <div class="row">

                <div class="col-md-12">
                    <?php
                    echo $this->Form->input('AgendamentoCAT.informacoes_complementares_pericia_medica', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'type'=>'textarea',
                        //'value' => $agCat['informacoes_complementares_pericia_medica'],
                        'disabled' => $disabled,
                        'id' => 'informacao_complementares_pericia_medica_cat',
                        'label' => __('agendamento_label_informacao_complementares_pericia_medica_cat')));
                    ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php
                    echo

                    $this->Form->input('AgendamentoCAT.data_reavaliacao_pericia_medica', array('label' => __('agendamento_label_data_reavaliacao') . $isRequerid,
                        'type' => 'text',
                        //'value' => $agCat['data_reavaliacao_pericia_medica'],
                        'class' => 'inputData form-control', 
                        'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                        'onblur' => 'VerificaData(this,this.value)',
                        'disabled' => $disabled,
                        'onmouseout' => 'VerificaData(this,this.value)'
                    ));


                    ?>
                </div>

                <?php if ($tipologiaId !== TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO):
                ?> 
                <div class="col-md-6">
                    <?php
                    $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));
                    echo
                    $this->Form->input('AgendamentoCAT.encaminha_medicina_trabalho_pericia_medica', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'options'=>$arrTipo,
                        //'value' => $agCat['encaminha_medicina_trabalho_pericia_medica'],
                        'empty' => __('label_selecione'),
                        'id' => 'data_reavaliacao',
                        'disabled' => $disabled,
                        'label' => __('agendamento_label_encaminhar_medicina_trabalho')));
                    ?>

                </div>
                <?php endif; ?>
            
                <div class="row" style="padding-left: 1.3em">
                    <div class="col-md-6">
                        <?php
                        echo 

                        $this->Form->input('AtendimentoCAT.nome_medico_assistente', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'type' => 'text',
                            'disabled' => $disabled,
                            'label' => __('nome_medico_assistente')));
                        ?>         
                    </div>
                
                    <div class="col-md-4">
                        <?php
                        echo 

                        $this->Form->input('AtendimentoCAT.crm_medico_assistente', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control soNumero',
                        'type' => 'text',
                        'disabled' => $disabled,
                        'label' => __('crm_medico_assistente')));
                    ?>
                </div>
            </div>
        </fieldset>
    

<!-- Acaba Aqui -->

<div class="row">
    <div class="col-md-3 form-group">
        <?php
        $arrTipo = array('1' => __('label_sim'), '0' => __('label_nao'));

        $il = (isset($this->data['AtendimentoCAT']['nexo_causal_medicina_trabalho'])) ? $this->data['AtendimentoCAT']['nexo_causal_medicina_trabalho'] : null;

        $value = null;
        if ($il == true):
            $value = 1;
        elseif ($il === false):
            $value = 0;
        endif;
        echo
        $this->Form->input('AtendimentoCAT.nexo_causal_medicina_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $arrTipo,
            'value' => $value,
            'disabled' => $disabled,
            'empty' => __('label_selecione'),
            'label' => __('nexo_causal')));
        ?>
    </div>
        <div class="col-md-9 form-group">
            <?php
            echo $this->Form->input('AtendimentoCAT.parecer_medicina_trabalho', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'type' => 'text',
                'disabled' => $disabled,
                'label' => __('label_parecer_medicina_trabalho')));
            ?>
        </div>
 </div>

<div class="row">
    <div class="col-md-12 form-group">
        <?php

         echo $this->Form->input('AtendimentoCAT.tipo_restricao_medicina_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
             'disabled' => $disabled,
            'label' => __('tipo_restricao_medicina_trabalho')));

        ?>

    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <?php
        echo $this->Form->input('AtendimentoCAT.sequelas_medicina_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'disabled' => $disabled,
            'label' => __('sequelas_medicina_trabalho')));

        ?>

    </div>
</div>

<?php if($afastamento == '1'){ ?>

<div class="row">
    <div class="col-md-6">
        <?php

        echo $this->Form->checkbox('AtendimentoCAT.recuperacao_integral_medicina_trabalho',array('disabled' => $disabled, 'hiddenField' => false)) . __('atendimento_rec_integral_medicina_trabalho');
        echo "<br/>";
        echo $this->Form->checkbox('AtendimentoCAT.recuperacao_restrito_mesma_funcao_mt', array('disabled' => $disabled, 'hiddenField' => false)) . __('atendimento_recuperacao_restrito_mesma_funcao_medicina_trabalho');
        echo "<br/>";
        echo $this->Form->checkbox('AtendimentoCAT.recuperacao_restrito_readaptacao_mt', array('disabled' => $disabled, 'hiddenField' => false)) . __('atendimento_recuperacao_restrito_readaptacao_medicina_trabalho');
        ?>
    </div>
    <div class="col-md-6 form-group">
        <?php
        echo $this->Form->checkbox('AtendimentoCAT.invalidez_parcial_medicina_trabalho', array('disabled' => $disabled, 'hiddenField' => false)) . __('atendimento_invalidez_parcial_medicina_trabalho');
        echo "<br/>";
        echo $this->Form->checkbox('AtendimentoCAT.invalidez_total_medicina_trabalho', array('disabled' => $disabled, 'hiddenField' => false)) . __('atendimento_invalidez_total_medicina_trabalho');
        echo "<br/>";
        echo $this->Form->checkbox('AtendimentoCAT.obito_medicina_trabalho', array('disabled' => $disabled, 'hiddenField' => false)) . __('atendimento_obito_medicina_trabalho');
        ?>

    </div>
    <? }?>
            
</div>


