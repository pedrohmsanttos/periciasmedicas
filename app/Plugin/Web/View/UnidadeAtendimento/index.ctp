<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('UnidadesAtendimento')), 'exibirBotaoNovo' => true, 'feminino' => true)); ?>

            <div class="panel-body">
                <?=
                $this->Form->create($nameModel, array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => $nameModel, 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <?= $this->Form->input('nome', array('label' => __('unidade_atendimento_label_nome'))); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?= $this->Form->input('cnpj',
                                array('maxlength' => 18,
                                    'label' => __('unidade_atendimento_label_cnpj'),
                                    'class'=>'form-control cnpj')); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <?php
                        $urlAutoCompleteNome = Router::url(array('controller' => "UnidadeAtendimento", 'action' => 'getNomeResponsavel'), true);
                        echo
                        $this->Form->input('Endereco.municipio_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control comboMunicipio',
                            'options'=> $municipiosUnidade,
                            'label' => __('unidade_atendimento_label_municipio'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-8 form-group">
                        <?= $this->Form->input('nome_responsavel', array('label' => __('unidade_atendimento_label_responsavel'), 
                            'id' => 'inputNomeResponsavel', 
                            'maxlength' => 255,
                            'data-url' => $urlAutoCompleteNome, 
                            'data-telefone' => 'false')); ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>
<?php //echo $this->Html->script('Admin.unidade', array('block' => 'script')); ?>
<?php echo $this->Html->script('Web.unidade', array('block' => 'script')); ?>