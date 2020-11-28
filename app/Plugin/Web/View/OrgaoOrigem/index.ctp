<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('orgao_origem_label_orgaos')), 'exibirBotaoNovo' => true)); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('OrgaoOrigem', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'OrgaoOrigem', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('orgao_origem', array('label' => __('orgao_origem_label_nome'))); ?>
                    </div>
                    <div class="col-md-3 form-group">
                        <?= $this->Form->input('sigla', array('label' => __('orgao_origem_label_sigla'))); ?>
                    </div>
                    <div class="col-md-3 form-group">
                        <?= $this->Form->input('cnpj', array('maxlength' => 18, 'label' => __('orgao_origem_label_cnpj'), 'class' => 'form-control cnpj')); ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>