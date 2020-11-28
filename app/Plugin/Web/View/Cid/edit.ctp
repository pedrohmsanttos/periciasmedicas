<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Cid')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('cid_label_cid') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?php
                        echo $this->Form->input('nome_doenca', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('cid_label_nome_doenca') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?=
                        $this->Form->input('descricao', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'onkeyup' => "limitarTamanho(this,2000);",
                            'onblur' => "limitarTamanho(this,2000);",
                            'label' => __('cid_label_descricao') . ': ' . $isRequerid,
                            'disabled' => $formDisabled,
                            'type' => 'textarea'
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('cid_label_associar_especialidades') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group" style="width: 55%;">
                                    <?php echo $this->Form->label(null, __('cid_label_especialidades')); ?>
                                </div>
                                 <div class="form-group">
                                    <?php echo $this->Form->label(null, __('cid_label_especialidades_selecionadas')); ?>
                                </div>
                            </div>
                            <?= $this->element('componente_acoes_pickList', array("target" => 'sigas_multi_select'))?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('Especialidade', array('options' => $especialidades,
                                        'id' => 'sigas_multi_select',
                                        'multiple' => 'multiple',
                                        'class' => 'sigas_multi_select alturaPickList',
                                        'disabled' => $formDisabled,
                                        'selected' => $selected,
                                        'div' => array('class' => 'form-group multi-select '),
                                        'label' => false));
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('cid_label_associar_unidades') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group" style="width: 55%;">
                                    <?php echo $this->Form->label(null, __('cid_label_unidades')); ?>
                                </div>
                                 <div class="form-group">
                                    <?php echo $this->Form->label(null, __('cid_label_unidades_selecionadas')); ?>
                                </div>
                            </div>
                            <?= $this->element('componente_acoes_pickList', array("target" => 'unidade_multi_select'))?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('UnidadeAtendimento', array('options' => $unidades,
                                        'id' => 'unidade_multi_select',
                                        'multiple' => 'multiple',
                                        'class' => 'sigas_multi_select alturaPickList',
                                        'disabled' => $formDisabled,
                                        'selected' => $selectedUnidades,
                                        'div' => array('class' => 'form-group multi-select '),
                                        'label' => false));
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row col-md-12">
                    <?php
                    if ($acao == Configure::read('ACAO_EXCLUIR')):
                        $this->Form->unlockField('Especialidade.Especialidade');
                    endif;
                    ?>
                    <?= $this->element('botoes-default-cadastro'); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?= $this->Form->end(); ?>