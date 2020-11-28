<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Tipologia')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?=
                        $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('tipologia_label_nome') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?=
                        $this->Form->input('legislacao', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'onkeyup' => "limitarTamanho(this,8000);",
                            'onblur' => "limitarTamanho(this,8000);",
                            'label' => __('tipologia_label_legislacao') . ': ',
                            'disabled' => $formDisabled,
                            'type' => 'textarea'
                        ));
                        ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-cadastro'); ?>
            </div>
        </section>
    </div>
</div>
<?= $this->Form->end(); ?>