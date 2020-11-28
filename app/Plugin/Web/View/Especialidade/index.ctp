<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar',__('Especialidades')), 'exibirBotaoNovo' => true, 'feminino' => true)); ?>

            <div class="panel-body">
                <?= $this->Form->create($nameModel, array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required'=> false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => $nameModel, 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?= $this->Form->input('nome', array('label' => __('especialidade_label_nome').':')); ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>