<?php echo $this->Form->create($controller, $formCreate); ?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?php echo $this->element('titulo-pagina', array('titulo' => __($title, __('OrgaoOrigem')))); ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('orgao_origem', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('orgao_origem_label_nome') . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('email', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('orgao_origem_label_email') . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('sigla', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control',
                            'label' => __('orgao_origem_label_sigla') . $isRequerid,
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <?php
                        echo
                        $this->Form->input('cnpj', array(
                            'div' => array(
                                'class' => 'form-group'
                            ),
                            'class' => 'form-control cnpj',
                            'maxlength' => 18,
                            'label' => __('orgao_origem_label_cnpj'),
                            'disabled' => $formDisabled
                        ));
                        ?>
                    </div>
                </div>
                <?php echo $this->element('botoes-default-cadastro'); ?>
            </div>
        </section>
    </div>
</div>
<?php echo $this->Form->end(); ?>