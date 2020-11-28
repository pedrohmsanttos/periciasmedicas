<div class="modal fade" id="procurarServidorModal" tabindex="-1" role="dialog" aria-labelledby="procurarServidorModalTitle"
     aria-hidden="true" style="z-index: 8999; background: none" >
    <div class="modal-dialog" role="document" style="width: 800px">
        <div class="modal-content">
                <div class="modal-header clearfix">
                    <h5 class="modal-title" id="procurarServidorModalTitle" style="float:left">Procurar Servidor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body clearfix" style="padding-bottom: 0">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-limpar" class="btn btn-danger " style="box-shadow: none !important;">
                        <span class="glyphicon glyphicon-remove"></span>&nbsp;
                        Limpar
                    </button>
                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" id="btn-procurar" class="btn btn-primary">Procurar</button>
                </div>

        </div>
    </div>
</div>


<div class="displayNone">
    <div id="procurar_servidor">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="busca-cpf">CPF</label>
                    <input name="cpf" class="form-control cpf" id="busca-cpf" maxlength="14" type="text">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="busca-matricula">Matrícula</label>
                    <input name="matricula" class="form-control" id="busca-matricula" type="text">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group ">
                    <label for="busca-rg">RG</label>
                    <input name="identidade" class="form-control soNumero" id="busca-rg" maxlength="14" type="text">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group ">
                    <label for="busca-nome">Nome</label>
                    <input name="nome" class="form-control" id="busca-nome" type="text">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group required">
                    <label for="busca-data_nasc">Data de Nascimento</label>
                    <input name="nascimento" class="form-control inputData" id="busca-data_nasc" maxlength="14"
                           type="text">
                </div>
            </div>
        </div>
    </div>


    <div id="item_listagem_servidor">
        <a href="#" class="list-group-item" data-id=""><div class="row">
                <div class="col-sm-2 matricula">00000</div>
                <div class="col-sm-4 nome">Meu nome</div>
                <div class="col-sm-2 nascimento">02/05/1994</div>
                <div class="col-sm-2 cpf">00000</div>
                <div class="col-sm-2 identidade">00000</div>
            </div>
        </a>
    </div>
    <div id="listagem_servidor">
        <div class="row list-group-item active">
            <div class="col-sm-2">Matrícula</div>
            <div class="col-sm-4">Nome</div>
            <div class="col-sm-2">Data de Nascimento</div>
            <div class="col-sm-2">CPF</div>
            <div class="col-sm-2">Identidade</div>
        </div>
        <div class="row">
            <div id="listagem-data" class="list-group" style="
                height: 360px;
                overflow-y: scroll;
                border-radius: 5px;
                border-style: groove;"
            >

            </div>
        </div>
    </div>
</div>


<script>
    $('#btn-procurarServidorModal').on('click', function (e) {
        initModalData();
    });
    $('#procurarServidorModal .modal-footer').on('click', '#btn-limpar', function (e) {
        initModalData();
    });

    $('#procurarServidorModal .modal-footer').on('click', '#btn-procurar', function (e) {
        $('#procurarServidorModal #btn-procurar').hide();
        var query = $('#procurarServidorModal .modal-body input').serialize();
        $('#procurarServidorModal .modal-body').html($('#listagem_servidor').html());
        //var item = $('#item_listagem_servidor').html();
        $.ajax({
            url: '<?php echo $this->Html->url(array("controller" => "Atendimento", "action" => "buscarServidorSim")); ?>?'+query,
            type: "GET",
            dataType: "json",
            beforeSend:function(){
                $('body').addClass('loading');
            },
            success: function (resData) {
                $.each(resData, function(i, d){
                    console.log(d.Pessoas.unpessoa);
                    $('#item_listagem_servidor a').attr('data-id', d.Pessoas.unpessoa);
                    $('#item_listagem_servidor  .matricula').text(d.Servidores.matricula);
                    $('#item_listagem_servidor  .nome').text(d.Pessoas.nome);
                    $('#item_listagem_servidor  .nascimento').text(d.Pessoas.nascimento);
                    $('#item_listagem_servidor  .cpf').text(d.Pessoas.cpf);
                    $('#item_listagem_servidor  .identidade').text(d.Pessoas.identidade);

                    $('#procurarServidorModal .modal-body #listagem-data').append($('#item_listagem_servidor a').clone());
                });
            },
            complete:function(){
                $('body').removeClass('loading');
            }
        });
    });

    $('#procurarServidorModal').on('click','.list-group-item', function (e) {
        e.preventDefault();
        var mat = $(this).find('.matricula').text();
        var nom = $(this).find('.nome').text();
       $('#serv_escolhido').html(mat + "&nbsp; --- &nbsp; " + nom);
       var id = $(this).data('id');
       $.ajax({
           url: '<?php echo $this->Html->url(array("controller" => "Atendimento", "action" => "ajaxListaHistorico")); ?>/'+id,
           type: "GET",
           dataType: "html",
           beforeSend:function(){
               $('body').addClass('loading');
           },
           success: function (resData) {
               $('#tableHistoricoMedico tbody').html(resData);
               $('#procurarServidorModal').modal('toggle');
           },
           complete:function(){
               $('body').removeClass('loading');
           }
       });

    });

    function initModalData(){
        $('#procurarServidorModal .modal-body').html($('#procurar_servidor').html());
        $('#procurarServidorModal #btn-procurar').show();
        $(".modal-body .inputData").mask("99/99/9999");
        if ($.fn.datepicker) {
            $('.modal-body .inputData').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                buttonImageOnly: true,
                showButtonPanel: true,
                buttonImage: "../img/datepicker.gif",
                showOn: 'both'
            });
        }
        function soNumero(element) {
            $(element).val($(element).val().replace(/[^0-9]/gi, ''));
        }
        $(".modal-body .soNumero").on("keyup", function (event) {
            soNumero($(this));
        });
        $(".modal-body .soNumero").on("change", function (event) {
            soNumero($(this));
        });
        $(".modal-body .soNumero").on("focus", function (event) {
            soNumero($(this));
        });
        $(".modal-body .soNumero").on("hover", function () {
            soNumero($(this));
        });
        $(".modal-body .soNumero").on("mouseout", function () {
            soNumero($(this));
        });
        $(".modal-body .cpf").mask("999.999.999-99");

        $('#procurarServidorModal input').val('');
    }
</script>
