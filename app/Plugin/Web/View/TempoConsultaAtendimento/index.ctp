<style>.red { color:red; } .green{ color: green;}</style>
<div class="row">
    <div class="col-md-12">

        <div data-collapsed="0" class="panel">

           <?= $this->element('titulo-pagina', array('titulo' => __('titulo_consultar', __('Tempo da Consulta do Atendimento')), 'exibirBotaoNovo' => true)); ?>
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
</script>