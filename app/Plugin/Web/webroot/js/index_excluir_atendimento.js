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

$(document).ready(function () {
    function escapeRegExp(string) {
        return string.replace(/([._\-*+?^=!:${}()|\[\]\/\\])/g, "");
    }

    $("#cpfSolicitante").mask("999.999.999-99");

    $("#cpfSolicitante").on("keyup", function (e) {
        $('#nomeSolicitante').val('');
        $('#idSolicitante').val('');
        var url = $('#baseUrlDefault').data('url') + 'getUsuarioCpf/';
        var cpf = escapeRegExp($(this).val());
        if (cpf.length === 11) {
            $.ajax({
                url: url,
                type: "post",
                data: {cpf: cpf},
                dataType: "html",
                success: function (response) {
                    var responseJson = $.parseJSON(response);
                    if (responseJson.status === "danger") {
                        $('.alert').remove();
                        generateGrow(responseJson.msg, responseJson.status);
                    } else {
                        $('#idSolicitante').val(responseJson.id);
                        $('#nomeSolicitante').val(responseJson.nome);
                    }
                }
            });
        }
    });


    $('body').on('click', '.excluirAtendimento', function (){
        var id = $(this).data("id");
        var url = $(this).data('url');
        var acao = $(this).data('acao');

        $("#excluirId").text(id);
        $('#dialog-exclusao').removeClass('displayNone');
        $("#dialog-exclusao").dialog({
            resizable: false,
            height: 390,
            width: 490,
            modal: true,
            buttons: {
                "Cancelar": {
                    text: 'Cancelar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('.inputsExclusao').val('');
                        $('#dialog-exclusao').dialog("close");
                    }
                },
                "Ok": {
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        var cpf = escapeRegExp($('#cpfSolicitante').val());
                        var val = $.trim($('#motivoExclusao').val());
                        var idSolicitante = $('#idSolicitante').val();
                        console.log(cpf, val);
                        var hasError =  false;
                        if(cpf == ""){
                            $('#cpfSolicitante').focus();
                            generateGrow("É preciso selecionar o solicitante", "danger");
                            hasError = true;
                        }
                        if(val == "" ) {
                            $('#motivoExclusao').focus();
                            generateGrow("É preciso selecionar informar o motivo", "danger");
                            hasError = true;
                        }

                        if(!hasError){
                            $('#dialog-exclusao').dialog("close");
                            $("body").addClass("loading");
                            $.ajax({
                                url: url,
                                type: "POST",
                                data:{motivo:val, idSolicitante:idSolicitante},
                                dataType: "html",
                                success: function (response) {
                                    $("body").removeClass("loading");
                                    if(response == 1){
                                        generateGrow("Atendimento excluído com sucesso.", "success");
                                        $('.inputsExclusao').val('');
                                        $("#registros_pagina").change();
                                    }else{
                                        generateGrow("Não foi possível excluir o atendimento.", "danger");
                                    }
                                },
                                error:function () {
                                    generateGrow("Não foi possível excluir o atendimento.", "danger");
                                }
                            });
                        }
                    }
                }
            }
        });
        return;
    });
});