<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar',__('feriado_label_feriado')), 'exibirBotaoNovo' => true)); ?>

            <div class="panel-body">
                <?= $this->Form->create('Feriado', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required'=> false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'Feriado', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('nome', array('label' => __('feriado_label_nome'))); ?>
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
                                'label' => 'Data', 
                                'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                'onblur' => 'VerificaData(this,this.value)', 'id' => 'dataInicial',
                                'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                        ?>
                    </div>
                </div>
                <?= $this->element('botoes-default-consulta'); ?>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>