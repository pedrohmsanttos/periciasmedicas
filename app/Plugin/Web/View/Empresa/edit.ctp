<?php echo $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?php echo $this->element('titulo-pagina', array('titulo' => __($title, __('Empresa')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <?php
                        echo
                        $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('empresa_label_nome') . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo
                        $this->Form->input('cnpj', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control cnpj',
                            'maxlength' => 18,
                            'label' => __('empresa_label_cnpj'),
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                </div>
                <?php echo $this->element('componente_endereco', array('idComboMunicipio'=>'idMunicipioEmpresa', 'municipios' => isset($municipios)? $municipios : array())); ?>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= __('empresa_label_responsavel') ?></legend>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <?php
                            echo
                            $this->Form->input('nome_responsavel', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'class' => 'form-control',
                                'label' => __('empresa_label_nome'),
                                'disabled' => $formDisabled
                            ));
                            ?>
                        </div>
                        <div class="col-md-4 form-group">
                            <?php
                            echo
                            $this->Form->input('telefone_responsavel', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'class' => 'form-control telefone',
                                'label' => __('empresa_label_telefone'),
                                'disabled' => $formDisabled
                            ));
                            ?>
                        </div>
                    </div>
                </fieldset>

                <?php echo $this->element('botoes-default-cadastro'); ?>
            </div>
        </section>
    </div>
</div>
<?php echo $this->Form->end(); ?>