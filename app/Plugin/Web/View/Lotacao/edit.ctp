<?php echo $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?php echo $this->element('titulo-pagina', array('titulo' => __($title, __('lotacao_label_lotacao')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <?php
                        echo $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('lotacao_label_nome') . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo $this->Form->input('orgao_origem_id', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control comboOrgaoOrigem',
                            'options' => $orgaos,
                            'label' => __('lotacao_label_orgao'). $isRequerid,
                            'empty' => __('label_selecione'),
                            'disabled' => $formDisabled));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo $this->Form->input('telefone', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control telefone',
                            'label' => __('lotacao_label_telefone'),
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                </div>
                <?php echo $this->element('componente_endereco', array('idComboMunicipio'=>'idLotacaoEmpresa', 'municipios' => isset($municipios)? $municipios : array())); ?>
                <?php echo $this->element('botoes-default-cadastro'); ?>
            </div>
        </section>
    </div>
</div>
<?php echo $this->Form->end(); ?>