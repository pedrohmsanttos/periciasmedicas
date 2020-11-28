<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('AtendimentosPendentes')))); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('Atendimento', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'data-acao' => 'consultarAtendimentosPendentes',
                    'url' => array('controller' => 'Atendimento', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <?= $this->Form->hidden('atendimentosPendentes'); ?>
                        <?= $this->Form->input('nome_servidor', array('label' => __('atendimentos_pendentes_label_nome_servidor'), 'maxlength' => 255)); ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?php
                        echo
                        $this->Form->input('data', array('label' => __('atendimentos_pendentes_label_data_pericia'),
                            'type' => 'text',
                            'class' => 'inputData form-control', 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                            'onblur' => 'VerificaData(this,this.value)',
                            'onmouseout' => 'VerificaData(this,this.value)'));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?= $this->Form->input('numero', array('label' => __('atendimentos_pendentes_label_numero'))); ?>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-md-12"></div>
                </div>
                <?= $this->element('botoes-default-consulta', array('acaoBotaoLimpar' => 'indexAtendimentoPendentes')); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>