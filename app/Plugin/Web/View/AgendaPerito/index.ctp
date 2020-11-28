<style>.red { color:red; } .green{ color: green;}</style>

<div id="dialog-periodo" class="displayNone zTop" >
    <p>Caso não preencha nada, o sistema irá procurar pelo periodo do mês corrente.</p>
</div>

<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

           <header class="panel-heading"> 
		    <div class="row">
		        <div class="col-md-10">
		            <div class="panel-title">CONSULTA DE AGENDA DO PERITO</div>
		        </div>
		        <div class = "col-md-2">
		           
		        </div>
		    </div>
		</header>
            <div class="panel-body">
                <?=
                $this->Form->create('AgendaSistema', array(
                    'inputDefaults' => array(
                        'class' => 'form-control',
                        'required' => false
                    ),
                    'id' => 'formularioImprimirAgenda',
                    'url' => array('controller' => 'AgendaPerito', 'action' => 'index')
                ));
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('Unidades de Atendimentos') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group" style="width: 55%;">
                                    <?php echo $this->Form->label(null, 'Unidades de Atendimentos'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->label(null, 'Unidades de Atendimentos Selecionadas'); ?>
                                </div>
                            </div>
                            <?= $this->element('componente_acoes_pickList', array("target" => 'unidades_multi_select', 'formDisabled' => false))?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('Unidade', array('options' => $unidades,
                                        'id' => 'unidades_multi_select',
                                        'multiple' => 'multiple',
                                        'class' => 'sigas_multi_select alturaPickList',
                                        'div' => array('class' => 'form-group multi-select '),
                                        'label' => false));
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Período</legend>
                            
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

                            <div class="row">
                                <div id="helperPeriodo" style="margin: 23px 0 0 5px; float: left; cursor: help;">
                                    <?= $this->Html->image(('question_mark_blue.png')); ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6 form-group">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Perito</legend>

                            <div class="row">
                                <div class="col-md-6 form-group" >
                                    <?= 
                                        $this->Form->input('cpf', array('div' => false, 
                                            'class' => 'form-control cpf inputSemLabel',
                                            'maxlength' => 14,
                                            'label' => 'CPF'));
                                    ?>
                                </div>

                                <div class="col-md-6 form-group" >
                                    <?= 
                                        $this->Form->input('perito_atende', array('div' => false, 
                                            'class' => 'form-control',
                                            'empty' => 'Selecione',
                                            'options' => array('1' => 'Sim', '0' => 'Não'),
                                            'label' => 'Perito atende?'));
                                    ?>
                                </div>

                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= __('Tipologias') ?></legend>
                            <div class="row">
                                <div class="col-md-6 form-group" style="width: 55%;">
                                    <?php echo $this->Form->label(null, 'Tipologias'); ?>
                                </div>
                                 <div class="form-group">
                                    <?php echo $this->Form->label(null, 'Tipologias Selecionadas'); ?>
                                </div>
                            </div>
                            <?= $this->element('componente_acoes_pickList', array("target" => 'sigas_multi_select', 'formDisabled' => false))?>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <?php
                                    echo $this->Form->input('Tipologia', array('options' => $tipologias,
                                        'id' => 'sigas_multi_select',
                                        'multiple' => 'multiple',
                                        'class' => 'sigas_multi_select alturaPickList',
                                        'div' => array('class' => 'form-group multi-select '),
                                        'label' => false));
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <? echo  $this->element('botoes-relatorio'); ?>
                    <div class="row float-right">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="grid">
    </div>
</div>
<script type="text/javascript">
    $('#formularioImprimirAgenda').submit(function (e){
        var isOk = true;
        e.preventDefault();
        console.log('chamou o submit');
        // if($('#tipo_relatorio').val() == ''){
        //     alert('É preciso escolher o tipo de relatório');
        //     isOk = false;
        // }

        // if($('#tipo_agrupamento').is(':visible') && $('#tipo_agrupamento').val() == ""){
        //     alert('É preciso escolher o tipo de agrupamento');
        //     isOk = false;
        // }
        if(isOk){

            var url =  $(this).attr('action')+ '/consultar';
            // var url =  "/spm/fontes/trunk/web/AgendaPerito/consultar";
            $.ajax({
                url: url,
                type: "POST",
                data: $(this).serialize(),
                dataType: "html",
                success: function (response) {
                    $('#grid').html(response);
                }
            });
        }
    });



    $("#btn-imprimir").on('click', function(e){
    	e.preventDefault();

        var urlPdf = "";
    	var url =  $("#formularioImprimirAgenda").attr('action')+ '/impressao?';
        url +=  $("#formularioImprimirAgenda").serialize();
        if(document.location.protocol == ""){
            urlPdf = document.location.protocol + "//" + document.location.hostname + url;
        }else{
            urlPdf = document.location.protocol + "//" + document.location.hostname + ":" + document.location.port + url;
        }
        // console.log(urlPdf);
        // jQuery('body').addClass('loading');
        window.location.replace(urlPdf);
        // jQuery('body').removeClass('loading');
        
    });

    function exibirAjuda(div) {
        $('#'+div).removeClass('displayNone');
        $("div#"+div).dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#"+div).dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: {
                "Ok": {
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#'+div).addClass('displayNone');
                        $(this).dialog("close");
                    }
                }
            }
        });
    }

    $('#helperPeriodo').click(function(){
        exibirAjuda('dialog-periodo');
    });


</script>