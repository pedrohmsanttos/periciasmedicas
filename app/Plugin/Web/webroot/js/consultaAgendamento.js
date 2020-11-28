$(document).ready(function () {
    $("#formularioConsultaAgendamentoInterno").submit(function (event) {
        event.preventDefault();
        var dataInicial = $("#dataInicial").val();
        var dataFinal = $("#dataFinal").val();
        if (dataInicial != "" && dataFinal != "") {
            dataInicial = inverterData($("#dataInicial").val());
            dataFinal = inverterData($("#dataFinal").val());
            if (dataInicial > dataFinal) {
                removerAlertas();
                growMessage('A data inicial do Período não pode ser maior que a data final.', 'danger');
            } else {
                recarregarConsulta();
            }
        } else {
            recarregarConsulta();
        }
    });

    //* SCRIPT PARA RECARREGAR CONSULTA *//
    function recarregarConsulta() {
        var url = $("#formularioConsultaAgendamentoInterno").attr("action");
        var data_acao = $("#formularioConsultaAgendamentoInterno").data('acao');
        if (data_acao != null) {
            url = url + '/' + data_acao;
        } else {
            url = url + '/consultar';
        }

        $.ajax({
            url: url,
            type: 'get',
            data: $("#formularioConsultaAgendamentoInterno").serialize(),
            success: function (response) {
                $("#grid").html(response);
            },
            error: function (response) {
            }
        });

    }
//* FIM DE SCRIPT PARA CARREGAR CONSULTA *//

//* DISPARA FUNCAO DE CONSULTA AJAX QUANDO REGISTROS POR PAGINA É ALTERADO *//
    $('body').on('change', '#registros_pagina_agendamento', function () {
        var valorSelecionado = $("option:selected", this).attr('value');
        $("#limitConsulta").attr('value', valorSelecionado);
        recarregarConsulta();
    });

    function inverterData(data) {
        var partesDatas = data.split("/");
        if (partesDatas.length == 3) {
            return partesDatas[2] + "/" + partesDatas[1] + "/" + partesDatas[0];
        }
    }

    function growMessage(message, priority) {
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
});