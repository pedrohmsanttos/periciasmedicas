<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Cargo')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('cargo_label_nome') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?php
                        echo
                        $this->Form->input('sigla', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('cargo_label_sigla') . ': ',
                            'disabled' => $formDisabled
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