<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', 'Auditoria'))); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('Auditoria', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'Auditoria', 'action' => 'index')
                ));
                ?>


                <div class="row">
                    <div class="col-md-6 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?php echo __('processo_label_periodo') ?></legend>

                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_inicial', array('maxlength' => 150, 'label' => __('processo_label_de'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                                    ?>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('data_final', array('maxlength' => 150, 'label' => __('processo_label_ate'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData inputSemLabel'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <?= $this->Form->input('nome', array('label' => __('usuario_label_nome_usuario'))); ?>
                    </div>
                    <div class="col-md-2 form-group">
                        <?= $this->Form->input('pk_log', array('label' => __('ID Área'))); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo
                        $this->Form->input('tipo_operacao', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $tipo_operacao,
                            'label' => __('tipo_operacao'),
                            'empty' => __('label_selecione')));
                        ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <?php
                        echo
                        $this->Form->input('area_sistema', array('div' => array('class' => 'form-group'),
                            'class' => 'form-control',
                            'options' => $area_sistema,
                            'label' => __('area_sistema') ,
                            'empty' => __('label_selecione')));
                        ?>
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