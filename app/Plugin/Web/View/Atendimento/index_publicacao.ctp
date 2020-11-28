
<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">
            <header class="panel-heading"> 
                <div class="row">
                    <div class="col-md-10">
                        <div class="panel-title">Lista de Publicações</div>
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
                    'url' => array('controller' => 'Atendimento', 'action' => 'consultarPublicacoes')
                ));
                ?>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('agendamento_input_servidor') ?></legend>
                            <div class="row">
                                <div class="col-md-8">
                                    <?= $this->Form->input('nome', array('label' => __('agendamento_input_nome_servidor'), 'maxlength' => 150)); ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $this->Form->input('cpf', array('label' => __('agendamento_input_cpf_servidor'), 'class' => 'form-control cpf')); ?>
                                </div>
                        </fieldset>

                    </div>
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
                    <div class="col-md-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('usuario_label_vinculo') ?></legend>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('orgaoOrigem', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'options' => $orgaoOrigem,
                                        'label' => __('usuario_label_orgao_origem'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
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
