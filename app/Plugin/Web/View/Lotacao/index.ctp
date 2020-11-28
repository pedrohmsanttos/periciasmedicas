<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('lotacao_label_lotacao')), 'exibirBotaoNovo' => true, 'feminino' => true)); ?>

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
                    <div class="col-md-4 form-group">
                        <?= $this->Form->input('nome', array('label' => __('lotacao_label_nome'))); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo $this->Form->input('orgao_origem_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control comboOrgaoOrigem',
                            'options' => $orgaos,
                            'label' => __('lotacao_label_orgao'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo $this->Form->input('Endereco.municipio_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control comboMunicipio',
                            'label' => __('componente_endereco_label_municipio'),
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