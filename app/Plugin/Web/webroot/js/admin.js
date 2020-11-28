/**
 * Esse aquivo possui todos os scripts necessários para o funcionamento do
 * gerenciador. 
 * 
 */
//Variavel para controlar se o formulario deve ou não ser submetido
var submit = false;
function submeterFormulario() {
    submit = true;
    $("#skip_validation").val(true);
    $('.formulario').submit();
}
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}
function removerAlertas() {
    $('.alert').remove();
}


function gerarMensagem(message, priority) {

    $.growl({
        message: '<div style="margin:10px;"><strong>' + message + '</strong></div>',
        click_close: true
    }, {
        element: 'body',
        type: priority,
        delay: 25000,
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


$(document).ready(function () {
    //* FUNCAO PARA MOSTRAR MENSAGENS NA TELA *//
    window.growMessage = function(message, priority) {
        $.growl({
            message: '<div style="margin:10px;"><strong>' + message + '</strong></div>',
            click_close: true
        }, {
            element: 'body',
            type: priority,
            delay: 25000,
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
    //* FIM DA FUNCAO *//

    //* SCRIPT PARA CARREGAR TODOS OS GROWS APARTIR DAS DIV GERADAS PELO CAKE *//
    $(".error-message, div.alert, div.success").each(function () {
        var message = $(this).html();
        var id = $(this).attr('id');
        var priority = '';
        var title = '';
        if (id == 'infoMessage') {
            priority = 'success';
        } else {
            priority = 'danger';
        }
        growMessage(message, priority);
    })
    //* FIM DE SCRIPT DOS GROWS *//

    //* SCRIPT PARA RECARREGAR CONSULTA *//
    function recarregarConsulta() {
        var url = $("#formularioConsulta").attr("action");
        var data_acao = $("#formularioConsulta").data('acao');
        if (data_acao != null) {
            url = url + '/' + data_acao;
        } else {
            url = url + '/consultar';
        }

        $.ajax({
            url: url,
            type: 'get',
            data: $("#formularioConsulta").serialize(),
            success: function (response) {
                $("#grid").html(response);
            },
            error: function (response) {
                location.reload(true);
            }
        });

    }
    //* FIM DE SCRIPT PARA CARREGAR CONSULTA *//

    //* DISPARA FUNCAO DE CONSULTA AJAX QUANDO FORMULARIO É SUBMETIDO *//
    $("#formularioConsulta").submit(function (event) {
        if (!$("#skip_validation").val()) {
            event.preventDefault();
            recarregarConsulta();
        }
    });
    //* FIM *//

    //* DISPARA FUNCAO DE CONSULTA AJAX QUANDO REGISTROS POR PAGINA É ALTERADO *//
    $('body').on('change', '#registros_pagina', function () {
        var valorSelecionado = $("option:selected", this).attr('value');
        $("#limitConsulta").attr('value', valorSelecionado);
        recarregarConsulta();
    });

    /* SCRIPTS REFERENTE A ARVORE SIMPLES. */
    if ($.fn.checktree) {
        $('.tree').checktree();
    }
    /* FIM SCRIPTS REFERENTE A ARVORE SIMPLES. */

    //* SCRIPTS REFERENTE AO MULTSELECT. *//
    if ($.fn.multiSelect) {
        $('.sigas_multi_select').multiSelect();

        $('body').on('click', '.select-all', function () {
            var target = $(this).data('target');
            var disabled = $('#' + target).attr('disabled');
            if (typeof disabled == typeof undefined) {
                $('#' + target).multiSelect('select_all');
            }
            return false;
        });
        $('body').on('click', '.deselect-all', function () {
            var target = $(this).data('target');
            var disabled = $('#' + target).attr('disabled');
            if (typeof disabled == typeof undefined) {
                $('#' + target).multiSelect('deselect_all');
            }
            return false;
        });

    }
    //* FIM SCRIPT MULTSELECT *//

    //* SCRIPT PARA SUBMETER O FORMULARIO NO CHANGE DE UM COMBO *//
    $('body').on('change', '.comboChangeFormSubmmit', function () {
        submeterFormulario();
    });

    $(document).on("drop", 'input', function (e) {
        e.preventDefault();
    });

    $(document).on("change blur keyup", ".upperCaseField", function (e) {
        if ($(this).hasClass("notUpperCase")) {
            return;
        }

        try {
            var start = 0;
            var end = 0;
            if (document.selection) { //IE
                var bm = document.selection.createRange().getBookmark();
                var sel = this.createTextRange();
                sel.moveToBookmark(bm);

                var sleft = this.createTextRange();
                sleft.collapse(true);
                sleft.setEndPoint("EndToStart", sel);
                start = sleft.text.length;
                end = sleft.text.length + sel.text.length;
            } else {
                start = this.selectionStart;
                end = this.selectionEnd;
            }

            var valor = $(this).val();


            var max_length = $(this).attr('maxlength');
            if (typeof max_length != 'undefined') {
                valor = valor.substring(0, max_length);
            }

            $(this).val(valor.toUpperCase());

            //Good Browsers
            if (this.setSelectionRange) {
                if (start == end && start != 0) {
                    this.setSelectionRange(start, end);
                }
            } else if (this.createTextRange) { // IE 
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }

        } catch (err) {
            console.log(err);
        }
    });

    $(document).on({
        ajaxStart: function () {
            $("body").addClass("loading");
        },
        ajaxStop: function () {
            $("body").removeClass("loading");
        }
    });

    $('body').on('click', '.recuperarSenha', function () {
        var cpf = $('.cpf').val();

        var url = $(this).attr('data-url');
        $.ajax({
            url: url,
            dataType: 'json',
            data: {cpf: cpf},
            type: 'get',
            success: function (response) {
                $('.alert').remove();
                if (response.priority == "redirect") {
                    window.location = response.message;
                } else {
                    growMessage(response.message, response.priority);
                }
            }
        });
    });

    if($("div#dialog-alerta-generico p").text() !== ''){
        dialogAlertMsg();
    }
});

function clearSelectedItensMultiSelect(idSelect) {
    $("#" + idSelect + " option").each(function () {
        $(this).removeAttr('selected');
    });
}

function atualizarPickList(idPickList) {
    $('#' + idPickList).multiSelect('refresh');
}
function validarHora(hora) {

    var hrs = (hora.substring(0, 2));
    var min = (hora.substring(3, 5));

    var horaValida = true;
    if ((hrs < 00) || (hrs > 23) || (min < 00) || (min > 59))
    {
        horaValida = false;
    }
    return horaValida;
}


function dialogAlertMsg() {
    $('#blockAll').show();
    $("div#dialog-alerta-generico").dialog().prev().find(".ui-dialog-titlebar-close").hide();
    $("#dialog-alerta-generico").removeClass("displayNone");
    $("#dialog-alerta-generico").dialog({
        resizable: false,
        height: 190,
        width: 490,
        modal: true,
        buttons: [{
            text: 'Ok',
            'class': 'btn fa estiloBotao btn-info float-right zTop',
            click: function () {
                stop();
                $('#blockAll').hide();
                $(this).dialog("close");
            }
        }]
    }).parent().addClass('zTop');
}

function isNumberFormat(value, event, format) {
    //format: [+](int|float)[1->9+]
    //numpad: 96 - 105 => 0 - 9,
    //numpad minus: 109
    //key: 48 - 57 => 0 - 9
    //arrows: 37 - 40 :
    //backspace, tab, delete: 8, 9, 46
    //comma, minus: 188, 189
    var decimais = '';
    var onlyPositive = false;
    if(format.indexOf('+')>= 0){
        onlyPositive = true;
    }

    var isInt = false;
    if(format.indexOf('int') >= 0){
        isInt = true;
    }else{
        decimais = Math.abs(format.replace(/[^0-9]/gi, ''));
    }

    var key = event.which;
    var isOk = false;
    //numbers, sem o shift pressionado
    if(( key >= 48 && key <= 57 && !event.shiftKey ) || (key >= 96 && key <= 105) ){
        if(decimais > 0){
            var commaPos = value.indexOf(',');
            if(commaPos == -1){
                isOk = true;
            }else if(event.target.selectionStart <= commaPos){
                isOk = true;
            }else if(event.target.selectionStart > commaPos){
                if(value.length - commaPos < decimais+1){
                    isOk = true;
                }
            }
        }else{
            isOk = true;
        }
    }else if(key == 109 || key == 189){ //minus
        //condição de positivo, só insere menos se n tiver sinal e se for o primeiro carater
        if(!onlyPositive && value.indexOf('-') == -1 && event.target.selectionStart == 0){
            isOk = true;
        }
        //condição int, só insere vírgula caso n exista vírgula no número
    }else if(!isInt && key == 188 && value.indexOf(',') == -1){
        if(decimais > 0){
            if(value.length - event.target.selectionStart <= decimais+1){
                isOk = true;
            }
        }else{
            isOk = true;
        }
        //jQuery inArray
    }else if ($.inArray(key, [37,38,39,40, 8,9, 46]) >= 0 ){
        isOk = true;
    }
    return isOk;
}