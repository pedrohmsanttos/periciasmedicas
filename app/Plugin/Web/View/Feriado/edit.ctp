<?= $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?= $this->element('titulo-pagina', array('titulo' => __($title, __('Feriado')))); ?>
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
                            'label' => __('feriado_label_nome') . ': ' . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?php
                            echo 
                            $this->Form->input('data_feriado', array(
                                'div' => array(
                                    'class' => 'form-group'
                                ),
                                'type' => 'text',
                                'maxlength' => 150, 
                                'label' => 'Data' . ': ' . $isRequerid,
                                'disabled' => $formDisabled,
                                'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                'onblur' => 'VerificaData(this,this.value)', 'id' => 'dataInicial',
                                'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                        ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for=""><?= __('feriado_label_recorrente'); ?></label>
                            <br/>
                            <?php
                               echo $this->Form->checkbox('feriado_recorrente', array(
                                    'disabled' => ($formDisabled)
                                )); 
                            ?>
                        </div>
                    </div>
                </div>
                <?= $this->element('botoes-default-cadastro'); ?>
            </div>
        </section>
    </div>
</div>
<?= $this->Form->end(); ?>