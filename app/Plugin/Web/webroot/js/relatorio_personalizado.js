function generateGrow(message, priority) {

    $.growl({
        message: '<div style="margin:10px;"><strong>' + message + '</strong></div>',
        click_close: true
    }, {
        element: 'body',
        type: priority,
        delay: 5000,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 40,
        spacing: 10,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
    });
}

function validaParte1(){
    var controle = $("#controle").val();
    var ano_exercicio = $("#ano_exercicio").val();
    var isOk = true;

    if(controle == ""){
        isOk = false;
        generateGrow('Selecione um controle', 'danger');
    }else if(ano_exercicio == ""){
        isOk = false;
        generateGrow('Selecione um ano de exercício', 'danger');
    }

    return isOk;
}

function validaParte3(){
    var filtros_exibicao = $("#filtros_exibicao").val();
    isOk = true;
    if(filtros_exibicao == "" || filtros_exibicao == null){
        isOk = false;
        generateGrow('Selecione o(s) campo(s) para exibição', 'danger');
    }
    return isOk;
}

$('#filtros_licenca').multiSelect();
$('#filtros_publicacao').multiSelect();
$('#filtros_unidades').multiSelect();
// $('#filtros_disponiveis').multiSelect();
$('#filtros_exibicao').multiSelect();
$('#filtros_sexo').multiSelect();
$('#filtros_status_agendamento').multiSelect();
$('#filtros_status_atendimento').multiSelect();
$('#filtros_tipo_usuario').multiSelect();
$('#filtros_situacao_atendimento').multiSelect();



$('#filtros_tipologia').multiSelect({
    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",
    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",


    afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
    },
    afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
    }
});

$('#filtros_cargo').multiSelect({
    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",
    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",


    afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
    },
    afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
    }
});

$('#filtros_lotacao').multiSelect({
    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",
    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",


    afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
    },
    afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
    }
});

$('#filtros_orgao').multiSelect({
    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",
    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",


    afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
    },
    afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
    }
});

$('#filtros_funcao').multiSelect({
    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",
    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom: 10px'>",


    afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
    },
    afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
    }
});


function esconderFiltros(){

    $("#tipologia").hide();
    $("#sexo").hide();
    $("#status_agendamento").hide();
    $("#situacao_atendimento").hide();
    $("#status_atendimento").hide();
    $("#orgao_origem").hide();
    $("#lotacao").hide();
    $("#funcao").hide();
    $("#tipo_usuario").hide();
    $("#unidades").hide();
    $("#publicacao").hide();
    $("#cargo").hide();

    $("#filtros_tipologia").multiSelect('deselect_all');
    $("#filtros_sexo").multiSelect('deselect_all');
    $("#filtros_status_agendamento").multiSelect('deselect_all');
    $("#filtros_status_atendimento").multiSelect('deselect_all');
    $("#filtros_orgao").multiSelect('deselect_all');
    $("#filtros_lotacao").multiSelect('deselect_all');
    $("#filtros_funcao").multiSelect('deselect_all');
    $("#filtros_tipo_usuario").multiSelect('deselect_all');
    $("#filtros_unidades").multiSelect('deselect_all');
    $("#filtros_publicacao").multiSelect('deselect_all');
    $("#filtros_situacao_atendimento").multiSelect('deselect_all');
    $("#filtros_cargo").multiSelect('deselect_all');
}


$('#filtros_licenca').change(function(){
    
    esconderFiltros();

    var filtros = [];
    filtros["Tipologia"]                = "tipologia";
    filtros["Sexo"]                     = "sexo";
    filtros["Status Agendamento"]       = "status_agendamento";
    filtros["Status Atendimento"]       = "status_atendimento";
    filtros["Orgão"]                    = "orgao_origem";
    filtros["Cargo"]                    = "cargo";
    filtros["Lotação"]                  = "lotacao";
    filtros["Função"]                   = "funcao";
    filtros["Tipo de Usuário"]          = "tipo_usuario";
    filtros["Unidade de Atendimento"]   = "unidades";
    filtros["Publicação"]               = "publicacao";
    filtros["Situação Atendimento"]     = "situacao_atendimento";
    
    var valorFiltros = $(this).val();
    if (valorFiltros != null){
        if( valorFiltros.length > 0){
            for (i = 0; i < valorFiltros.length; i++) {
                var item = "#" + filtros[valorFiltros[i]];
                $(item).show();
            }
        }
    }


});  

$("#next-1").click(function(){
    if(validaParte1()){
        $("#parte1").hide();
        $("#parte2").show();
        $("#parte3").hide();
    }
});

$("#back-2").click(function(){
    $("#parte1").show();
    $("#parte2").hide();
    $("#parte3").hide();
});

$("#next-2").click(function(){
    $("#parte1").hide();
    $("#parte2").hide();
    $("#parte3").show();
});

$("#back-3").click(function(){
    $("#parte1").hide();
    $("#parte2").show();
    $("#parte3").hide();
});

$('#formularioRelatorio').submit(function (e) {
    // var isOk = true;
    e.preventDefault();

    var url = $(this).attr('action') + '/' + $(this).data('acao');
    $.ajax({
        url: url,
        type: "POST",
        data: $(this).serialize(),
        dataType: "html",
        success: function (response) {
            $('#grid').html(response);
        }
    });

});

$(".btnConsultar").click(function(){
    // if(validaParte3()){
        $(".panel-body").hide();
        $("#resultadoRelatorio").show();
    // }
});

$("#periodo_inicio_opcoes").change(function(){
    if($(this).val() == "Período"){
        $("#periodoInicioDtInicio").show();
        $("#periodoInicioDtFim").show();
    }else{
        $("#periodoInicioDtInicio").hide();
        $("#periodoInicioDtFim").hide();
        $("#RelatorioPeriodoInicDtInic").val("");
        $("#RelatorioPeriodoInicDtFim").val("");
    }
});

$("#numero_laudo").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
         // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

$('#cid').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
});