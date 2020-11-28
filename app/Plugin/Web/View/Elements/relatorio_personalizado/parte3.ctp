<fieldset class="scheduler-border">
    <legend class="scheduler-border">Bloco de Exibição(Agrupamento)</legend>

    <div class="row">
    	<div class="col-md-3 form-group">
    		<?=
            $this->Form->input('disponiveis', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_disponiveis',
            'class' => 'form-control',
            'options' => $disponiveis,
            'empty' => __('label_selecione'),
            // 'multiple' => 'multiple',
            'label' => __('Agrupar por'),
            'required'=>false));
            ?>
    	</div>	

        <div class="col-md-9 form-group">
            <?=
                $this->Form->input('titulo_relatorio', array('div' => array('class' => 'form-group'),
                    'id' => 'titulo_relatorio',
                    'class' => 'form-control',
                    'label' => __('Título do Relatório'),
                    'required'=>false,
                    'maxlength' => 255
                ));
            ?>
    	</div>	
    </div>
    <div class="row">
    	<div class="col-md-6 form-group">
    		<?=
            $this->Form->input('campos_exibicao', array('div' => array('class' => 'form-group'),
            'id' => 'filtros_exibicao',
            'class' => 'form-control',
            'options' => $campos_exibicao,
            'multiple' => 'multiple',
            'label' => __('Campos para Exibição *'),
            'required'=>false));
            ?>
    	</div>		
    </div>
    <!-- <div class="row" id="ordem_campos">
        <div class="row">
            <div class="col-md-4 form-group">
                <p>NOME DA UNIDADE</p>
            </div> 
            <div class="col-md-4 form-group">
                <?=
                $this->Form->input('numero_laudo_opcao', array('div' => array('class' => 'form-group'),
                'id' => 'numero_laudo_opcao',
                'class' => 'form-control',
                'options' => $ordenacao,
                'label' => __('N° Laudo (Opção)*'),
                'empty' => __('label_selecione'),
                'required'=>false));
                ?>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <p>NOME DA UNIDADE</p>
            </div> 
            <div class="col-md-4 form-group">
                <?=
                $this->Form->input('numero_laudo_opcao', array('div' => array('class' => 'form-group'),
                'id' => 'numero_laudo_opcao',
                'class' => 'form-control',
                'options' => $ordenacao,
                'label' => __('N° Laudo (Opção)*'),
                'empty' => __('label_selecione'),
                'required'=>false));
                ?>
            </div> 
        </div>
    </div> -->

     <div class="row float-right btn-edit">
        <div id="loading-consultar" class="displayNone" style="
            float:left;
            background: rgba( 255, 255, 255, .8 ) url('img/l6BEEsW.gif') 50% 50% no-repeat;
            width: 30px;
            height: 30px;
            margin-right: 5px" >
        </div>
        <button class="btn fa fa-arrow-left estiloBotao btn-info" type="button" id="back-3"> Voltar</button>
        <?php

        echo $this->Form->button(__('bt_consultar'), array(
            'class' => 'btn fa fa-search estiloBotao btn-info btnConsultar',
            'update' => '#main-content', 'evalScripts' => true
        ));

        echo $this->Form->button ( ' Imprimir', array (
            'class' => 'btn fa fa-print estiloBotao btn-primary',
            'id'=> 'btn-imprimir-personalizado','evalScripts' => true,
            'type' =>'button',
            'data-acao' => 'impressao'
        ) );

        echo $this->Form->button ( ' Exportar Excel', array (
            'class' => 'btn fa fa-print estiloBotao btn-primary',
            'id'=> 'btn-imprimir-personalizado-excel','evalScripts' => true,
            'type' =>'button',
            'data-acao' => 'impressaoExcel'
        ) );
        
        $acaoConsulta = "personalizado";
        if(isset($acaoBotaoLimpar)):
            $acaoConsulta = $acaoBotaoLimpar;
        endif;

        $urlConsulta = Router::url(array('controller' => "$controller", 'action' => $acaoConsulta));
    
        echo $this->Form->button(__('bt_limpar'), array(
            'class' => 'btn fa fa-eraser estiloBotao btn-danger',
            'type' => 'button',
            'onclick' => "location.href = '$urlConsulta'"
        ));
        ?>
    </div>
</fieldset>

<script type="text/javascript">
    $("#btn-imprimir-personalizado").click(function(){
        // var urlPdf = "";
        // var url =  $("#formularioRelatorio").attr('action')+ '/impressaoRelatorioPersonalizado?';
        // url +=  $("#formularioRelatorio").serialize();
        // if(document.location.protocol == ""){
        //     urlPdf = document.location.protocol + "//" + document.location.hostname + url;
        // }else{
        //     urlPdf = document.location.protocol + "//" + document.location.hostname + ":" + document.location.port + url;
        // }
        // window.location.replace(urlPdf);

        var url =  $("#formularioRelatorio").attr('action')+ '/impressaoRelatorioPersonalizado?';
        $.ajax({
          type: "POST",
          url: url,
          data: $("#formularioRelatorio").serialize(),
            success: function (response) {        
                var obj = JSON.parse(response);
                console.log(obj.url);
                window.open(obj.url,'_blank');
            }
        });
    });

    $("#btn-imprimir-personalizado-excel").click(function(){
        // var urlPdf = "";
        // var url =  $("#formularioRelatorio").attr('action')+ '/impressaoRelatorioPersonalizado?';
        // url +=  $("#formularioRelatorio").serialize();
        // if(document.location.protocol == ""){
        //     urlPdf = document.location.protocol + "//" + document.location.hostname + url;
        // }else{
        //     urlPdf = document.location.protocol + "//" + document.location.hostname + ":" + document.location.port + url;
        // }
        // window.location.replace(urlPdf);

        var url =  $("#formularioRelatorio").attr('action')+ '/impressaoRelatorioPersonalizadoExcel?';
        $.ajax({
          type: "POST",
          url: url,
          data: $("#formularioRelatorio").serialize(),
            success: function (response) {        
                var obj = JSON.parse(response);
                console.log(obj.url);
                window.open(obj.url,'_blank');
            }
        });
    });
</script>