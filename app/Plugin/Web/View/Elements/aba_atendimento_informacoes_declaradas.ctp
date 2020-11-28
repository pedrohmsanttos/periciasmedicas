<!--vinculo -->
<div id="" class="selectedRiscoVidaInsalubridade">
    <div class="row">
        <div class="col-md-5">
            <?php
            //debug($this->data);die;
            $arrTipo = array(ESTATUTARIO => __('agendamento_label_estatutario'), CTD => __('agendamento_label_ctd'), CLT => __('agendamento_label_clt'));

            $value = (isset($this->data['Agendamento']['vinculo'])) ? $this->data['Agendamento']['vinculo'] : '';

            echo
            $this->Form->input('vinculo', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $arrTipo,
                'value' => $value,
                'empty' => __('label_selecione'),
                'label' => __('agendamento_input_vinculo') . $isRequerid,
                'disabled' => true));
            ?>
        </div>
        <div class="col-md-5">
            <?php
            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

            $value= isset($this->data['Agendamento']['gratificacao_risco_vida_saude'])?$this->data['Agendamento']['gratificacao_risco_vida_saude']:'';
            echo
            $this->Form->input('gratificacao_risco_vida_saude', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $arrTipo,
                'value' => $value,
                'empty' => __('label_selecione'),
                'label' => __('agendamento_input_gratificacao_risco_vida_saude') . $isRequerid,
                'disabled' => true));
            ?>
        </div>
    </div>
</div>
<!--end row vinculo-->


<?php if($this->data['Agendamento']['vinculo'] == CTD): ?>
<!--contrato trabalho -->
<div id="fileContratoTrabalho" class="">
    <div class="row">
        <div class="col-md-3">
            <?php
            echo
            $this->Form->input('contrato_trabalho', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'type' => 'file',
                'label' => __('agendamento_contrato_trabalho') . $isRequerid,
                'disabled' => true));
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
                'disabled' => true));
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
                    </ul>
                </div>
            <? }?>
        </div>
    </div>
</div>
<!--end contrato trabalho-->
<?php endif; ?>


<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-3 form-group">
            <?php
            echo
            $this->Form->input('horario_trabalho_inicial', array('div' => array('class' => 'form-group'),
                'class' => 'form-control hour',
                'type' => 'text',
                'value' => $this->data['Agendamento']['horario_trabalho_inicial'],
                'label' => __('agendamento_label_horario_inicial') . $isRequerid,
                'disabled' =>  true));
            ?>
        </div>
        <div class="col-md-3 form-group">
            <?php
            echo
            $this->Form->input('horario_trabalho_final', array('div' => array('class' => 'form-group'),
                'class' => 'form-control hour',
                'type' => 'text',
                'value' => $this->data['Agendamento']['horario_trabalho_final'],
                'label' => __('agendamento_label_horario_final') . $isRequerid,
                'disabled' =>  true));
            ?>
        </div>
    </div>
</div>
<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-8 form-group">
            <?=
            $this->Form->input('atribuicao_funcao', array(
                'div' => array(
                    'class' => 'form-group'
                ),
                'class' => 'form-control',
                'value' => $this->data['Agendamento']['atribuicao_funcao'],
                'onkeyup' => "limitarTamanho(this,2000);",
                'onblur' => "limitarTamanho(this,2000);",
                'label' => __('agendamento_label_atribuicao_funcao') . ': ',
                'disabled' =>  true,
                'type' => 'textarea'
            ));
            ?>
        </div>
    </div>
</div>
<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-8 form-group">
            <?=
            $this->Form->input('atividade_executada', array(
                'div' => array(
                    'class' => 'form-group'
                ),
                'class' => 'form-control',
                'value' => $this->data['Agendamento']['atividade_executada'],
                'onkeyup' => "limitarTamanho(this,2000);",
                'onblur' => "limitarTamanho(this,2000);",
                'label' => __('agendamento_label_atividade_executada') . ': ',
                'disabled' =>  true,
                'type' => 'textarea'
            ));
            ?>
        </div>
    </div>
</div>
<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-8 form-group">
            <?=
            $this->Form->input('informacao_adicional', array(
                'div' => array(
                    'class' => 'form-group'
                ),
                'class' => 'form-control',
                'value' => $this->data['Agendamento']['informacao_adicional'],
                'onkeyup' => "limitarTamanho(this,2000);",
                'onblur' => "limitarTamanho(this,2000);",
                'label' => __('agendamento_label_informacao_adicional') . ': ',
                'disabled' =>  true,
                'type' => 'textarea'
            ));
            ?>
        </div>
    </div>
</div>

<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-5">
            <?php
            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

            $value = (isset($this->data['Agendamento']['curso_formacao'])) ? $this->data['Agendamento']['curso_formacao'] : '';

            echo
            $this->Form->input('curso_formacao', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $arrTipo,
                'value' => $value,
                'empty' => __('label_selecione'),
                'label' => __('agendamento_input_curso_formacao') . $isRequerid,
                'disabled' =>  true));
            ?>
        </div>
    </div>
</div>
<?php if($this->data['Agendamento']['curso_formacao'] == 1): ?>
<div id="fileCursoFormacao" class="">
    <div class="row">
        <div class="col-md-5">
            <?php

            echo
            $this->Form->input('curso_formacao_certificado', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'type' => 'file',
                'label' => __('agendamento_curso_formacao_certificado') . $isRequerid,
                'disabled' =>  true));
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
                    </ul>
                </div>
            <? }?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-5">
            <?php
            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

            $value = (isset($this->data['Agendamento']['desvio_funcao'])) ? $this->data['Agendamento']['desvio_funcao'] : '';

            echo
            $this->Form->input('desvio_funcao', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $arrTipo,
                'value' => $value,
                'empty' => __('label_selecione'),
                'label' => __('agendamento_input_desvio_funcao') . $isRequerid,
                'disabled' =>  true));
            ?>
        </div>
    </div>
</div>


<div class="selectedRiscoVidaInsalubridade ">
    <div class="row">
        <div class="col-md-5">
            <?php
            $arrTipo = array('1' => __('agendamento_label_sim'), '0' => __('agendamento_label_nao'));

            $value = (isset($this->data['Agendamento']['treinamento_desvio_funcao'])) ? $this->data['Agendamento']['treinamento_desvio_funcao'] : '';

            echo
            $this->Form->input('treinamento_desvio_funcao', array('div' => array('class' => 'form-group'),
                'class' => 'form-control',
                'options' => $arrTipo,
                'value' => $value,
                'empty' => __('label_selecione'),
                'label' => __('agendamento_input_treinamento_desvio_funcao') . $isRequerid,
                'disabled' =>  true));
            ?>
        </div>
    </div>
</div>

<?php if($this->data['Agendamento']['treinamento_desvio_funcao'] == 1): ?>
<div id="selectedTreinamentoDesvioFuncao" class="">
    <div class="row">
        <div class="col-md-8 form-group">
            <?=
            $this->Form->input('descricao_desvio_funcao', array(
                'div' => array(
                    'class' => 'form-group'
                ),
                'class' => 'form-control',
                'value' => $this->data['Agendamento']['descricao_desvio_funcao'],
                'onkeyup' => "limitarTamanho(this,2000);",
                'onblur' => "limitarTamanho(this,2000);",
                'label' => __('agendamento_label_descricao_desvio_funcao') . ': ',
                'disabled' =>  true,
                'type' => 'textarea'
            ));
            ?>
        </div>
    </div>
</div>
<?php endif; ?>