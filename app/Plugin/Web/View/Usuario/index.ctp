<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('Usuario')), 'exibirBotaoNovo' => true)); ?>

            <div class="panel-body">
                <?= $this->Form->create($nameModel, array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => $nameModel, 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-5 form-group">
                        <?php
                        echo
                        $this->Form->input('TipoUsuario', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $tipoUsuario,
                            'label' => __('usuario_label_tipo_usuario'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-3 form-group">
                        <?php
                        echo
                        $this->Form->input('perfil_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $perfis,
                            'label' => __('usuario_label_perfis'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?php
                        echo
                        $this->Form->input('ativado', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => [true=>'Ativo', false=>'Inativo'],
                            'label' => __('usuario_label_ativo'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?= $this->Form->input('nome', array('label' => __('usuario_label_nome_usuario'))); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('usuario_label_documentos') ?></legend>
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('cpf', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control cpf',
                                        'label' => __('usuario_label_cpf'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                                <div class="col-md-2 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('rg', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'label' => __('usuario_label_rg'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                                <div class="col-md-2 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('orgao_expedidor', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'maxlength' => 10, 
                                        'label' => __('usuario_label_orgao_expedidor'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                                <div class="col-md-2 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('numero_registro', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'maxlength' => 15, 
                                        'label' => __('usuario_label_numero_registro'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('usuario_label_vinculo') ?></legend>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('orgaoOrigem', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'options' => $orgaoOrigem,
                                        'label' => __('usuario_label_orgao_origem'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                                <div class="col-md-4 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('matricula', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'maxlength' => 10, 
                                        'label' => __('usuario_label_matricula'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                                <div class="col-md-4 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('cargo', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'options' => $cargo,
                                        'label' => __('usuario_label_cargo'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('usuario_label_lotacao') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <?php
                                    echo
                                    $this->Form->input('lotacaoNome', array('div' => array('class' => 'form-group'),
                                        'class' => 'form-control',
                                        'maxlength' => 255, 
                                        'label' => __('usuario_label_lotacao_nome'),
                                        'empty' => __('label_selecione')));
                                    ?>
                                </div>
                            </div>
                            <?= $this->element('componente_uf_municipio',['isRequerid'=>'','formDisabled'=>false,'idComboMunicipio'=>'consultaMunicipio']); ?>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <?= $this->element('botoes-default-consulta'); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>