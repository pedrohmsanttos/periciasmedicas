<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('Cid')), 'exibirBotaoNovo' => true)); ?>

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
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('nome', array('label' => __('cid_label_cid'))); ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('Especialidade.nome', array('label' => __('cid_label_especialidade'))); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo $this->Form->input('nome_doenca', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('cid_label_nome_doenca')
                        ));
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