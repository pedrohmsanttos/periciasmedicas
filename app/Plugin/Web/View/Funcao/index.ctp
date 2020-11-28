<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar',__('funcao_label_funcoes')), 'exibirBotaoNovo' => true, 'feminino' => true)); ?>

            <div class="panel-body">
                <?= $this->Form->create('Funcao', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required'=> false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'Funcao', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('nome', array('label' => __('funcao_label_nome'))); ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?= $this->Form->input('sigla', array('label' => __('funcao_label_sigla'))); ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>