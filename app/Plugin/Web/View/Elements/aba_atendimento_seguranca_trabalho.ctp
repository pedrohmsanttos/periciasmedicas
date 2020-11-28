<?php
echo $this->Form->input('AtendimentoCAT.id', array(
    'type' => 'hidden'
));
if(!$formDisabled){
    $disabled = !$isEngenheiro || $this->data['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO;
}else{
    $disabled = $formDisabled;
}

?>
<div class="row">
    <div class="col-md-3 form-group">
        <?php
        echo
        $this->Form->input('AtendimentoCAT.data_cat_recebida_seguranca_trabalho', array('label' => __('data_cat_recebida') . $isRequerid,
            'type' => 'text',
            'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
            'onblur' => 'VerificaData(this,this.value)',
            'disabled' => $disabled,
            'onmouseout' => 'VerificaData(this,this.value)'));
        ?>
    </div>
    <div class="col-md-3 form-group">
        <?php
        echo $this->Form->input('AtendimentoCAT.num_protocolo_seguranca_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'text',
            'disabled' => $disabled,
            'label' => __('num_protocolo')));


        ?>
    </div>
    <div class="col-md-3 form-group">
        <?php
        $arrTipo = array('1' => __('label_sim'), '0' => __('label_nao'));

        $il = (isset($this->data['AtendimentoCAT']['inspecao_local_seguranca_trabalho'])) ? $this->data['AtendimentoCAT']['inspecao_local_seguranca_trabalho'] : null;

        $value = null;
        if ($il == true):
            $value = 1;
        elseif ($il === false):
            $value = 0;
        endif;
        echo
        $this->Form->input('AtendimentoCAT.inspecao_local_seguranca_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $arrTipo,
            'value' => $value,
            'disabled' => $disabled,
            'empty' => __('label_selecione'),
            'label' => __('inspecao_local')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <?php
        echo $this->Form->input('AtendimentoCAT.causa_inspecao_local_seguranca_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'text',
            'disabled' => $disabled,
            'label' => __('causa_inspecao_local')));
        ?>

    </div>
</div>


<div class="row">
    <div class="col-md-6 form-group">
        <?php
        $arrOcorrencia = array('0' => __('atendimento_label_certa'), '1' => __('atendimento_label_provavel'), '2' => __('atendimento_label_improvavel'), '3' => __('atendimento_label_rara'));

       echo  $this->Form->input('AtendimentoCAT.probabilidade_ocorrencia_seguranca_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $arrOcorrencia,
            'id' => 'selectTipoIsencao',
           'disabled' => $disabled,
            'label' => __('atendimento_label_probabilidade_ocorrencia'),
            'empty' => __('label_selecione')));
        ?>

    </div>
    <div class="col-md-6 form-group">
        <?php
        $arrOcorrencia = array('0' => __('atendimento_label_leve'), '1' => __('atendimento_label_moderada'), '2' => __('atendimento_label_grave'), '3' => __('atendimento_label_fatal'));

       echo  $this->Form->input('AtendimentoCAT.consequencia_evento_seguranca_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'options' => $arrOcorrencia,
           'disabled' => $disabled,
            'label' => __('atendimento_label_consequencia_evento_seguranca_trabalho'),
            'empty' => __('label_selecione')));
        ?>

    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <?php
        echo $this->Form->input('AtendimentoCAT.informacao_complementares_seguranca_trabalho', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type'=>'textarea',
            'disabled' => $disabled,
            'label' => __('informacao_complementares_seguranca_trabalho')));

        ?>

    </div>
</div>

