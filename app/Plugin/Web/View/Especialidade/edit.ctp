<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Especialidades')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?=
                        $this->Form->input('nome', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control upperCaseField',
                            'label' => __('especialidade_label_nome') . ': '.$isRequerid,
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
<?= $this->Form->end();?>