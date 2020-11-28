<style>.red { color:red; } .green{ color: green;}</style>
<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

            <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('Agenda do Sistema')), 'exibirBotaoNovo' => true, 'feminino' => true)); ?>

            <div class="panel-body">
                <?=
                $this->Form->create('AgendaSistema', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioConsulta',
                    'url' => array('controller' => 'AgendaSistema', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Início Validade</legend>

                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('ini_data_inicial', array('maxlength' => 150, 'label' => __('processo_label_de'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                                    ?>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('ini_data_final', array('maxlength' => 150, 'label' => __('processo_label_ate'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData inputSemLabel'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Fim Validade</legend>
                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('fim_data_inicial', array('maxlength' => 150, 'label' => __('processo_label_de'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData'));
                                    ?>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <?=
                                    $this->Form->input('fim_data_final', array('maxlength' => 150, 'label' => __('processo_label_ate'), 'onkeyup' => 'JEffects.masks.data(this),formatoData(this)',
                                        'onblur' => 'VerificaData(this,this.value)',
                                        'onmouseout' => 'VerificaData(this,this.value)', 'class' => 'form-control inputData inputSemLabel'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <?= $this->Form->input('descricao', array('label' => 'Descrição')); ?>
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
<style>
    .bgDanger { background-color: #F33; }
    .bgOk { background-color: #3B3; }
</style>
<div class="modal fade modalMsg" tabindex="-1" role="dialog" style="background: none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bgDanger" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="msgTitle">Aviso</h4>
            </div>
            <div class="modal-body">
                <p id="msgTxt">Tem certeza que gostaria de excluir a agenda ?</p>
            </div>
            <div class="modal-footer">
                <button id="btn-c-excluir"
                        class="btn fa fa-trash-o estiloBotao btn-danger" type="button"
                        data-dismiss="modal"
                        data-url=""
                > Excluir</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Modal de alerta -->
<div class="modal fade modalMsgAlert" role="dialog" style="background: none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header " >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="msgTitle">Aviso</h4>
            </div>
            <div class="modal-body">
                <p id="msgTxt" style="text-align: center;font-size: 16px;"><?=$this->Session->flash('msgAlert')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<script>
    jQuery(function ($) {
        if($(".modalMsgAlert #msgTxt").text() !== ''){
            $('.modalMsgAlert').modal();
        }
    });
</script>