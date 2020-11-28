<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">
            <header class="panel-heading"> 
                <div class="row">
                    <div class="col-md-10">
                        <div class="panel-title"><?= __('processo_titulo') ?></div>
                    </div>
                </div>
            </header>
            <div class="panel-body">
                <?=
                $this->Form->create('Atendimento', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'type' => 'get',
                    'id' => 'formularioConsulta',
                    'data-acao' => 'consultarProcessos',
                    'url' => array('controller' => 'Atendimento', 'action' => 'index')
                ));
                ?>
                <div class="displayNone">
                    <?php
                    echo $this->Form->select("procesos_selecionados", array(), array('multiple' => true, 'id' => 'procesos_selecionados'));
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('processo_label_periodo') ?></legend>

                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_inicial', array('maxlength' => 150, 'label' => __('processo_label_de'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                                    ?>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_final', array('maxlength' => 150, 'label' => __('processo_label_ate'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData inputSemLabel'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-8 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Servidor</legend>
                            <div class="row">
                                <div class="col-md-4 form-group" >
                                    <?=
                                    $this->Form->input('cpf', array('maxlength' => 150, 'label' =>'CPF', 'class' => 'cpf form-text form-control'));
                                    ?>
                                </div>
                                <div class="col-md-8 form-group" >
                                    <?=
                                    $this->Form->input('nome', array('maxlength' => 150, 'label' =>'Nome', 'class' => 'form-text form-control'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <?= $this->Form->input('numero_processo', array('maxlength' => 150, 'label' =>'Número do Processo', 'class' => 'form-text form-control soNumero')); ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('tipologia_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $tipologias,
                            'label' => __('atendimentos_pendentes_label_tipologia'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?php
                        echo
                        $this->Form->input('tipo_situacao', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $situacoes,
                            'label' => __('processo_label_status'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>
<? //echo $this->Html->script('Admin.processos', array('block' => 'script')); ?>
<? echo $this->Html->script('Web.processos', array('block' => 'script')); ?>
