jQuery(function ($) {
    $(".avancarData").on("click", function (e) {
        var date = $('#dataAgendamento').datepicker('getDate');
        date.setTime(date.getTime() + (1000 * 60 * 60 * 24))
        $('#dataAgendamento').datepicker("setDate", date);
    });

    $(".voltarData").on("click", function (e) {
        var date = $('#dataAgendamento').datepicker('getDate');
        date.setTime(date.getTime() - (1000 * 60 * 60 * 24))
        $('#dataAgendamento').datepicker("setDate", date);
    });

    function tratarConfirmacaoDesconfirmacaoAgendamento(link) {
        console.log($('#check_prioritario').attr('checked'));
        var url = link.data('url');
        $.ajax({
            url: url,
            type: "get",
            dataType: "html",
            data: {prioritario: $('#check_prioritario').is(':checked')+0},
            success: function (response) {
                var responseJson = $.parseJSON(response);
                $('.alert').remove();
                growMessage(responseJson.msg, responseJson.status);
                recarregarConsulta();
            }
        });
    }

    $('body').on('click', '.beforeAcao', function () {
        var link = $(this);
        var hora = $(this).closest('tr').find(".colunaHora").html();
        var data = $("#dataAgendamento").val();
        var cpf = $(this).closest('tr').find(".colunaCpf").html();
        var nome = $(this).closest('tr').find(".colunaNome").html();
        var acao = $(this).data('acao');
        $("#parametroNomeDialog").html(nome);
        $("#parametroCpf").html(cpf);
        $("#parametroData").html(data);
        $("#parametroHora").html(hora);
        $("#parametroAcao").html(acao);
        var height = 185;
        $('#check_prioritario').attr('checked', false);
        if(acao == 'confirmar'){
            height = 205;
            $('#confirmacao-prioritario').removeClass('displayNone');
        }else{
            $('#confirmacao-prioritario').addClass('displayNone');
        }

        $("div#dialog-confirmacao_acao").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-confirmacao_acao").removeClass("displayNone");
        $("#dialog-confirmacao_acao").dialog({
            resizable: false,
            height: height,
            width: 490,
            modal: true,
            buttons: {
                "Não": {
                    text: 'Não',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-confirmacao_acao').dialog("close");
                    }
                },
                "Sim": {
                    text: 'Sim',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        if (acao == "confirmar" || acao == "desconfirmar") {
                            tratarConfirmacaoDesconfirmacaoAgendamento(link);
                        } else if (acao == "reagendar") {
                            tratarReagendamento(link);
                        } else {
                            tratarExclusao(link);
                        }
                        $('#dialog-confirmacao_acao').dialog("close");
                    }
                }
            }
        });
    });

    /**
     * Comment
     */
    function tratarReagendamento(link) {
        var url = link.data('url');
        window.location.href = url;
    }

    /**
     * Comment
     */
    function tratarExclusao(link) {
        var url = link.data('url');
        window.location.href = url;
    }

    $("#formularioConsultaAgendamento").submit(function (event) {
        $('#loading-consultar').removeClass('displayNone');
        window.consultarClick = true;

        if ($('#dataAgendamento').val()) {
            event.preventDefault();
            removerAlertas();
            var horaInicial = $("#AgendamentoHoraInicial").val();
            var horaFinal = $("#AgendamentoHoraFinal").val();

            var realizarConsulta = true;

            if (horaInicial !== "" && !validarHora(horaInicial)) {
                realizarConsulta = false;
                growMessage('A hora inicial não é uma hora válida.', 'danger');
            }
            if (horaFinal !== "" && !validarHora(horaFinal)) {
                realizarConsulta = false;
                growMessage('A hora final não é uma hora válida.', 'danger');
            }
            if (realizarConsulta) {
                recarregarConsulta();
            }

        } else {
            growMessage('Escolha um dia para listar os agendamentos', 'danger');
            return false;
        }
    });

    //* SCRIPT PARA RECARREGAR CONSULTA *//
    function recarregarConsulta() {
        var url = $("#formularioConsultaAgendamento").attr("action");
        url = url + '/consultar';
        var dataAgendamento = $('#dataAgendamento').val();
        var data = {data:{}};
        data.data.Agendamento = {
            'Tipologia' :$('#AgendamentoTipologia').val(),
            'cpf' :$('#AgendamentoCpf').val(),
            'hora_inicial' : $('#AgendamentoHoraInicial').val(),
            'hora_final' : $('#AgendamentoHoraFinal').val(),
            'data' : $('#dataAgendamento').val(),
            'limitConsulta' : $('#limitConsulta').val(),
            'Unidade' : $('#AgendamentoUnidade').val()
        };

        if (dataAgendamento) {
            if(window.consultarClick){
                $('#loading-consultar').removeClass('displayNone');
                window.consultarClick = false;
            }
            $.ajax({
                url: url,
                type: 'get',
                global: false,
                data: data,
                success: function (response) {
                    $("#grid").html(response);
                    $('#loading-consultar').addClass('displayNone');
                },
                error: function (response) {
                    $('#oading-consultar').addClass('displayNone');
//                location.reload(true);
                }
            });
        } else {
            $("#grid").html('');
        }
    }

    //* FUNCAO PARA MOSTRAR MENSAGENS NA TELA *//
    window.growMessage = function (message, priority) {
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
    
    $('body').on('change', '#registros_pagina_sala', function () {
        var valorSelecionado = $("option:selected", this).attr('value');
        $("#limitConsulta").attr('value', valorSelecionado);
        listaSalas();
    });

    $('body').on('click', '#gerenciarSalas', function () {
        //Carregando lista de Salas;
        listaSalas();
        $(".ui-dialog-titlebar-close").hide();
        $("#dialog-gerenciamento-salas").css("background-color", "white");
        $("#dialog-gerenciamento-salas").removeClass("displayNone");
        $("#dialog-gerenciamento-salas").dialog({
            resizable: false,
            width: 1200,
            height: 600,
            dialogClass: "no-close",
            modal: true,
            position: 'center',
            buttons: {
                "Fechar": {
                    text: 'Fechar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-gerenciamento-salas').dialog("close");
                    }
                }
            },
            open: function () {
                var unidadeAtendimento = $('#selectUnidadeAtendimento').val();
                if (unidadeAtendimento) {
                    $('#inputNomePerito').attr('disabled', false);
                    $('#inputCpf').attr('disabled', false);
                    $('#inputNumeroRegistro').attr('disabled', false);
                    $('#selectTipologiaSala').attr('disabled', true);
                    atualizarPickList('selectTipologiaSala');
                } else {
                    disabledAfterUnidadeSelect(true);
                }
            },
            close: function () {
                limparCamposSalas();
            },
            draggable: false
        });
    });

    function disabledAfterUnidadeSelect(disabled) {
        $('#inputNomePerito').attr('disabled', disabled);
        $('#inputCpf').attr('disabled', disabled);
        $('#inputNumeroRegistro').attr('disabled', disabled);
        $('#selectTipologiaSala').attr('disabled', disabled);
    }

    $('body').on('change', '#selectUnidadeAtendimento', function () {
        if ($('#selectUnidadeAtendimento').val()) {
            listaSalas();

            $('#selectTipologiaSala').find('option').remove();

            $('#inputNomePerito').val('');
            $('#inputCpf').val('');
            $('#inputNumeroRegistro').val('');
            $('#selectTipologiaSala').val('');
            $('#hidPeritoId').val('');

            $('#inputNomePerito').attr('disabled', false);
            $('#inputCpf').attr('disabled', false);
            $('#inputNumeroRegistro').attr('disabled', false);
            $('#selectTipologiaSala').attr('disabled', true);
        } else {
            $('#selectTipologiaSala').find('option').remove();

            $('#inputNomePerito').val('');
            $('#inputCpf').val('');
            $('#inputNumeroRegistro').val('');
            $('#selectTipologiaSala').val('');
            $('#hidPeritoId').val('');
            disabledAfterUnidadeSelect(true);
        }
    });

    /**
     * Validação do gerenciar Sala
     */
    function validateGerenciarSala(parameters) {
        removerAlertas();
        var validate = true;
        if ($('#selectUnidadeAtendimento').val() === "") {
            validate = false;
            growMessage('Selecione uma Unidade de Atendimento.', 'danger');
        }

        if ($('#inputSala').val() === "") {
            validate = false;
            growMessage('Digite a sala que o perito vai está alocado.', 'danger');
        }

        if ($('#hidPeritoId').val() === "") {
            validate = false;
            growMessage('Informe qual perito se refere este cadastro de sala.', 'danger');
        }

        if ($('#selectTipologiaSala option:selected').length === 0) {
            validate = false;
            growMessage('Selecione uma tipologia.', 'danger');
        }

        return validate;
    }

    /**
     * Libera sala e coloca o novo perito dentro da sala.
     */
    function realocaSala() {
        $("#dialog-alocamento_perito_sala").removeClass("displayNone");
        $("#dialog-alocamento_perito_sala").dialog({
            resizable: false,
            height: 190,
            width: 490,
            dialogClass: "no-close",
            modal: true,
            buttons: {
                "Não": {
                    text: 'Não',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-alocamento_perito_sala').dialog("close");
                    }
                },
                "Sim": {
                    text: 'Sim',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        var url = $('#adicionarSala').data('url') + '/realocarEmSala';
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {
                                sala: $('#inputSala').val(),
                                usuario_perito_id: $('#hidPeritoId').val(),
                                unidade_atendimento_id: $('#selectUnidadeAtendimento').val(),
                                Tipologia: $('#selectTipologiaSala').val()
                            },
                            success: function (response) {
                                limparCamposSalas();
                                listaSalas();
                            },
                            error: function (response) {
                                //                location.reload(true);
                            }
                        });
                        $('#dialog-alocamento_perito_sala').dialog("close");
                    }
                }
            }
        });
    }

    /**
     * Libera sala e coloca o mesmo perito dentro da mesma sala.
     */
    function realocaSalaPerito() {
        $("#dialog-alocamento_mesmo_perito_mesma_sala").removeClass("displayNone");
        $("#dialog-alocamento_mesmo_perito_mesma_sala").dialog({
            resizable: false,
            height: 190,
            width: 490,
            dialogClass: "no-close",
            modal: true,
            buttons: {
                "Não": {
                    text: 'Não',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-alocamento_mesmo_perito_mesma_sala').dialog("close");
                    }
                },
                "Sim": {
                    text: 'Sim',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        var url = $('#adicionarSala').data('url') + '/realocarEmSala';
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {
                                sala: $('#inputSala').val(),
                                usuario_perito_id: $('#hidPeritoId').val(),
                                unidade_atendimento_id: $('#selectUnidadeAtendimento').val(),
                                Tipologia: $('#selectTipologiaSala').val()
                            },
                            success: function (response) {
                                limparCamposSalas();
                                listaSalas();
                            },
                            error: function (response) {
                                //                location.reload(true);
                            }
                        });
                        $('#dialog-alocamento_mesmo_perito_mesma_sala').dialog("close");
                    }
                }
            }
        });
    }

    /**
     * Realoca o perito a uma nova sala.
     */
    function realocaPerito() {
        $("#dialog-alocamento_perito_nova_sala").removeClass("displayNone");
        $("#dialog-alocamento_perito_nova_sala").dialog({
            resizable: false,
            height: 190,
            width: 490,
            dialogClass: "no-close",
            modal: true,
            buttons: {
                "Não": {
                    text: 'Não',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-alocamento_perito_nova_sala').dialog("close");
                    }
                },
                "Sim": {
                    text: 'Sim',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        var url = $('#adicionarSala').data('url') + '/realocarPeritoEmSala';
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {
                                sala: $('#inputSala').val(),
                                usuario_perito_id: $('#hidPeritoId').val(),
                                unidade_atendimento_id: $('#selectUnidadeAtendimento').val(),
                                Tipologia: $('#selectTipologiaSala').val()
                            },
                            success: function (response) {
                                limparCamposSalas();
                                listaSalas();
                            },
                            error: function (response) {
                                //                location.reload(true);
                            }
                        });
                        $('#dialog-alocamento_perito_nova_sala').dialog("close");
                    }
                }
            }
        });
    }

    /**
     * Verifica a disponibilidade da sala
     */
    function verificaDisponibilidadeSala() {
        var retorno = false;
        var url = $('#adicionarSala').data('url') + '/validarDisponibilidadeSala';

        var responseAjax = $.ajax({
            url: url,
            type: 'post',
            async: false,
            data: {
                sala: $('#inputSala').val(),
            },
            success: function (response) {
                var retorno = false;
                var responseJson = $.parseJSON(response);
                if (responseJson) {
                    retorno = true;
                } else {
                    retorno = false;
                }
                return retorno;
            }
        });

        retorno = $.parseJSON(responseAjax.responseText);

        return retorno;
    }

    /**
     * Verifica a realocação do perito
     */
    function verificaRealocacaoPerito() {
        var retorno = false;
        var url = $('#adicionarSala').data('url') + '/validarDisponibilidadePerito';

        var responseAjax = $.ajax({
            url: url,
            type: 'post',
            async: false,
            data: {
                usuario_perito_id: $('#hidPeritoId').val(),
            },
            success: function (response) {
                var retorno = false;
                var responseJson = $.parseJSON(response);

                if (responseJson) {
                    retorno = true;
                } else {
                    retorno = false;
                }
                return retorno;
            }
        });

        retorno = $.parseJSON(responseAjax.responseText);

        return retorno;
    }

    $('body').on('click', '#adicionarSala', function () {
        if (validateGerenciarSala()) {
            var validaSala = true;
            var ValidaPerito = true;
            var validaSalaPerito = true;
            var disponibilidadeSala = verificaDisponibilidadeSala();
            var disponibilidadePerito = verificaRealocacaoPerito();

            if (disponibilidadeSala && disponibilidadePerito) {
                realocaSalaPerito();
                validaSalaPerito = false;
            }
            if (disponibilidadeSala && validaSalaPerito) {
                realocaSala();
                validaSala = false;
            }

            if (disponibilidadePerito && validaSala && validaSalaPerito) {
                realocaPerito();
                ValidaPerito = false;
            }

            if (validaSala && ValidaPerito && validaSalaPerito) {
                adicionarSala();
            }

        }
    });

    /**
     * Adicionar Sala
     */
    function adicionarSala() {
        var url = $('#adicionarSala').data('url') + '/adicionar';
        $.ajax({
            url: url,
            type: 'post',
            data: {
                sala: $('#inputSala').val(),
                usuario_perito_id: $('#hidPeritoId').val(),
                unidade_atendimento_id: $('#selectUnidadeAtendimento').val(),
                Tipologia: $('#selectTipologiaSala').val()
            },
            success: function (response) {
                var responseJson = $.parseJSON(response);
                if (responseJson) {
                    growMessage('Sala cadastrada com sucesso!', 'success');
                    limparCamposSalas();
                    listaSalas();
                } else {
                    growMessage('Problemas ao adicionar sala!', 'danger');
                }
            },
            error: function (response) {
//                location.reload(true);
            }
        });
    }


    function limparCamposSalas() {
        $('#selectTipologiaSala').find('option').remove();

        $('#inputSala').val('');
        $('#inputNomePerito').val('');
        $('#inputCpf').val('');
        $('#inputNumeroRegistro').val('');
        if (!$('#selectUnidadeAtendimento').val()) {
            $('#selectUnidadeAtendimento').val('');

            $('#inputNomePerito').attr('disabled', true);
            $('#inputCpf').attr('disabled', true);
            $('#inputNumeroRegistro').attr('disabled', true);
            $('#selectTipologiaSala').attr('disabled', true);
        } else {
            $('#inputNomePerito').attr('disabled', false);
            $('#inputCpf').attr('disabled', false);
            $('#inputNumeroRegistro').attr('disabled', false);
            $('#selectTipologiaSala').attr('disabled', true);
        }
        $('#selectTipologiaSala').val('');
        atualizarPickList('selectTipologiaSala');
        $('#hidPeritoId').val('');

    }

    /**
     * Função que monta o grid de Salas
     */
    function listaSalas() {
        var url = $("#gerenciarSalas").data("gerenciar-sala");
        $.ajax({
            url: url,
            global: false,
            type: 'get',
            data: {
                unidade: $('#selectUnidadeAtendimento').val(),
                limitConsulta: $('#registros_pagina_sala').val()
            },
            success: function (response) {
                $("#gridGerenciarSalas").html(response);
            },
            error: function (response) {
                console.log(response);
            }
        });
    }

    function removerAlertas() {
        $('.alert').remove();
    }

    /**
     * Função para o AutoComplete de acordo com o CPF do servidor
     */
    $("#inputNomePerito").autocomplete({
        appendTo: '#dialog-gerenciamento-salas',
        source: function (request, response) {
            $.getJSON($("#inputNomePerito").data("url"), {unidade: $('#selectUnidadeAtendimento').val(), term: $("#inputNomePerito").val()},
            response);
        },
        minLength: 4,
        response: function (event, ui) {
            $('#hidPeritoId').val('');
        },
        select: function (a, b) {
            $('#inputCpf').val(b.item.cpf);
            $('#cpfServidor').val(b.item.cpf);
            $('#inputNumeroRegistro').val(b.item.numeroRegistro);
            $('#hidPeritoId').val(b.item.id);
            $('#selectTipologiaSala').attr('disabled', false);
            aplicarMascaraCpf();

            carregaTipologias();
        }
    });

    /**
     * Função para o AutoComplete de acordo com o CPF do servidor
     */
    $("#inputCpf").autocomplete({
        appendTo: '#dialog-gerenciamento-salas',
        source: function (request, response) {
            $.getJSON($("#inputCpf").data("url"), {unidade: $('#selectUnidadeAtendimento').val(), term: $("#inputCpf").val()},
            response);
        },
        response: function (event, ui) {
            $('#hidPeritoId').val('');
            $('#selectTipologiaSala').attr('disabled', true);
        },
        select: function (a, b) {
            $('#inputNomePerito').val(b.item.nome);
            $('#inputNumeroRegistro').val(b.item.numeroRegistro);
            $('#hidPeritoId').val(b.item.id);
            $('#selectTipologiaSala').attr('disabled', false);
            aplicarMascaraCpf();

            carregaTipologias();
        }
    });

    /**
     * Função para o AutoComplete de acordo com o CPF do servidor
     */
    $("#inputNumeroRegistro").autocomplete({
        appendTo: '#dialog-gerenciamento-salas',
        source: function (request, response) {
            $.getJSON($("#inputNumeroRegistro").data("url"), {unidade: $('#selectUnidadeAtendimento').val(), term: $("#inputNumeroRegistro").val()},
            response);
        },
        minLength: 3,
        response: function (event, ui) {
            $('#hidPeritoId').val('');
            $('#selectTipologiaSala').attr('disabled', true);
        },
        select: function (a, b) {
            $('#inputNomePerito').val(b.item.nome);
            $('#inputCpf').val(b.item.cpf);
            $('#hidPeritoId').val(b.item.id);
            $('#selectTipologiaSala').attr('disabled', false);
            aplicarMascaraCpf();

            carregaTipologias();
        }
    });


    function poll() {
        recarregarConsulta();
    }

    setInterval(function () {
        if ($('#selectUnidadeAtendimento').val()) {
            listaSalas();
        }

        $.each($('.solicitarPresenca'), function (key, event) {
            var servidorNome = $.trim($(this).find('.colunaNome').html());
            var servidorCpf = $(this).find('.colunaCpf').html();
            var sala = $.trim($(this).find('.colunaSala').html());
            var idAgendamento = $.trim($(this).find('.idAgendamento').val());
            verificaChamada(sala, servidorCpf, servidorNome, idAgendamento);
        });

    }, 10000);

    setInterval(function () {
        poll();
    }, 10000);

    /**
     * Função que carrega as tipologias
     */
    function carregaTipologias() {
        var perito = $('#hidPeritoId').val();
        var url = $('#selectTipologiaSala').data('url');
        if (perito !== "" && perito) {
            $.ajax({
                url: url,
                appendTo: '#dialog-gerenciamento-salas',
                type: "POST",
                data: {
                    perito: perito
                },
                success: function (response) {
                    var responseJson = $.parseJSON(response);
                    $('#selectTipologiaSala').find('option').remove();
                    $.each(responseJson, function (value, text) {
                        $('#selectTipologiaSala').append($('<option>', {
                            value: value,
                            text: text
                        }));
                    });
                    atualizarPickList('selectTipologiaSala');
                },
                error: function (response) {
                    generateGrow('Problemas ao carregar Tipologia', 'danger');
                }
            });
        }
    }


    $('body').on('click', '.deleteSala', function () {
        var url = $(this).data('url');
        $('#salaConfirmaExclusao').html($(this).data('sala'));
        $("#dialog-excluir_sala_gerenciamento").removeClass("displayNone");
        $("#dialog-excluir_sala_gerenciamento").dialog({
            resizable: false,
            height: 190,
            width: 490,
            dialogClass: "no-close",
            modal: true,
            buttons: {
                "Não": {
                    text: 'Não',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-excluir_sala_gerenciamento').dialog("close");
                    }
                },
                "Sim": {
                    text: 'Sim',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function (response) {
                                var responseJson = $.parseJSON(response);
                                if (responseJson) {
                                    growMessage('Sala limpa com sucesso.', 'success');
                                    limparCamposSalas();
                                    listaSalas();
                                } else {
                                    growMessage('Não foi possível deletar a sala.', 'danger');
                                }
                            },
                            error: function (response) {
                                generateGrow('Não foi possível deletar a sala.', 'danger');
                            }
                        });
                        $('#dialog-excluir_sala_gerenciamento').dialog("close");
                    }
                }
            }
        });
    });

    /**
     * Verifica se o usuário não já foi chamado
     */
    function verificaChamada(sala, servidorCpf, nomeServidor, idAgendamento) {
        var url = $('#editable-sample').data('url');
        $.ajax({
            url: url + "/consultarChamadaSala",
            type: 'get',
            async: false,
            global: false,
            data: {
                sala: sala,
                servidor_cpf: servidorCpf,
                idAgendamento: idAgendamento
            },
            success: function (response) {
                var responseJson = $.parseJSON(response);
                if (responseJson) {
                    play();
                    informarSala(sala, servidorCpf, nomeServidor);
                }
            },
            error: function (response) {
//                location.reload(true);
            }
        });
    }

    /**
     * Função abrir Modal de Informação da Sala
     */
    function informarSala(sala, servidorCpf, nomeServidor) {
        $('#parametroNomeServidor').html(nomeServidor);
        $('#parametroSala').html(sala);

        $("div#dialog-chamada_para_sala").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-chamada_para_sala").removeClass("displayNone");
        $("#dialog-chamada_para_sala").dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: [{
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        var url = $('#editable-sample').data('url');
                        $.ajax({
                            url: url + "/avisoConfirmadoSala",
                            type: 'get',
                            async: false,
                            global: false,
                            data: {
                                sala: sala,
                                servidor_cpf: servidorCpf
                            },
                            success: function (response) {

                            }
                        });
                        stop();
                        $(this).dialog("close");
                    }
                }]
        });
    }

    //BLoco de audio
    var audio = document.getElementById('playMusic');

    function play() {
        audio.play();
    }

    function stop() {
        audio.pause();
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

});
