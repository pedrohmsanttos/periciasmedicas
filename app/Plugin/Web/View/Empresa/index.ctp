<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('empresa_label_empresas')), 'exibirBotaoNovo' => true, 'feminino' => true)); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('Empresa', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'Empresa', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <?= $this->Form->input('nome', array('label' => __('empresa_label_nome'))); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?= $this->Form->input('cnpj', array('maxlength' => 18, 'label' => __('empresa_label_cnpj'), 'class' => 'form-control cnpj')); ?>
                    </div>
                </div>
               <?php echo $this->element('componente_uf_municipio', array('isRequerid' => '','idComboMunicipio'=>'idMunicipioEmpresa', 'formDisabled' => false)); ?>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('empresa_label_responsavel') ?></legend>
                            <?= $this->Form->input('nome_responsavel', array('label' => __('empresa_label_nome'))); ?>
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