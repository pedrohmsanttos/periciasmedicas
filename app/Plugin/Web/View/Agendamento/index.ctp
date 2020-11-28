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
<?php
$tipoUsuario = CakeSession::read('Auth.User.tipo_usuario_id');

if ($tipoUsuario == USUARIO_INTERNO):

    // echo $this->Html->script('Admin.consultaAgendamento', array('block' => 'script'));
    echo $this->Html->script('Web.consultaAgendamento', array('block' => 'script'));
    ?>
    <div class="row">
        <div class="col-md-12">
        
            <div data-collapsed="0" class="panel">

                <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('Agendamentos')), 'exibirBotaoNovo' => true)); ?>

                <div class="panel-body">
                    <?=
                    $this->Form->create('Agendamento', array(
                        'inputDefaults' => array(
                            'class' => 'form-control',
                            'required' => false
                        ),
                        'id' => 'formularioConsultaAgendamentoInterno',
                        'url' => array('controller' => 'Agendamento', 'action' => 'index')
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
                                <legend class="scheduler-border"><?php echo __('agendamento_input_periodo') ?></legend>

                                <div class="row">
                                    <div class="col-md-6 form-group" >
                                        <?=
                                        $this->Form->input('data_inicial', array('maxlength' => 150, 'label' => __('agendamento_input_de'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                            'onblur' => 'VerificaData(this,this.value)', 'id' => 'dataInicial',
                                            'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                                        ?>
                                    </div>
                                    <div class="col-md-6 form-group" >
                                        <?=
                                        $this->Form->input('data_final', array('maxlength' => 150, 'label' => __('agendamento_input_ate'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                            'onblur' => 'VerificaData(this,this.value)', 'id' => 'dataFinal',
                                            'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData inputSemLabel'));
                                        ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-4 form-group">
                            <?=
                            $this->Form->input('Tipologia', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $tipologias,
                                'label' => __('agendamento_input_tipologia'),
                                'empty' => __('label_selecione')));
                            ?>
                        </div>
                        <div class="col-md-4 form-group">
                            <?=
                            $this->Form->input('unidade_atendimento_id', array('div' => array('class' => 'form-group'),
                                'class' => 'form-control',
                                'options' => $unidadeAtendimento,
                                'label' => __('agendamento_input_unidade_atendimento'),
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

    <?php
else :
    echo $this->element('consulta_agendamento_servidor');
endif;
?>

<!-- Modal de alerta -->
<div id="dialog-alerta-generico" class="displayNone zTop" >
    <p><?=$this->Session->flash('dialogAlertMsg')?></p>
</div>
