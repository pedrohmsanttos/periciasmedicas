<?php
echo $this->Form->hidden( 'AtendimentoInspecao.id', array(
    'id' =>'hidAtendimentoInspecao'
));
?>
<div class="row" >
    <div class="col-md-12">
        <style>
            .form-control div.select label{
                float:left;
                margin-right: 20px;
            }
        </style>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Condições físicas e ambiental</legend>
            <?php
            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'id' => 'cfa_piso',
                    'title' =>'Piso',
                    'options' => $cfa_piso,
                    'column' => 6,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Paredes',
                    'options' => $cfa_paredes,
                    'column' => 6,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'id' => 'cfa_escadas',
                    'title' =>'Escadas, Rampas e Parapeito',
                    'options' => $cfa_escadas,
                    'column' => 12,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Iluminação',
                    'options' => $cfa_iluminacao,
                    'column' => 12,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Instalações Elétricas',
                    'options' => $cfa_inst_eletricas,
                    'column' => 12,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Máquinas',
                    'options' => $cfa_maquinas,
                    'column' => 12,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Higiene',
                    'options' => $cfa_higiene,
                    'column' => 6,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            '<style>
            .form-linear {}
            .form-linear label {
                float: left;
                margin: 13px 5px;
                font-size: 12px;
                font-weight: normal;
            }

            .form-linear input {
                float: left;
                margin: 5px;
                width: 80%;
            }
            .padt{
                padding-top: 6px;
            }
            </style>'.
            $this->PForm->checkbox(
                array(
                    'class' => 'padt',
                    'name' => 'Tag',
                    'title' =>'Instalações',
                    'options' => $cfa_instalacoes,
                    'column' => 5,
                    'disabled' => $formDisabled)
            ).'
            <div class="col-md-6">
                '.$this->Form->input('AtendimentoInspecao.cfa_instalacoes_outros', array('div' => array('class' => 'form-linear'),
                    'class' => 'form-control',
                    'label' => 'Outros',
                    'disabled' => $formDisabled)).'
            </div>
            </div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Caixa de Primeiros Socorros',
                    'options' => $cfa_cxps,
                    'column' => 12,
                    'disabled' => $formDisabled)
            ).'</div>';

            echo '<div class="row row-item">'.
            $this->PForm->checkbox(
                array(
                    'name' => 'Tag',
                    'title' =>'Equipamento de Proteção Individual – E.P.I',
                    'options' => $cfa_epi,
                    'column' => 12,
                    'disabled' => $formDisabled)
            ).'</div>';
            ?>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Medições</legend>
            <div class="row">
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.m_poluicao', array('div' => array('class' => 'form-group'),
                        'id' => 'm_poluicao',
                        'class' => 'form-control',
                        'label' => 'Poluição',
                        'data-type' =>'+float2',
                        'disabled' => $formDisabled));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.m_ruido', array('div' => array('class' => 'form-group'),
                        'id' => 'm_ruido',
                        'class' => 'form-control',
                        'label' => 'Ruído (em decibéis)',
                        'data-type' =>'+float2',
                        'disabled' => $formDisabled));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.m_temperatura', array('div' => array('class' => 'form-group'),
                        'id' => 'm_temperatura',
                        'class' => 'form-control',
                        'label' => 'Temperatura (em grau celsius)',
                        'data-type' =>'+float2',
                        'disabled' => $formDisabled));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.m_luminosidade', array('div' => array('class' => 'form-group'),
                        'id' => 'm_luminosidade',
                        'class' => 'form-control',
                        'label' => 'Luminosidade (em lux)',
                        'data-type' =>'+float2',
                        'disabled' => $formDisabled));
                    ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Riscos Inerentes a Atividade</legend>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.ria_riscos_fisico', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'label' => 'Riscos Físico ',
                        'disabled' => $formDisabled));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.ria_riscos_quimico', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'label' => 'Riscos Químico ',
                        'disabled' => $formDisabled));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo
                    $this->Form->input('AtendimentoInspecao.ria_riscos_biologico', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'label' => 'Riscos Biológico ',
                        'disabled' => $formDisabled));
                    ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<div class="row">
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
            'label' => "Parecer" . $isRequerid));
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('AtendimentoInspecao.recomendacoes', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'type' => 'textarea',
            'disabled' => $formDisabled,
            'onkeyup' => "limitarTamanho(this,8000);",
            'onblur' => "limitarTamanho(this,8000);",
            'label' => "Recomendações"));
        ?>
    </div>
</div>
<? //echo $this->Html->script('Admin.inspecao', array('block' => 'script')); ?>
<? echo $this->Html->script('Web.inspecao', array('block' => 'script')); ?>