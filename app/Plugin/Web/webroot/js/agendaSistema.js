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

jQuery(function ($) {

    //Montando no edite os selecionados
    $(".tipologias_multi_select_agendamento option:selected").each(function () {
        
        var $this = $(this);
        console.log($this.val(), ' - ', $this.text());
        $("#AgendaSistemaItemTipologia").append("<option value='" + $this.val() + "'>" + $this.text() + "</option>");
        $("#AgendaSistemaItemTipologia").multiSelect('refresh');
    });


    //Montando as tipologias de acordo com os selecionados
    $('.tipologias_multi_select_agendamento').multiSelect({
        afterSelect: function (values) {
            console.log('afterSelect', ' - ', values);
            if ($("#AgendaSistemaItemTipologia option[value='" + values + "']").text() === "") {
                //PERMITINDO APENAS TRÊS ITENS SEREM SELECIONADOS
                var label = $(".tipologiasDisponiveis option[value='" + values + "']").text();
                $("#AgendaSistemaItemTipologia").append("<option value='" + values + "'>" + label + "</option>");
                $("#AgendaSistemaItemTipologia").multiSelect('refresh');
            }
        },
        afterDeselect: function (values) {
            console.log('afterDeselect', ' - ', values);

            var url = $('.tipologiasDisponiveis').attr('url-data') + 'verificaRemocaoTipologia';
            var idTipologia = values;
            removerAlertas();

            $.ajax({
                url: url,
                type: "POST",
                data: {id: idTipologia},
                dataType: "html",
                success: function (response) {
                    var objResponse = $.parseJSON(response);
                    if (objResponse) {
                        generateGrow('Não é possível remover essa tipologia, por que já existe agendamento ligado a ela.', 'danger');
                        $('.tipologiasDisponiveis').multiSelect('select', values);
                    } else {
                        $("#AgendaSistemaItemTipologia option[value='" + values + "']").remove();
                        $("#AgendaSistemaItemTipologia").multiSelect('refresh');
                    }
                },
                error: function (response) {
                    generateGrow('Problemas ao verificar possibilidade de remoção dessa tipologia.', 'danger');
                }
            });
        }
    });


    /**
     * Remove Classe e Adiciona Classe de acordo com o tipo de usuário.
     */
    function removeAddClass(div, input, removeAdd) {
        if (removeAdd) {
            $(div).removeClass('displayNone');
        } else {
            $(input).val('');
            $(div).addClass('displayNone');
        }
    }

    /**
     * Criando Grow
     */


     function isArrayEmpty(array) {
        return array.filter(function(el) {
            return !jQuery.isEmptyObject(el);
        }).length === 0;
    }
   


    //Adicionar agenda de atendimento
    $("#adicionarAgendaSistemaItem").click(function () {

        var diaSemana = $("#AgendaSistemaItemDiaSemana").val();
        var horarioInicial = $("#AgendaSistemaItemHorarioInicial").val();
        var horarioFinal = $("#AgendaSistemaItemHorarioFinal").val();
        var unidadeAtendimento = $("#AgendaSistemaItemUnidadeAtendimentoId").val();
        var nomeUnidadeAtendimento = $("#AgendaSistemaItemUnidadeAtendimentoId option[value='" + unidadeAtendimento + "']").text();
        var ids_tipologias = [];
        var nomes_tipologias = [];
        $('#AgendaSistemaItemTipologia option:selected').each(function (i, selected) {
            ids_tipologias[i] = $(selected).val();
            nomes_tipologias[i] = $(selected).text();
        });
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'adicionarAgendaSistemaItem',
            type: "POST",
            data: {dia_semana: diaSemana,
                hora_inicial: horarioInicial,
                hora_final: horarioFinal,
                unidade_atendimento_id: unidadeAtendimento,
                nome_unidade_atendimento: nomeUnidadeAtendimento,
                Tipologia: ids_tipologias,
                nome_tipologia: nomes_tipologias},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    $('#emptyAgendaSistemaItem').addClass('displayNone');
                    $("#tableAgendaSistemaItem > tbody > .linhaRegistro").remove();
                    var table = $("#tableAgendaSistemaItem tbody");
                    //Montando tabela atualizada
                    $.each(objResponse, function (key, objAgenda) {
                        table.append("<tr class='linhaRegistro'>"+
                                '<td style="text-align: center"><span class="glyphicon glyphicon-'+(objAgenda.AgendaSistemaItem.validado?'ok ':'ban-circle ')+(objAgenda.AgendaSistemaItem.validado?"green":"red")+'"></span></td>' +
                                "<td>" +
                                objAgenda.AgendaSistemaItem.dia_semana +
                                "</td><td>" +
                                objAgenda.AgendaSistemaItem.hora_inicial + ' / ' + objAgenda.AgendaSistemaItem.hora_final +
                                "</td><td>" +
                                objAgenda.AgendaSistemaItem.nome_unidade_atendimento +
                                "</td><td>" +
                                objAgenda.AgendaSistemaItem.nome_tipologia +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn editarAgendaSistemaItem fa btn-info" title="Editar">Alterar</div>' +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn deletarAgendaSistemaItem fa btn-danger" title="Excluir">Excluir</div>' +
                                "</td></tr>");
                    });
                    limparAgenda();
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao cadastrar agenda de atendimento', 'danger');
            }
        });

    });

    /**
     * Limpar agenda
     */
    function limparAgenda() {
        $('#AgendaSistemaItemDiaSemana').val('');
        $('#AgendaSistemaItemHorarioInicial').val('');
        $('#AgendaSistemaItemHorarioFinal').val('');
        $('#AgendaSistemaItemHorarioFinal').attr('disabled', true);
        $('#AgendaSistemaItemUnidadeAtendimentoId').val('');
        clearSelectedItensMultiSelect('AgendaSistemaItemTipologia');
        $("#AgendaSistemaItemTipologia").multiSelect('refresh');
        //botões para inserir
        $('#adicionarAgenda').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarAgenda').addClass('displayNone');
    }

    //DELETAR AGENDA DE ATENDIMENTO
    $(document).on("click", ".deletarAgendaSistemaItem", function () {
        var url = $(this).attr('url-data') + 'deletarAgendaSistemaItem';
        var key = $(this).attr('rel');
        //REMOVENDO LINHA
        var td = $(this).parent();
        var tr = td.parent();
        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: {key: key},
            dataType: "html",
            success: function (response) {
                tr.fadeOut(400, function () {
                    tr.remove();
                });
                if ($('#tableAgendaSistemaItem tr').length == 3) {
                    if ($('#emptyAgendaSistemaItem').hasClass('displayNone')) {
                        $('#emptyAgendaSistemaItem').removeClass('displayNone');
                    }
                    $('#emptyAgendaSistemaItem').fadeIn(1500);
                }
            },
            error: function (response) {
                generateGrow('Problemas ao deletar agenda de atendimento', 'danger');
            }
        });
    });

    //Carregando as informações que iram ser editadas para Agenda de Atendimento.
    $("body").on("click", ".editarAgendaSistemaItem", function () {
        var url = $(this).attr('url-data') + 'obterAgendaSistemaItem';
        var id = $(this).attr('rel');
        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: {id: id},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                $("#AgendaSistemaItemDiaSemana").val(objResponse.AgendaSistemaItem.dia_semana);
                $('#AgendaSistemaItemHorarioInicial').val(objResponse.AgendaSistemaItem.hora_inicial);
                $('#AgendaSistemaItemHorarioFinal').val(objResponse.AgendaSistemaItem.hora_final);
                $("#AgendaSistemaItemUnidadeAtendimentoId").val(objResponse.AgendaSistemaItem.unidade_atendimento_id);

                var tipologias = objResponse.Tipologia;
                clearSelectedItensMultiSelect('AgendaSistemaItemTipologia');
                
                if ($.isArray(tipologias)) {
                    //Se existir mais de uma tipologia selecionada, a variavel "tipologias" será do tipo array
                    $.each((tipologias), function () {
                        //Busca cada tipologia selecionada e atribui a propriedade selected para a mesma no picklist
                        $("#AgendaSistemaItemTipologia").find('option[value=' + this + ']').prop('selected', true);
                    });
                }else{
                    $("#AgendaSistemaItemTipologia").find('option[value=' + tipologias + ']').prop('selected', true);
                }
                
                $("#AgendaSistemaItemTipologia").multiSelect('refresh');

                $('#AgendaSistemaItemId').val(id);

                //botões para inserir
                $('#adicionarAgenda').addClass('displayNone');

                //Botões para Atualizar
                $('#atualizarAgenda').removeClass('displayNone');

                $('#AgendaSistemaItemHorarioFinal').attr('disabled', false);

            },
            error: function (response) {
                generateGrow('Problemas ao carregar informações desse atendimento', 'danger');
            }
        });
    });

    //Cancelando a edição
    $("#cancelarAtualizarAgenda").click(function () {

        $('#AgendaSistemaItemDiaSemana').val("");
        $('#AgendaSistemaItemHorarioInicial').val("");
        $('#AgendaSistemaItemHorarioFinal').val("");
        $('#AgendaSistemaItemUnidadeAtendimentoId').val("");
        clearSelectedItensMultiSelect('AgendaSistemaItemTipologia');
        $("#AgendaSistemaItemTipologia").multiSelect('refresh');
        $('#AgendaSistemaItemId').val("");


        //botões para inserir
        $('#adicionarAgenda').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarAgenda').addClass('displayNone');
    });

    //Atualizar agenda de Atendimento
    $("#atualizarAgendaSistemaItem").click(function () {

        var diaSemana = $("#AgendaSistemaItemDiaSemana").val();
        var horarioInicial = $("#AgendaSistemaItemHorarioInicial").val();
        var horarioFinal = $("#AgendaSistemaItemHorarioFinal").val();
        var unidadeAtendimento = $("#AgendaSistemaItemUnidadeAtendimentoId").val();
        var nomeUnidadeAtendimento = $("#AgendaSistemaItemUnidadeAtendimentoId option[value='" + unidadeAtendimento + "']").text();
        var ids_tipologias = [];
        var nomes_tipologias = [];
        $('#AgendaSistemaItemTipologia option:selected').each(function (i, selected) {
            ids_tipologias[i] = $(selected).val();
            nomes_tipologias[i] = $(selected).text();
        });

        var id = $('#AgendaSistemaItemId').val();
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'atualizarAgendaSistemaItem',
            type: "POST",
            data: {dia_semana: diaSemana,
                hora_inicial: horarioInicial,
                hora_final: horarioFinal,
                unidade_atendimento_id: unidadeAtendimento,
                Tipologia: ids_tipologias,
                id: id,
                nome_unidade_atendimento: nomeUnidadeAtendimento,
                nome_tipologia: nomes_tipologias},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    if (objResponse !== false) {
                        $("#tableAgendaSistemaItem > tbody > tr").remove();
                        var table = $("#tableAgendaSistemaItem tbody");
                        table.append("<tr id='emptyAgendaSistemaItem' class='displayNone' style='text-align: center;'><td colspan='6'> Nenhum registro encontrado </td></tr>");
                        //Montando tabela atualizada
                        console.log(objResponse);
                        $.each(objResponse, function (key, objAgenda) {
                            table.append("<tr class='linhaRegistro'>"+
                                    '<td style="text-align: center"><span class="glyphicon glyphicon-'+(objAgenda.AgendaSistemaItem.validado?'ok ':'ban-circle ')+(objAgenda.AgendaSistemaItem.validado?"green":"red")+'"></span></td>' +
                                    "<td>" +
                                    objAgenda.AgendaSistemaItem.dia_semana +
                                    "</td><td>" +
                                    objAgenda.AgendaSistemaItem.hora_inicial + ' / ' + objAgenda.AgendaSistemaItem.hora_final +
                                    "</td><td>" +
                                    objAgenda.AgendaSistemaItem.nome_unidade_atendimento +
                                    "</td><td>" +
                                    objAgenda.AgendaSistemaItem.nome_tipologia +
                                    "</td><td>" +
                                    '<div rel="' + key + '" url-data="' + url + '" class="btn editarAgendaSistemaItem fa btn-info" title="Editar">Alterar</div>' +
                                    "</td><td>" +
                                    '<div rel="' + key + '" url-data="' + url + '" class="btn deletarAgendaSistemaItem fa btn-danger" title="Excluir">Excluir</div>' +
                                    "</td></tr>");
                        });
                        limparAgenda();
                    }
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao atualizar agenda de atendimento', 'danger');
            }
        });

    });

    function limparDiv(div) {
        $(div, $(this)).each(function (index) {
            this.value = "";
        });

    }

    function disabilitaDiv(div, disabled) {
        $(div, $(this)).each(function (index) {
            if (disabled) {
                $(this).attr('disabled', 'disabled');
            } else {
                if (!$("form").hasClass("formVisualizacao")) {
                    $(this).removeAttr('disabled');
                }
            }
        });

    }

    /**
     * Carrega municipio de acordo com o ID
     */
    function montarMunicipios(valorSelecionado, comboMunicipio, url, municipio_selected) {
        $.ajax({
            url: url,
            dataType: 'json',
            data: {estado_id: valorSelecionado},
            type: 'get',
            success: function (response) {
                $('#' + comboMunicipio).find('option').remove();
                $('#' + comboMunicipio).append(new Option('Selecione', ''));
                $.each(response, function (index, valor) {
                    if (municipio_selected === index) {
                        $('#' + comboMunicipio).append(new Option(valor, index, true, true));
                    } else {
                        $('#' + comboMunicipio).append(new Option(valor, index));
                    }
                });
            }
        });
    }



    //Ações de cadastros

    //Adicioanr
    $('#ajaxAdd, #ajaxDuplicar').click(function () {
        var url = $(this).data('url');

        removerAlertas();
        var funNext = function(){
            $.ajax({
                url: url,
                type: "POST",
                data: $('#formBody').serialize(),
                dataType: "html",
                success: function (response) {
                    var objResponse = $.parseJSON(response);
                    console.log(objResponse);
                    if(objResponse.status == 'redirect') {
                        location.href = objResponse.url;
                    }else if (objResponse.status == 'new') {
                        location.href = objResponse.url;
                    }else if (objResponse.status == 'danger') {
                        $.each(objResponse.message.erros, function (key, arrayMsg) {
                            generateGrow(arrayMsg, 'danger');
                        });
                    } else {
                        if(objResponse.AgendaSistema.validada == 0){
                            $('.modalMsg').modal();
                        }else{
                            generateGrow('Agenda salva e validada', 'success');
                        }
                    }
                },
                error: function (response) {
                    generateGrow('Problemas ao adicionar agenda.', 'danger');
                }
            });
        };
        if($(this).attr('id') == 'ajaxDuplicar'){
            if(confirm("Deseja realmente duplicar a agenda atual?")){
                funNext();
            }
        }else{
            funNext();
        }
    });

    //Adicionar e após inserir um novo
    $('#ajaxAddAfterNew').click(function (){
        if($('#AgendaSistemaHabilitada').is(":checked")){
            if(!confirm("Somente é possível habilitar uma agenda por vez, deseja continuar?"))return;
        }
        var url = $(this).data('url');

        removerAlertas();
        $.ajax({
            url: url+"/1",
            type: "POST",
            data: $('#formBody').serialize(),
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                console.log(objResponse);
                if(objResponse.status == 'redirect') {
                    location.href = objResponse.url;
                }else if (objResponse.status == 'new') {
                    if(objResponse.AgendaSistema.validada == 0){
                        $('.modalMsg').modal();
                    }else{
                        generateGrow('Agenda salva e validada', 'success');
                    }
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao adicionar agenda.', 'danger');
            }
        });
    });

    //Excluir
    $('#ajaxDelete').click(function () {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: "POST",
            data: $('#formBody').serialize(),
            dataType: "html",
            success: function (response) {
                window.location.href = response;
            },
            error: function (response) {
                window.location.href = response;
            }
        });
    });

    function removerAlertas() {
        $('.alert').remove();
    }

    //Acrescentando o add hours ao Date do js
    Date.prototype.addHours = function (h) {
        this.setHours(this.getHours() + h);
        return this;
    }

    function pad(str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    /*
        Habilita o campo HORA FINAL após ter preenchido o campo HORA INICIAL do AGENDAMENTO
    */
    $('#AgendaSistemaItemHorarioInicial').change(function () {
        var horaInicio = $('#AgendaSistemaItemHorarioInicial').val();
        var arrHour = horaInicio.split(':');
        var time = new Date(2014, 12, 10, arrHour[0], arrHour[1], 00);

        //Adicionando duas horas a mais
        time.addHours(2);
        //Acrescentando 0 a esquerda.
        var dataSugerida = pad(time.getHours(), 2) + ':' + pad(time.getMinutes(), 2);
        //Setando o valor sugerido da data final
        $('#AgendaSistemaItemHorarioFinal').val(dataSugerida);
        //removendo disabled
        $('#AgendaSistemaItemHorarioFinal').removeAttr('disabled');
    });

    /**
     * Validando a diferença entre os horarios.
     */
    $('#AgendaSistemaItemHorarioFinal').change(function () {
        var horaInicio = $('#AgendaSistemaItemHorarioInicial').val();
        var arrHourIni = horaInicio.split(':');

        var horaFinal = $('#AgendaSistemaItemHorarioFinal').val();
        var arrHourFim = horaFinal.split(':');

        //Time Inicial
        var timeIni = new Date(2014, 12, 10, arrHourIni[0], arrHourIni[1], 00);
        //Time Duas Horas
        var timeTwoHour = new Date(2014, 12, 10, arrHourIni[0], arrHourIni[1], 00);
        //Time Fim
        var timeFim = new Date(2014, 12, 10, arrHourFim[0], arrHourFim[1], 00);

        timeTwoHour.addHours(2);

        var timeIni = timeIni.getTime();
        var timeIniPlusTwo = timeTwoHour.getTime();
        var timeFim = timeFim.getTime();

        if (timeFim != timeIniPlusTwo) {
            $("#dialog-confirm-hour").css("background-color", "white");
            $('#dialog-confirm-hour').removeClass('displayNone');
            $("#dialog-confirm-hour").dialog({
                resizable: false,
                height: 180,
                dialogClass: "no-close",
                width: 350,
                modal: true,
                buttons: {
                    "Ok": {
                        text: 'Ok',
                        'class': 'btn fa estiloBotao btn-info float-right',
                        click: function () {
                            $('#dialog-confirm-hour').addClass('displayNone');
                            $(this).dialog("close");
                        }
                    }
                }
            });
        }

    });

});