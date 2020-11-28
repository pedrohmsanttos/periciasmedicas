<div class="row">
    <audio  controls class="displayNone" id="playMusic">
        <source src="<?= $this->Html->url(array('plugin' => 'web', 'action' => '/audio/alert_atendimento.ogg', 'controller' => false), false) ?>" type="audio/ogg">
        <source src="<?= $this->Html->url(array('plugin' => 'web', 'action' => '/audio/alert_atendimento.mp3', 'controller' => false), false) ?>" type="audio/mpeg">
        Seu navegador não possui suporte ao elemento audio
    </audio>
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('atendimento_titulo_consulta'))); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('Agendamento', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsultaAgendamento',
                    'url' => array('controller' => 'Atendimento', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <?php
                        $l = $lockUnidade;
                        $disabled = !empty($l);
                        ?>
                        <?=
                        $this->Form->input('Unidade', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $unidadeAtendimento,
                            'label' => __('Unidade'),
                            'empty' => __('label_selecione'),
                            'disabled' => $disabled,
                            'value' => $l
                        ));
                        ?>
                    </div>
                    <div class="col-md-3 form-group">
                        <?=
                        $this->Form->input('Tipologia', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $tipologias,
                            'label' => __('atendimento_label_tipologia'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?= $this->Form->input('cpf', array('label' => __('atendimento_label_cpf'), 'class' => 'form-control cpf')); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <div class="col-md-5 form-group">
                            <?=
                            $this->Form->input('hora_inicial', array('label' => __('atendimento_label_hora'), 'class' => 'form-control hour'));
                            ?>
                        </div>
                        <div class="col-md-2 form-group" style="width: 2%;text-align: center;margin-top: 7.5%; margin-left: -9px;">
                            <?= $this->Form->label(null, __('ÀS')); ?>
                        </div>
                        <div class="col-md-5 form-group" style="margin-top: 5.5%;">
                            <?=
                            $this->Form->input('hora_final', array('maxlength' => 150, 'label' => false, 'class' => 'form-control inputSemLabel hour'));
                            ?>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 form-group"></li></div>
                    <div class="col-md-1 form-group"><li class="fa fa-arrow-left fa-2x float-right voltarData"></li></div>
                    <div class="col-md-2 form-group">
                        <?=
                        $this->Form->input('data', array('maxlength' => 150, 'label' => false, 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                            'onblur' => 'VerificaData(this,this.value)', "id" => "dataAgendamento", "style" => "text-align: center",
                            'onmouseout' => 'VerificaData(this,this.value)',
                            'class' => 'form-control inputData'));
                        ?>
                    </div>
                    <div class="col-md-1 form-group"><li class="fa fa-arrow-right fa-2x float-left avancarData"></li></div>
                    <div class="col-md-4 form-group"></div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>
<div id="dialog-confirmacao_acao" class="displayNone">
    <p style="margin: 0"><?php echo __('atendimento_dialog_tem_certeza_que_deseja'); ?> <span id="parametroAcao"></span>
        <?php echo __('atendimento_dialog_agendamento_servidor'); ?> <span id="parametroNomeDialog"></span>
        <?php echo __('atendimento_dialog_cpf'); ?><span id="parametroCpf"></span>
        <?php echo __('atendimento_dialog_marcado_dia'); ?><span id="parametroData"></span>
        <?php echo __('atendimento_dialog_as'); ?><span id="parametroHora"></span> ?
        <span id="confirmacao-prioritario" class="displayNone"><br>Atendimento prioritário? <input type="checkbox" id="check_prioritario" />Sim</span>
    </p>
</div>

<div id="dialog-alocamento_perito_sala" class="displayNone">
    <p><?php echo __('atendimento_dialog_alocar_novo_perito'); ?></p>
</div>

<div id="dialog-alocamento_mesmo_perito_mesma_sala" class="displayNone">
    <p><?php echo __('atendimento_dialog_alocar_mesma_perito_mesma_sala'); ?></p>
</div>

<div id="dialog-alocamento_perito_nova_sala" class="displayNone">
    <p><?php echo __('atendimento_dialog_alocar_novo_perito_nova_sala'); ?></p>
</div>

<div id="dialog-excluir_sala_gerenciamento" class="displayNone">
    <p><?php echo __('atendimento_dialog_limpar_sala'); ?><span id="salaConfirmaExclusao"></span>?</p>
</div>

<div id="dialog-gerenciamento-salas" class="displayNone panel">
    <div class="row col-sm-12">
        <fieldset class="scheduler-border">
            <?php $isRequerid = '*'; ?>
            <legend class="scheduler-border"><?php echo __('gerenciar_sala_lista_salas') ?></legend>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    $disabledUnidade = false;
                    if ($unidadeAtendimentoIdUser) {
                        $disabledUnidade = true;
                    }
                    echo $this->Form->input('UnidadeAtendimentoSala', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'selectUnidadeAtendimento',
                        'options' => $unidadeAtendimento,
                        'value' => $unidadeAtendimentoIdUser,
                        'disabled' => $disabledUnidade,
                        'label' => __('atendimento_label_unidade_atendimento') . $isRequerid,
                        'empty' => __('label_selecione')));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $this->Form->input('sala', array('div' => array('class' => 'form-group'),
                        'class' => 'form-control',
                        'id' => 'inputSala',
                        'label' => __('gerenciar_sala_sala') . $isRequerid));
                    ?>
                </div>
                <div class="col-md-5">

                </div>
            </div>
            <div class="row">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo __('atendimento_label_perito') ?></legend>
                    <div class="col-md-4 form-group">
                        <?php
                        echo $this->Form->input('limitConsulta', array('type' => 'hidden', 'id' => 'limitConsulta'));

                        echo $this->Form->input('usuario_perito_id', array(
                            'type' => 'hidden',
                            'id' => 'hidPeritoId'
                        ));
                        $urlAutoCompleteNome = Router::url(array('controller' => "GerenciamentoSala", 'action' => 'getNomeServidor'), true);
                        echo $this->Form->input('nomePerito', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'maxlength' => 100,
                            'id' => 'inputNomePerito',
                            'data-url' => $urlAutoCompleteNome,
                            'label' => __('gerenciar_sala_nome_perito') . $isRequerid));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        $urlAutoCompleteCpf = Router::url(array('controller' => "GerenciamentoSala", 'action' => 'getCpfServidor'), true);
                        echo $this->Form->input('cpfPerito', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control cpf',
                            'id' => 'inputCpf',
                            'data-url' => $urlAutoCompleteCpf,
                            'label' => __('gerenciar_sala_cpf') . $isRequerid));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        $urlAutoCompleteNumRegistro = Router::url(array('controller' => "GerenciamentoSala", 'action' => 'getNumRegistroServidor'), true);
                        echo $this->Form->input('numeroRegistro', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'maxlength' => 15,
                            'id' => 'inputNumeroRegistro',
                            'data-url' => $urlAutoCompleteNumRegistro,
                            'label' => __('gerenciar_sala_numero_registro') . $isRequerid));
                        ?>
                    </div>
                </fieldset>
            </div>
            <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?php echo __('gerenciar_sala_tipologia') . $isRequerid ?></legend>
                <div class="row">
                    <div class="col-md-6 form-group" style="width: 28%;">
                         <?php echo $this->Form->label(null, __('gerenciar_sala_atribuidas_perito') . ': '); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->label(null, __('gerenciar_sala_selecionadas') . ': '); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        $urlSearchTipologia = Router::url(array('controller' => "GerenciamentoSala", 'action' => 'getTipologias'), true);
                        echo $this->Form->input('Tipologia', array(
                            'multiple' => 'multiple',
                            'id' => 'selectTipologiaSala',
                            'data-url' => $urlSearchTipologia,
                            'options' => [],
                            'class' => 'sigas_multi_select alturaPickList',
                            'div' => array('class' => 'form-group multi-select '),
                            'label' => false));
                        ?>
                    </div>
                    <div class="col-md-8"></div>
                </div>
            </fieldset>

            <div class="row">
                <?php
                $url = Router::url(array('controller' => "GerenciamentoSala"), true);
                echo $this->Form->button(__('bt_adicionar'), array(
                    'class' => 'btn fa  fa-save estiloBotao btn-info float-right',
                    'type' => 'button',
                    'id' => 'adicionarSala',
                    'data-url' => $url,
                    'href' => 'javascript:void(0)'
                ));
                ?>
            </div>
            <br/>
            <div class="row">
                <div id="gridGerenciarSalas"></div>
            </div>
        </fieldset>
    </div>
</div>
<div id="dialog-confirmarPresenca" class="displayNone zTop" >
    <p>O atendimento <span id="confirmacao-tipologia"></span> para <span id="confirmacao-servidor"></span> é prioritário ?</p>
    <input type="checkbox" name="chk_prioritario" id="chk_prioritario"><span>SIM</span>
</div>
<? //echo$this->Html->script('Admin.atendimento', array('block' => 'script')); ?>
<? echo$this->Html->script('Web.atendimento', array('block' => 'script')); ?>
