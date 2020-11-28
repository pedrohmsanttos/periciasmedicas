jQuery(function ($) {

    //Montando no edite os selecionados
    $(".tipologias_multi_select_agendamento option:selected").each(function () {
        
        var $this = $(this);
        $("#AgendaAtendimentoTipologia").append("<option value='" + $this.val() + "'>" + $this.text() + "</option>");
        $("#AgendaAtendimentoTipologia").multiSelect('refresh');

        $("#AgendaAtendDomicilioTipologia").append("<option value='" + $this.val() + "'>" + $this.text() + "</option>");
        $("#AgendaAtendDomicilioTipologia").multiSelect('refresh');
    });


    //Montando as tipologias de acordo com os selecionados
    $('.tipologias_multi_select_agendamento').multiSelect({
        afterSelect: function (values) {
            if ($("#AgendaAtendimentoTipologia option[value='" + values + "']").text() === "") {
                //PERMITINDO APENAS TRÊS ITENS SEREM SELECIONADOS
                var label = $("#TipologiaTipologia option[value='" + values + "']").text();
                $("#AgendaAtendimentoTipologia").append("<option value='" + values + "'>" + label + "</option>");
                $("#AgendaAtendimentoTipologia").multiSelect('refresh');
            }

            if ($("#AgendaAtendDomicilioTipologia option[value='" + values + "']").text() === "") {
                //PERMITINDO APENAS TRÊS ITENS SEREM SELECIONADOS
                var label = $("#TipologiaTipologia option[value='" + values + "']").text();
                $("#AgendaAtendDomicilioTipologia").append("<option value='" + values + "'>" + label + "</option>");
                $("#AgendaAtendDomicilioTipologia").multiSelect('refresh');
            }

            // console.log('entrei');
        },
        afterDeselect: function (values) {
            var url = $('#TipologiaTipologia').attr('url-data') + 'verificaRemocaoTipologia';
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
                        $('#TipologiaTipologia').multiSelect('select', values);
                    } else {
                        $("#AgendaAtendimentoTipologia option[value='" + values + "']").remove();
                    }
                },
                error: function (response) {
                    generateGrow('Problemas ao verificar possibilidade de remoção dessa tipologia.', 'danger');
                }
            });


            url = $('#TipologiaTipologia').attr('url-data') + 'verificaRemTipoAgndDomicilio';
            $.ajax({
                url: url,
                type: "POST",
                data: {id: idTipologia},
                dataType: "html",
                success: function (response) {
                    var objResponse = $.parseJSON(response);
                    if (objResponse) {
                        generateGrow('Não é possível remover essa tipologia, por que já existe agendamento em domicílio ligado a ela.', 'danger');
                        $('#TipologiaTipologia').multiSelect('select', values);
                    } else {
                        $("#AgendaAtendDomicilioTipologia option[value='" + values + "']").remove();
                    }
                },
                error: function (response) {
                    generateGrow('Problemas ao verificar possibilidade de remoção dessa tipologia.', 'danger');
                }
            });
        }
    });

    /**
     * Função para esconder o endereço do dependente
     */
    function escondeEnderecoDependente() {
        //ATRIBUIR VALORES DO ENDEREÇO DO PERITO.
        $("#EnderecoDependenteCep").val($('#EnderecoUsuarioCep').val());
        $("#EnderecoDependenteLogradouro").val($('#EnderecoUsuarioLogradouro').val());
        $("#EnderecoDependenteNumero").val($('#EnderecoUsuarioNumero').val());
        $("#EnderecoDependenteComplemento").val($('#EnderecoUsuarioComplemento').val());
        $("#EnderecoDependenteBairro").val($('#EnderecoUsuarioBairro').val());
        $("#EnderecoDependenteEstadoId").val($('#EnderecoUsuarioEstadoId').val());
        $("#EnderecoDependenteMunicipioId").val($('#EnderecoUsuarioMunicipioId').val());

        //ESCONDENDO DIV
        $('#enderecoDependente').hide();
    }

    /**
     * Função para exibir o fieldset de endereço.
     */
    function exibirEndenrecoDependente() {
        //PASSANDO OS VALORES PARA VAZIO
        $("#EnderecoDependenteCep").val("");
        $("#EnderecoDependenteLogradouro").val("");
        $("#EnderecoDependenteNumero").val("");
        $("#EnderecoDependenteComplemento").val("");
        $("#EnderecoDependenteBairro").val("");
        $("#EnderecoDependenteEstadoId").val("");
        $("#EnderecoDependenteMunicipioId").val("");

        //EXIBINDO DIV DO ENDEREÇO
        $('#enderecoDependente').show();
    }

    $('#enderecoDependenteServidor').change(function () {
        if (this.checked) {
            escondeEnderecoDependente();
        } else {
            exibirEndenrecoDependente();
        }
    });

//FUNÇÃO
    $('#adicionarFuncaoUsuario').click(function () {
        removerAlertas();
        if ($('#UsuarioFuncao').val() == "") {
            removerAlertas();
            generateGrow('Selecione ao menos uma função!', 'danger');
        } else {
            var url = $('#adicionarFuncaoUsuario').attr('url-data') + 'adicionarFuncaoVinculo/';
            var idFuncao = $('#UsuarioFuncao').val();
            var nomeFuncao = $("#UsuarioFuncao option[value='" + idFuncao + "']").text();

            $.ajax({
                url: url,
                type: "POST",
                data: {id: idFuncao, nome: nomeFuncao},
                dataType: "html",
                success: function (response) {
                    var objResponse = $.parseJSON(response);
                    $.each(objResponse, function (key, value) {
                        var table = document.getElementById("tableFuncao");
                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = value.nome;
                        cell2.innerHTML = '<div rel="' + value.id + '" url-data="' + $('#adicionarFuncaoUsuario').attr('url-data') + '" class="btn deletarFuncao fa btn-danger" title="Excluir">Excluir</div>';
                    });
                    $('#UsuarioFuncao').val('');
                    $('#emptyFuncao').fadeOut(1500);
                }
            });
        }
    });

    /**
     * Deletar função
     */
    $(document).on("click", ".deletarFuncao", function () {
        removerAlertas();
        var url = $(this).attr('url-data') + 'deletarFuncaoSession/';
        var id = $(this).attr('rel');
        //REMOVENDO LINHA
        var td = $(this).parent();
        var tr = td.parent();

        $.ajax({
            url: url,
            type: "POST",
            data: {id: id},
            dataType: "html",
            success: function (response) {
                tr.fadeOut(400, function () {
                    tr.remove();
                });

                if ($('#tableFuncao tr').length == 3) {
                    $('#emptyFuncao').fadeIn(1500);
                }
            },
            error: function (response) {
                generateGrow('Problemas ao deletar função', 'danger');
            }
        });
    });

//ADICIONAR LOTAÇÃO
    $('#adicionarLotacaoUsuario').click(function () {
        removerAlertas();
        if ($('#UsuarioLotacao').val() == "") {
            generateGrow('Selecione ao menos uma lotação!', 'danger');
        } else {
            var url = $('#adicionarLotacaoUsuario').attr('url-data') + 'adicionarLotacaoVinculo/';
            var idFuncao = $('#UsuarioLotacao').val();
            var nomeFuncao = $("#UsuarioLotacao option[value='" + idFuncao + "']").text();

            $.ajax({
                url: url,
                type: "POST",
                data: {id: idFuncao, nome: nomeFuncao},
                dataType: "html",
                success: function (response) {
                    var objResponse = $.parseJSON(response);
                    $.each(objResponse, function (key, value) {
                        var table = document.getElementById("tableLotacao");
                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = value.nome;
                        cell2.innerHTML = '<div rel="' + value.id + '" url-data="' + $('#adicionarLotacaoUsuario').attr('url-data') + '" class="btn deletarLotacao fa btn-danger" title="Excluir">Excluir</div>';
                    });
                    $('#UsuarioLotacao').val('');
                    $('#emptyLotacao').fadeOut(1500);
                }
            });
        }
    });

    //DELETAR LOTAÇÃO
    $(document).on("click", ".deletarLotacao", function () {
        removerAlertas();
        var url = $(this).attr('url-data') + 'deletarLotacaoSession/';
        var id = $(this).attr('rel');
        //REMOVENDO LINHA
        var td = $(this).parent();
        var tr = td.parent();

        $.ajax({
            url: url,
            type: "POST",
            data: {id: id},
            dataType: "html",
            success: function (response) {
                tr.fadeOut(400, function () {
                    tr.remove();
                });

                if ($('#tableLotacao tr').length == 3) {
                    $('#emptyLotacao').fadeIn(1500);
                }
            },
            error: function (response) {
                generateGrow('Problemas ao excluir lotação.', 'danger');
            }
        });
    });

    //ADICIONAR VINCULO
    $('#adicionarVinculoUsuario').click(function () {
        var url = $('#adicionarVinculoUsuario').attr('url-data') + 'adicionarVinculo/';
        var idOrgaoOrigem = $('#VinculoOrgaoOrigemId').val();
        var nomeOrgaoOrigem = $("#VinculoOrgaoOrigemId option[value='" + idOrgaoOrigem + "']").text();
        var matricula = $("#VinculoMatricula").val();
        var idCargo = $("#VinculoCargoId").val();
        var nomeCargo = $("#VinculoCargoId option[value='" + idCargo + "']").text();
        var dataAdmissao = $("#VinculoDataAdmissaoServidor").val();
        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: {orgao_origem_id: idOrgaoOrigem,
                nomeOrgaoOrigem: nomeOrgaoOrigem,
                matricula: matricula,
                cargo_id: idCargo,
                nomeCargo: nomeCargo,
                data_admissao_servidor: dataAdmissao},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    $.each(objResponse, function (key, value) {
                        var table = document.getElementById("tableVinculo");
                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4);
                        var cell6 = row.insertCell(5);
                        cell1.innerHTML = value.OrgaoOrigem.orgao_origem;
                        cell2.innerHTML = value.Vinculo.matricula;
                        cell3.innerHTML = value.Cargo.nome;
                        cell4.innerHTML = value.Funcao.string;
                        cell5.innerHTML = value.Lotacao.string;
                        cell6.innerHTML = '<div rel="' + key + '" url-data="' + $('#adicionarVinculoUsuario').attr('url-data') + '" class="btn deletarVinculo fa btn-danger" title="Excluir">Excluir</div>';
                    });

                    //RETIRANDO O EMPTY DO VINCULO
                    $('#emptyVinculo').fadeOut(1500);
                    $('#emptyVinculo').addClass('displayNone');

                    //LIMPANDO AS TABELAS DE LOTAÇÃO E FUNÇÃO, PARA EVITAR O RELOAD
                    $('#tbodyLotacal tr').fadeOut(1500);
                    $('#tbodyFuncao tr').fadeOut(1500);

                    //LIBERANDO O EMPTY PARA FUNÇÃO E LOTAÇÃO.
                    $('#emptyFuncao').fadeIn(1500);
                    $('#emptyLotacao').fadeIn(1500);


                    $('#fieldVinculo input').val('');

                    $('#VinculoOrgaoOrigemId').val('');
                    $('#VinculoCargoId').val('');
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            }
        });
    });

    //DELETAR VINCULO
    $(document).on("click", ".deletarVinculo", function () {
        var url = $(this).attr('url-data') + 'deletarVinculoSession/';
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

                if ($('#tableVinculo tr').length == 3) {
                    $('#emptyVinculo').removeClass('displayNone');
                    $('#emptyVinculo').fadeIn(1500);
                }
            },
            error: function (response) {
                generateGrow('Problemas ao deletar a vinculo', 'danger');
            }
        });
    });

    /**
     * Chamada da função para liberar ou esconder os campos!
     */
    var tipoUsuario = $('#UsuarioTipoUsuario').val();
    validaTipoUsuario(tipoUsuario);

    /**
     *  Função para validar o tipo de usuário e liberar os campos que o pertence.
     */
    function validaTipoUsuario(tipoUsuario) {
        var peritoCredenciado = $('#usuario_perito_credenciado').val();
        var peritoServidor = $('#usuario_perito_servidor').val();
        var interno = $('#usuario_interno').val();
        var servidor = $('#usuario_servidor').val();

        //REFERENTE A NUMERO DE REGISTRO DE ACORDO COM O TIPO DE USUÁRIO
        if ((tipoUsuario == peritoCredenciado) || (tipoUsuario == peritoServidor)) {
            removeAddClass('#numeroRegistroDisplay', '#UsuarioNumeroRegistro', true);
        } else {
            removeAddClass('#numeroRegistroDisplay', '#UsuarioNumeroRegistro', false);
        }

        //REFERENTE A SENHA
        if ((tipoUsuario == peritoCredenciado) || (tipoUsuario == interno)) {
            if (!$("form").hasClass("formVisualizacao")) {
                $('#passAlterUser').removeClass('displayNone');

                $("#UsuarioSenhaAtual").removeAttr(('disabled'));
                $("#UsuarioNovaSenha").removeAttr(('disabled'));
                $("#UsuarioConfirmaNovaSenha").removeAttr(('disabled'));
            }
        } else {
            $('#passAlterUser').addClass('displayNone');
            //Limpando campos
            $("#UsuarioSenhaAtual").val('');
            $("#UsuarioNovaSenha").val('');
            $("#UsuarioConfirmaNovaSenha").val('');
        }

        //HABILITANDO CAMPO DE UNIDADE DE ATENDIMENTO PARA O USUÁRIO INTERNO
        if (tipoUsuario == interno) {
            removeAddClass('#unidadeAtendimentoUsuario', '#UsuarioUnidadeAtendimento', true);
        } else {
            removeAddClass('#unidadeAtendimentoUsuario', '#UsuarioUnidadeAtendimento', false);
        }

        //RETIRANDO Empresa
        if (tipoUsuario == peritoCredenciado) {
            $("#fieldVinculo").addClass('displayNone');
            $("#VinculoOrgaoOrigemId").val('');
            $("#VinculoMatricula").val('');
            $("#VinculoCargoId").val('');
            $("#VinculoDataAdmissaoServidor").val('');
            $("#UsuarioFuncao").val('');
            $("#UsuarioLotacao").val('');
            $("#tableVinculo tbody tr").remove();
            $("#tableFuncao tbody tr").remove();
            $("#tableLotacao tbody tr").remove();
            $("#tableVinculo").append("<tr id='emptyVinculo'><td colspan='6' style='text-align: center;'> Nenhum registro encontrado </td></tr>");
            $("#tableFuncao").append("<tr id='emptyFuncao'><td colspan='2' style='text-align: center;'> Nenhum registro encontrado </td></tr>");
            $("#tableLotacao").append("<tr id='emptyLotacao'><td colspan='2' style='text-align: center;'> Nenhum registro encontrado </td></tr>");
            removeAddClass('#divEmpresaAbaProfissao', '#UsuarioEmpresaComplete', true);
            if (!$("form").hasClass("formVisualizacao")) {
                $('#hiddenEmpresaId').removeAttr('disabled');
            }
        } else {
            $("#fieldVinculo").removeClass('displayNone');
            removeAddClass('#divEmpresaAbaProfissao', '#UsuarioEmpresaComplete', false);
            //Limpando Campo
            $("#hiddenEmpresaId").val('');
            //Disabled em campo
        }
        //HABILITANDO ABA DE TRABALHO PARA OS PERITOS
        if (tipoUsuario == peritoCredenciado || tipoUsuario == peritoServidor) {
            $('#abaDadosProfissionais').removeClass('displayNone');
            $('#dados-profissionais').removeClass('displayNone');
            if (!$("form").hasClass("formVisualizacao")) {
                $('#UsuarioDataAdmissaoPericia').removeAttr('disabled');
            }
            disabilitaDiv('#dependentes', false);
        } else {



            $("#TipologiaTipologia option:selected").removeAttr("selected");

            $('#abaDadosProfissionais').addClass('displayNone');
            $('#dados-profissionais').addClass('displayNone');
            //Limpando Campo
            $("#UsuarioDataAdmissaoPericia").val('');
            disabilitaDiv('#dependentes', true);
        }
    }


    var labelEmail = $("#labelEmail").text();

    //Verificando o tipo de usuário e aplicando regras
    $('#UsuarioTipoUsuario').change(function () {
        var tipoUsuario = parseInt($('#UsuarioTipoUsuario').val());
        if (tipoUsuario == 1 || tipoUsuario == 3) {
            labelEmail = labelEmail.replace("*", "");
            $("#labelEmail").text(labelEmail + "*");
        } else {
            $("#labelEmail").text(labelEmail.replace("*", ""));
        }
        validaTipoUsuario(tipoUsuario);
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

    /**
     * Função para o AutoComplete para empresas
     */
    $("#UsuarioEmpresaComplete").autocomplete({
        source: $('#UsuarioEmpresaComplete').attr('data-url'),
        open: function (eventi, ui) {
            $('#hiddenEmpresaId').val('');
        },
        select: function (a, b) {
            var idEmpresa = b.item.id;
            $('#hiddenEmpresaId').removeAttr('disabled');
            $("#hiddenEmpresaId").val(idEmpresa);
        }
    });


    $("#UsuarioRg").bind("keyup blur focus", function (e) {
        e.preventDefault();
        var expre = /[^\d]/g;
        $(this).val($(this).val().replace(expre, ''));
    });


     function isArrayEmpty(array) {
        return array.filter(function(el) {
            return !jQuery.isEmptyObject(el);
        }).length === 0;
    }
   
     $('#AgendaAtendimentoDomicilioMunicipioId').change(function () {
        var url = $('#AgendaAtendimentoDomicilioMunicipioId').attr('url-data') + 'retornaUnidAtendimentoMunicipio/';
        var idMunicipio = $('#AgendaAtendimentoDomicilioMunicipioId').val();
        
        $.ajax({
            url: url,
            type: "POST",
            data: {municipio: idMunicipio},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                    var options = "";
                    options += "<option value=''>Selecione</option>"
                    $.each(objResponse, function (key, value) {
                        options += '<option value="' + key + '">' + value + '</option>';
                    
                    });
                    if( objResponse.length == 0 ) {
                        generateGrow('Não há unidades que atendam em domicílio nesse município', 'danger');
                        $('#AgendaAtendimentoDomicilioUnidadeAtendimentoId').attr('disabled', true); 
                    }else{
                       $("#AgendaAtendimentoDomicilioUnidadeAtendimentoId").html(options);
                       $('#AgendaAtendimentoDomicilioUnidadeAtendimentoId').attr('disabled', false); 
                    }
                    
            },
            error: function (response) {
                generateGrow('Problemas ao consultar unidades de atendimento', 'danger');
            }
        });
    });

    //Adicionar agenda de atendimento
    $("#adicionarAgendaAtendimento").click(function () {

        var diaSemana = $("#AgendaAtendimentoDiaSemana").val();
        var horarioInicial = $("#AgendaAtendimentoHorarioInicial").val();
        var horarioFinal = $("#AgendaAtendimentoHorarioFinal").val();
        var unidadeAtendimento = $("#AgendaAtendimentoUnidadeAtendimentoId").val();
        var nomeUnidadeAtendimento = $("#AgendaAtendimentoUnidadeAtendimentoId option[value='" + unidadeAtendimento + "']").text();
        // var permitirAgendameto = $("#AgendaAtendimentoPermitirAgendamento").val();
        var permitirAgendameto = "";

        if($("#AgendaAtendimentoPermitirAgendamento").prop('checked')){
            permitirAgendameto = "1";            
        }else{
            permitirAgendameto = "0";
        }

        var ids_tipologias = [];
        var nomes_tipologias = [];
        $('#AgendaAtendimentoTipologia option:selected').each(function (i, selected) {
            ids_tipologias[i] = $(selected).val();
            nomes_tipologias[i] = $(selected).text();
        });
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'adicionarAgendaAtendimento',
            type: "POST",
            data: {dia_semana: diaSemana,
                hora_inicial: horarioInicial,
                hora_final: horarioFinal,
                unidade_atendimento_id: unidadeAtendimento,
                nome_unidade_atendimento: nomeUnidadeAtendimento,
                Tipologia: ids_tipologias,
                nome_tipologia: nomes_tipologias,
                permitir_agendamento: permitirAgendameto,
                },
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    $('#emptyAgendaAtendimento').addClass('displayNone');
                    $("#tableAgendaAtendimento > tbody > .linhaRegistro").remove();
                    var table = $("#tableAgendaAtendimento tbody");
                    //Montando tabela atualizada
                    $.each(objResponse, function (key, objAgenda) {

                        var permitirAgendamento = "";

                        if(objAgenda.AgendaAtendimento.permitir_agendamento == 1){
                            permitirAgendamento = "Sim";
                        }else{
                            permitirAgendamento = "Não";
                        }

                        table.append("<tr class='linhaRegistro'><td>" +
                                objAgenda.AgendaAtendimento.dia_semana +
                                "</td><td>" +
                                objAgenda.AgendaAtendimento.hora_inicial + ' / ' + objAgenda.AgendaAtendimento.hora_final +
                                "</td><td>" +
                                objAgenda.AgendaAtendimento.nome_unidade_atendimento +
                                "</td><td>" +
                                objAgenda.AgendaAtendimento.nome_tipologia +
                                "</td><td>" +
                                permitirAgendamento +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn editarAgendaAtendimento fa btn-info" title="Editar">Editar</div>' +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn deletarAgendaAtendimento fa btn-danger" title="Excluir">Excluir</div>' +
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

    //Adicionar agenda de atendimento em domicilio
    $("#adicionarAgendaAtendimentoDomicilio").click(function () {

        var diaSemana = $("#AgendaAtendimentoDomicilioDiaSemana").val();
        var horarioInicial = $("#AgendaAtendimentoDomicilioHorarioInicial").val();
        var horarioFinal = $("#AgendaAtendimentoDomicilioHorarioFinal").val();
        var unidadeAtendimento = $("#AgendaAtendimentoDomicilioUnidadeAtendimentoId").val();
        var nomeUnidadeAtendimento = $("#AgendaAtendimentoDomicilioUnidadeAtendimentoId option[value='" + unidadeAtendimento + "']").text();
        var municipio = $("#AgendaAtendimentoDomicilioMunicipioId").val();
        var nomeMunicipio = $("#AgendaAtendimentoDomicilioMunicipioId option[value='" + municipio + "']").text();
        var ids_tipologias = [];
        var nomes_tipologias = [];

        // console.log('DIA DA SEMANA: ' + diaSemana);
        // console.log('HORARIO INICIAL: ' + horarioInicial);
        // console.log('HORARIO FINAL: ' + horarioFinal);
        // console.log('UNIDADE ATENDIMENTO: ' + unidadeAtendimento);
        // console.log('NOME UND ATENDIMENTO: ' + nomeUnidadeAtendimento);
        // console.log('MUNICIPIO: ' + municipio);
        // console.log('NOME MUNICIPIO: ' + nomeMunicipio);

        $('#AgendaAtendDomicilioTipologia option:selected').each(function (i, selected) {
            ids_tipologias[i] = $(selected).val();
            nomes_tipologias[i] = $(selected).text();
        });
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'adicionarAgendaAtendDomicilio',
            type: "POST",
            data: {
                dia_semana: diaSemana,
                hora_inicial: horarioInicial,
                hora_final: horarioFinal,
                unidade_atendimento_id: unidadeAtendimento,
                nome_unidade_atendimento: nomeUnidadeAtendimento,
                municipio_id: municipio,
                nome_municipio: nomeMunicipio,
                Tipologia: ids_tipologias,
                nome_tipologia: nomes_tipologias
            },
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    $('#emptyAgendaAtendimentoDomicilio').addClass('displayNone');
                    $("#tableAgendaAtendimentoDomicilio > tbody > .linhaRegistro").remove();
                    var table = $("#tableAgendaAtendimentoDomicilio tbody");
                    //Montando tabela atualizada
                    $.each(objResponse, function (key, objAgenda) {
                        table.append(
                                        "<tr class='linhaRegistro'>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.dia_semana +"</td>" +
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.hora_inicial + ' / ' + objAgenda.AgendaAtendimentoDomicilio.hora_final + "</td>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.nome_unidade_atendimento + "</td>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.nome_municipio + "</td>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.nome_tipologia + "</td>" + 
                                            "<td>" +
                                                '<div rel="' + key + '" url-data="' + url + '" class="btn editarAgendaAtendimentoDomicilio fa btn-info" title="Editar">Editar</div>' +
                                            "</td>" + 
                                            "<td>" +
                                                '<div rel="' + key + '" url-data="' + url + '" class="btn deletarAgendaAtendimentoDomicilio fa btn-danger" title="Excluir">Excluir</div>' +
                                            "</td>" +
                                        "</tr>"
                                    );
                    });
                    limparAgendaDomicilio();
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
        $('#AgendaAtendimentoDiaSemana').val('');
        $('#AgendaAtendimentoHorarioInicial').val('');
        $('#AgendaAtendimentoHorarioFinal').val('');
        $('#AgendaAtendimentoHorarioFinal').attr('disabled', true);
        $('#AgendaAtendimentoUnidadeAtendimentoId').val('');
        clearSelectedItensMultiSelect('AgendaAtendimentoTipologia');
        $("#AgendaAtendimentoTipologia").multiSelect('refresh');
        $('#AgendaAtendimentoPermitirAgendamento').bootstrapSwitch('state', true);
        //botões para inserir
        $('#adicionarAgenda').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarAgenda').addClass('displayNone');
    }

    /**
     * Limpar agenda em domicilio
     */
    function limparAgendaDomicilio() {
        $('#AgendaAtendimentoDomicilioDiaSemana').val('');
        $('#AgendaAtendimentoDomicilioHorarioInicial').val('');
        $('#AgendaAtendimentoDomicilioHorarioFinal').val('');
        $('#AgendaAtendimentoDomicilioHorarioFinal').attr('disabled', true);
        $('#AgendaAtendimentoDomicilioUnidadeAtendimentoId').val('');
        $('#AgendaAtendimentoDomicilioMunicipioId').val('');

        clearSelectedItensMultiSelect('AgendaAtendDomicilioTipologia');
        $("#AgendaAtendDomicilioTipologia").multiSelect('refresh');
        //botões para inserir
        $('#adicionarAgenda').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarAgenda').addClass('displayNone');
    }

    //DELETAR AGENDA DE ATENDIMENTO
    $(document).on("click", ".deletarAgendaAtendimento", function () {
        var url = $(this).attr('url-data') + 'deletarAgentaAtendimento';
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
                var obj = JSON.parse(response);
                console.log(obj);
                if(!obj.falha){
                    tr.fadeOut(400, function () {
                        tr.remove();
                    });
                    if ($('#tableAgendaAtendimento tr').length == 3) {
                        if ($('#emptyAgendaAtendimento').hasClass('displayNone')) {
                            $('#emptyAgendaAtendimento').removeClass('displayNone');
                        }
                        $('#emptyAgendaAtendimento').fadeIn(1500);
                    }
                }else{
                    generateGrow(obj.msg, 'danger');
                }
            },
            error: function (response) {
                generateGrow('Problemas ao deletar agenda de atendimento', 'danger');
            }
        });
    });

    //DELETAR AGENDA DE ATENDIMENTO em DOMICILIO
    $(document).on("click", ".deletarAgendaAtendimentoDomicilio", function () {
        var url = $(this).attr('url-data') + 'deletarAgendaAtendimentoDomicilio';
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
                if ($('#tableAgendaAtendimentoDomicilio tr').length == 3) {
                    if ($('#emptyAgendaAtendimentoDomicilio').hasClass('displayNone')) {
                        $('#emptyAgendaAtendimentoDomicilio').removeClass('displayNone');
                    }
                    $('#emptyAgendaAtendimentoDomicilio').fadeIn(1500);
                }
            },
            error: function (response) {
                generateGrow('Problemas ao deletar agenda de atendimento', 'danger');
            }
        });
    });

    //Carregando as informações que iram ser editadas para Agenda de Atendimento.
    $("body").on("click", ".editarAgendaAtendimento", function () {
        var url = $(this).attr('url-data') + 'obterAgendaAtendimento';
        var id = $(this).attr('rel');
        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: {id: id},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                $("#AgendaAtendimentoDiaSemana").val(objResponse.AgendaAtendimento.dia_semana);
                $('#AgendaAtendimentoHorarioInicial').val(objResponse.AgendaAtendimento.hora_inicial);
                $('#AgendaAtendimentoHorarioFinal').val(objResponse.AgendaAtendimento.hora_final);
                $("#AgendaAtendimentoUnidadeAtendimentoId").val(objResponse.AgendaAtendimento.unidade_atendimento_id);

                if(objResponse.AgendaAtendimento.permitir_agendamento == 1){
                    $('#AgendaAtendimentoPermitirAgendamento').bootstrapSwitch('state', true);
                }else{
                    $('#AgendaAtendimentoPermitirAgendamento').bootstrapSwitch('state', false);
                }

                var tipologias = objResponse.Tipologia;
                clearSelectedItensMultiSelect('AgendaAtendimentoTipologia');
                
                if ($.isArray(tipologias)) {
                    //Se existir mais de uma tipologia selecionada, a variavel "tipologias" será do tipo array
                    $.each((tipologias), function () {
                        //Busca cada tipologia selecionada e atribui a propriedade selected para a mesma no picklist
                        $("#AgendaAtendimentoTipologia").find('option[value=' + this + ']').prop('selected', true);
                    });
                }else{
                    $("#AgendaAtendimentoTipologia").find('option[value=' + tipologias + ']').prop('selected', true);
                }
                
                $("#AgendaAtendimentoTipologia").multiSelect('refresh');

                $('#AgendaAtendimentoId').val(id);

                //botões para inserir
                $('#adicionarAgenda').addClass('displayNone');

                //Botões para Atualizar
                $('#atualizarAgenda').removeClass('displayNone');

                $('#AgendaAtendimentoHorarioFinal').attr('disabled', false);

            },
            error: function (response) {
                generateGrow('Problemas ao carregar informações desse atendimento', 'danger');
            }
        });
    });

    //Carregando as informações que iram ser editadas para Agenda de Atendimento em Domicilio.
    $("body").on("click", ".editarAgendaAtendimentoDomicilio", function () {
        var url = $(this).attr('url-data') + 'obterAgendaAtendimentoDomicilio';
        var id = $(this).attr('rel');
        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: {id: id},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                $("#AgendaAtendimentoDomicilioDiaSemana").val(objResponse.AgendaAtendimentoDomicilio.dia_semana);
                $('#AgendaAtendimentoDomicilioHorarioInicial').val(objResponse.AgendaAtendimentoDomicilio.hora_inicial);
                $('#AgendaAtendimentoDomicilioHorarioFinal').val(objResponse.AgendaAtendimentoDomicilio.hora_final);
                $("#AgendaAtendimentoDomicilioUnidadeAtendimentoId").val(objResponse.AgendaAtendimentoDomicilio.unidade_atendimento_id);
                $("#AgendaAtendimentoDomicilioMunicipioId").val(objResponse.AgendaAtendimentoDomicilio.municipio_id);

                var tipologias = objResponse.Tipologia;
                clearSelectedItensMultiSelect('AgendaAtendDomicilioTipologia');
                
                if ($.isArray(tipologias)) {
                    //Se existir mais de uma tipologia selecionada, a variavel "tipologias" será do tipo array
                    $.each((tipologias), function () {
                        //Busca cada tipologia selecionada e atribui a propriedade selected para a mesma no picklist
                        $("#AgendaAtendDomicilioTipologia").find('option[value=' + this + ']').prop('selected', true);
                    });
                }else{
                    $("#AgendaAtendDomicilioTipologia").find('option[value=' + tipologias + ']').prop('selected', true);
                }
                
                $("#AgendaAtendDomicilioTipologia").multiSelect('refresh');

                $('#AgendaAtendimentoDomicilioId').val(id);

                //botões para inserir
                $('#adicionarAgendaDomicilio').addClass('displayNone');

                //Botões para Atualizar
                $('#atualizarAgendaDomicilio').removeClass('displayNone');

                $('#AgendaAtendimentoDomicilioHorarioFinal').attr('disabled', false);

            },
            error: function (response) {
                generateGrow('Problemas ao carregar informações desse atendimento', 'danger');
            }
        });
    });

    //Cancelando a edição
    $("#cancelarAtualizarAgenda").click(function () {

        $('#AgendaAtendimentoDiaSemana').val("");
        $('#AgendaAtendimentoHorarioInicial').val("");
        $('#AgendaAtendimentoHorarioFinal').val("");
        $('#AgendaAtendimentoUnidadeAtendimentoId').val("");
        clearSelectedItensMultiSelect('AgendaAtendimentoTipologia');
        $("#AgendaAtendimentoTipologia").multiSelect('refresh');
        $('#AgendaAtendimentoId').val("");


        //botões para inserir
        $('#adicionarAgenda').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarAgenda').addClass('displayNone');
    });

    //Cancelando a edição da edição do agendamento em domicilio
    $("#cancelarAtualizarAgendaDomicilio").click(function () {

        $('#AgendaAtendimentoDomicilioDiaSemana').val("");
        $('#AgendaAtendimentoDomicilioHorarioInicial').val("");
        $('#AgendaAtendimentoDomicilioHorarioFinal').val("");
        $('#AgendaAtendimentoDomicilioUnidadeAtendimentoId').val("");
        $('#AgendaAtendimentoDomicilioMunicipioId').val("");
        clearSelectedItensMultiSelect('AgendaAtendDomicilioTipologia ');
        $("#AgendaAtendDomicilioTipologia").multiSelect('refresh');
        $('#AgendaAtendimentoDomicilioId').val("");


        //botões para inserir
        $('#adicionarAgendaDomicilio').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarAgendaDomicilio').addClass('displayNone');
    });

    //Atualizar agenda de Atendimento
    $("#atualizarAgendaAtendimento").click(function () {

        var diaSemana = $("#AgendaAtendimentoDiaSemana").val();
        var horarioInicial = $("#AgendaAtendimentoHorarioInicial").val();
        var horarioFinal = $("#AgendaAtendimentoHorarioFinal").val();
        var unidadeAtendimento = $("#AgendaAtendimentoUnidadeAtendimentoId").val();
        var nomeUnidadeAtendimento = $("#AgendaAtendimentoUnidadeAtendimentoId option[value='" + unidadeAtendimento + "']").text();
        var ids_tipologias = [];
        var nomes_tipologias = [];
        $('#AgendaAtendimentoTipologia option:selected').each(function (i, selected) {
            ids_tipologias[i] = $(selected).val();
            nomes_tipologias[i] = $(selected).text();
        });
        var permitirAgendameto = "";

        if($("#AgendaAtendimentoPermitirAgendamento").prop('checked')){
            permitirAgendameto = "1";            
        }else{
            permitirAgendameto = "0";
        }

        var id = $('#AgendaAtendimentoId').val();
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'atualizarAgendaAtendimento',
            type: "POST",
            data: {dia_semana: diaSemana,
                hora_inicial: horarioInicial,
                hora_final: horarioFinal,
                unidade_atendimento_id: unidadeAtendimento,
                Tipologia: ids_tipologias,
                id: id,
                nome_unidade_atendimento: nomeUnidadeAtendimento,
                nome_tipologia: nomes_tipologias,
                permitir_agendamento: permitirAgendameto},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    if (objResponse !== false) {
                        $("#tableAgendaAtendimento > tbody > tr").remove();
                        var table = $("#tableAgendaAtendimento tbody");
                        table.append("<tr id='emptyAgendaAtendimento' class='displayNone' style='text-align: center;'><td colspan='6'> Nenhum registro encontrado </td></tr>");
                        //Montando tabela atualizada
                        $.each(objResponse, function (key, objAgenda) {
                            var permitirAgendamento = "";

                            if(objAgenda.AgendaAtendimento.permitir_agendamento == 1){
                                permitirAgendamento = "Sim";
                            }else{
                                permitirAgendamento = "Não";
                            }

                            table.append("<tr class='linhaRegistro'><td>" +
                                    objAgenda.AgendaAtendimento.dia_semana +
                                    "</td><td>" +
                                    objAgenda.AgendaAtendimento.hora_inicial + ' / ' + objAgenda.AgendaAtendimento.hora_final +
                                    "</td><td>" +
                                    objAgenda.AgendaAtendimento.nome_unidade_atendimento +
                                    "</td><td>" +
                                    objAgenda.AgendaAtendimento.nome_tipologia +
                                   "</td><td>" +
                                    permitirAgendamento +
                                    "</td><td>" +
                                    '<div rel="' + key + '" url-data="' + url + '" class="btn editarAgendaAtendimento fa btn-info" title="Editar">Editar</div>' +
                                    "</td><td>" +
                                    '<div rel="' + key + '" url-data="' + url + '" class="btn deletarAgendaAtendimento fa btn-danger" title="Excluir">Excluir</div>' +
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

    //Atualizar agenda de Atendimento em Domicilio
    $("#atualizarAgendaAtendimentoDomicilio").click(function () {

        var diaSemana = $("#AgendaAtendimentoDomicilioDiaSemana").val();
        var horarioInicial = $("#AgendaAtendimentoDomicilioHorarioInicial").val();
        var horarioFinal = $("#AgendaAtendimentoDomicilioHorarioFinal").val();
        var unidadeAtendimento = $("#AgendaAtendimentoDomicilioUnidadeAtendimentoId").val();
        var nomeUnidadeAtendimento = $("#AgendaAtendimentoDomicilioUnidadeAtendimentoId option[value='" + unidadeAtendimento + "']").text();
        var municipio = $("#AgendaAtendimentoDomicilioMunicipioId").val();
        var nomeMunicipio = $("#AgendaAtendimentoDomicilioMunicipioId option[value='" + municipio + "']").text();
        var ids_tipologias = [];
        var nomes_tipologias = [];

        $('#AgendaAtendDomicilioTipologia option:selected').each(function (i, selected) {
            ids_tipologias[i] = $(selected).val();
            nomes_tipologias[i] = $(selected).text();
        });

        var id = $('#AgendaAtendimentoDomicilioId').val();
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'atualizarAgendaAtendimentoDomicilio',
            type: "POST",
            data: {
                id: id,
                dia_semana: diaSemana,
                hora_inicial: horarioInicial,
                hora_final: horarioFinal,
                unidade_atendimento_id: unidadeAtendimento,
                nome_unidade_atendimento: nomeUnidadeAtendimento,
                municipio_id: municipio,
                nome_municipio: nomeMunicipio,
                Tipologia: ids_tipologias,
                nome_tipologia: nomes_tipologias
            },
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    if (objResponse !== false) {
                        $("#tableAgendaAtendimentoDomicilio > tbody > tr").remove();
                        var table = $("#tableAgendaAtendimentoDomicilio tbody");
                        table.append("<tr id='emptyAgendaAtendimento' class='displayNone' style='text-align: center;'><td colspan='6'> Nenhum registro encontrado </td></tr>");
                        //Montando tabela atualizada
                        $.each(objResponse, function (key, objAgenda) {
                            table.append(
                                        "<tr class='linhaRegistro'>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.dia_semana +"</td>" +
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.hora_inicial + ' / ' + objAgenda.AgendaAtendimentoDomicilio.hora_final + "</td>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.nome_unidade_atendimento + "</td>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.nome_municipio + "</td>" + 
                                            "<td>" + objAgenda.AgendaAtendimentoDomicilio.nome_tipologia + "</td>" + 
                                            "<td>" +
                                                '<div rel="' + key + '" url-data="' + url + '" class="btn editarAgendaAtendimentoDomicilio fa btn-info" title="Editar">Editar</div>' +
                                            "</td>" + 
                                            "<td>" +
                                                '<div rel="' + key + '" url-data="' + url + '" class="btn deletarAgendaAtendimentoDomicilio fa btn-danger" title="Excluir">Excluir</div>' +
                                            "</td>" +
                                        "</tr>"
                                    );
                        });
                        limparAgendaDomicilio();
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

    /**
     * Função que monta o objeto de dependentes
     * @param {int} id
     * @returns {usuario_L1.montarObjetoDependente.retorno|Boolean}
     */
    function montarObjetoDependente(id) {
        var retorno = false;
        if (document.getElementById('enderecoDependenteServidor').checked) {
            var enderecoServidor = true;

            retorno = {nome: $("#DependenteNome").val(),
                cpf: $("#DependenteCpf").val(),
                rg: $("#DependenteRg").val(),
                data_nascimento: $("#DependenteDataNascimento").val(),
                inscricao_funape: $("#DependenteInscricaoFunape").val(),
                qualidade_id: $("#DependenteQualidadeId").val(),
                nome_pai: $("#DependenteNomePai").val(),
                nome_mae: $("#DependenteNomeMae").val(),
                id: id,
                endereco_servidor: enderecoServidor};
        } else {
            var enderecoServidor = false;

            retorno = {nome: $("#DependenteNome").val(),
                cpf: $("#DependenteCpf").val(),
                rg: $("#DependenteRg").val(),
                data_nascimento: $("#DependenteDataNascimento").val(),
                inscricao_funape: $("#DependenteInscricaoFunape").val(),
                qualidade_id: $("#DependenteQualidadeId").val(),
                nome_pai: $("#DependenteNomePai").val(),
                nome_mae: $("#DependenteNomeMae").val(),
                id: id,
                enedereco_servidor: enderecoServidor,
                endereco_dependente_cep: $("#EnderecoDependenteCep").val(),
                endereco_dependente_logradouro: $("#EnderecoDependenteLogradouro").val(),
                endereco_dependente_numero: $("#EnderecoDependenteNumero").val(),
                endereco_dependente_complemento: $("#EnderecoDependenteComplemento").val(),
                endereco_dependente_bairro: $("#EnderecoDependenteBairro").val(),
                endereco_dependente_estado_id: $("#EnderecoDependenteEstadoId").val(),
                endereco_dependente_municipio_id: $("#EnderecoDependenteMunicipioId").val()};
        }

        return retorno;
    }

    // Adicionar Dependente
    $("#adicionarDependente").click(function () {
        var objDependente = montarObjetoDependente();
        var url = $(this).attr('data-url');
        removerAlertas();
        $.ajax({
            url: url + 'adicionarDependente',
            type: "POST",
            data: objDependente,
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    $('#emptyDependente').addClass('displayNone');
                    var table = $("#tableDependentes tbody");
                    //Montando tabela atualizada

                    $.each(objResponse, function (key, objDependente) {
                        table.append("<tr class><td>" +
                                objDependente.Dependente.nome +
                                "</td><td>" +
                                objDependente.Dependente.cpf +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn editarDependente fa btn-info" title="Editar">Editar</div>' +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn deletarDependente fa btn-danger" title="Excluir">Excluir</div>' +
                                "</td></tr>");
                    });
                    limparDiv('#dependentes');
                    limpaFormularioDependentes();
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao adicionar dependente.', 'danger')
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

    //DELETAR DEPENDENTE
    $(document).on("click", ".deletarDependente", function () {
        var url = $(this).attr('url-data') + 'deletarDependente';
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
                if ($('#tableDependentes tr').length == 3) {
                    if ($('#emptyDependente').hasClass('displayNone')) {
                        $('#emptyDependente').removeClass('displayNone');
                    }
                    $('#emptyDependente').fadeIn(1500);
                }
            },
            error: function (response) {
                generateGrow('Problemas ao excluir dependente.', 'danger');
            }
        });
    });

    //Carregando as informações que iram ser editadas para o Dependente.
    $(document).on("click", ".editarDependente", function () {
        var url = $(this).attr('url-data');
        var id = $(this).attr('rel');
        removerAlertas();
        $.ajax({
            url: url + 'obterDependente',
            type: "POST",
            data: {id: id},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);

                $('#DependenteNome').val(objResponse.Dependente.nome);
                $('#DependenteCpf').val(objResponse.Dependente.cpf);
                $('#DependenteRg').val(objResponse.Dependente.rg);
                $('#DependenteDataNascimento').val(objResponse.Dependente.data_nascimento);
                $("#DependenteInscricaoFunape").val(objResponse.Dependente.inscricao_funape);
                $("#DependenteQualidadeId").val(objResponse.Dependente.qualidade_id);
                $("#DependenteNomePai").val(objResponse.Dependente.nome_pai);
                $("#DependenteNomeMae").val(objResponse.Dependente.nome_mae);
                $('#DependenteId').val(id);
                aplicarMascaraCpf();
                if (objResponse.Dependente.endereco_servidor) {
                    escondeEnderecoDependente();
                    $('#enderecoDependenteServidor').prop('checked', true);
                } else {
                    $('#enderecoDependenteServidor').removeAttr('checked');
                    var municipio_id = objResponse.EnderecoDependente.municipio_id;
                    exibirEndenrecoDependente();

                    montarMunicipios(objResponse.EnderecoDependente.estado_id, 'EnderecoDependenteMunicipioId',
                            url + 'carregarListaMunicipio', municipio_id);

                    $("#EnderecoDependenteCep").val(objResponse.EnderecoDependente.cep);
                    $("#EnderecoDependenteLogradouro").val(objResponse.EnderecoDependente.logradouro);
                    $("#EnderecoDependenteNumero").val(objResponse.EnderecoDependente.numero);
                    $("#EnderecoDependenteComplemento").val(objResponse.EnderecoDependente.complemento);
                    $("#EnderecoDependenteBairro").val(objResponse.EnderecoDependente.bairro);
                    $("#EnderecoDependenteEstadoId").val(objResponse.EnderecoDependente.estado_id);
                    $("#EnderecoDependenteMunicipioId").val(objResponse.EnderecoDependente.municipio_id);
                }

                //botões para inserir
                $('#adicionarDependente').addClass('displayNone');

                //Botões para Atualizar
                $('#atualizarDependente').removeClass('displayNone');

            },
            error: function (response) {
                generateGrow('Problemas ao carregar informações.', 'danger');
            }
        });
    });

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

    //Cancelando a edição
    $("#cancelarAtualizarDependente").click(function () {
        limpaFormularioDependentes();
    });

    /**
     * Função para atualizar na session os dependentes
     */
    $("#atualizarDependenteSession").on('click', function () {
        var objDependente = montarObjetoDependente($('#DependenteId').val());
        var url = $(this).attr('data-url');
        console.log(objDependente);
        $.ajax({
            url: url + 'atualizarDependente',
            type: "POST",
            data: objDependente,
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                removerAlertas();
                if (objResponse.status != 'danger') {
                    $("#tableDependentes > tbody > tr").remove();
                    var table = $("#tableDependentes tbody");
                    table.append("<tr id='emptyDependente' class='displayNone' style='text-align: center;'><td colspan='4'> Nenhum registro encontrado </td></tr>");
                    //Montando tabela atualizada
                    $.each(objResponse, function (key, objDependente) {
                        table.append("<tr class><td>" +
                                objDependente.Dependente.nome +
                                "</td><td>" +
                                objDependente.Dependente.cpf +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn editarDependente fa btn-info" title="Editar">Editar</div>' +
                                "</td><td>" +
                                '<div rel="' + key + '" url-data="' + url + '" class="btn deletarDependente fa btn-danger" title="Excluir">Excluir</div>' +
                                "</td></tr>");
                    });
                    limpaFormularioDependentes();
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao atualizar dependente.', 'danger');
            }
        });

    });


    /**
     * limpar form dependentes
     */
    function limpaFormularioDependentes() {
        $('#DependenteNome').val("");
        $('#DependenteCpf').val("");
        $('#DependenteRg').val("");
        $('#DependenteDataNascimento').val("");
        $("#DependenteInscricaoFunape").val("");
        $("#DependenteQualidadeId").val("");
        $("#DependenteNomePai").val("");
        $("#DependenteNomeMae").val("");
        $('#DependenteId').val("");

        $('#enderecoDependenteServidor').removeAttr('checked');

        exibirEndenrecoDependente();

        //botões para inserir
        $('#adicionarDependente').removeClass('displayNone');

        //Botões para Atualizar
        $('#atualizarDependente').addClass('displayNone');
    }


    //Ações de cadastros 

    //Adicioanr
    $('#ajaxAdd').click(function () {
        if($("#UsuarioNovaSenha").val() != null){

            var validaSenha = $("#UsuarioNovaSenha").val();
          

            if(validaSenha != ""){

                if(validaSenha.length < 6){
                    generateGrow('Nova senha deve conter no mínimo 6 caracteres.', 'danger');
                    return false;
                }

                var regexLetter = '[a-zA-Z]';
                if(!validaSenha.match(regexLetter)){
                    generateGrow('Nova senha deve conter letra.', 'danger');
                    return false;
                }

                var regexNumber = '[0-9]';
                if(!validaSenha.match(regexNumber)){
                    generateGrow('Nova senha deve conter número.', 'danger');
                    return false;
                }


                var regexCharacterEspecial = /\W/g;
                if(!validaSenha.match(regexCharacterEspecial)){
                    generateGrow('Nova senha deve conter caracter especial.', 'danger');
                    return false;
                }



            }

        }



        var url = $(this).data('url');

        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: $('#formBody').serialize(),
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    window.location.href = objResponse.url;
                } else {

                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao adicionar usuário.', 'danger');
            }
        });
    });

    //Adicionar e após inserir um novo
    $('#ajaxAddAfterNew').click(function () {
        var url = $(this).data('url');
        removerAlertas();
        $.ajax({
            url: url,
            type: "POST",
            data: $('#formBody').serialize(),
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if (objResponse.status != 'danger') {
                    window.location.href = objResponse.url + '/adicionar';
                } else {
                    $.each(objResponse.message.erros, function (key, arrayMsg) {
                        generateGrow(arrayMsg, 'danger');
                    });
                }
            },
            error: function (response) {
                generateGrow('Problemas ao adicionar usuário.', 'danger');
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

    function limparSessaoLotacao(url) {
        $.ajax({
            url: url + 'limparSessaoLotacao',
            type: "POST",
            data: {id: $('#VinculoOrgaoOrigemId').val()},
            dataType: "html",
            success: function (response) {
                $("#tableLotacao > tbody > tr").remove();
                var table = $("#tableLotacao tbody");
                //Montando tabela atualizada
                table.append("<tr id='emptyLotacao' style='text-align: center;'><td colspan='2'> Nenhum registro encontrado </td></tr>");
            },
            error: function (response) {
                generateGrow('Problemas ao carregar lotações.', 'danger');
            }
        });
    }

    /**
     * Acrescentando opções ao campo de lotações.
     */
    $('#VinculoOrgaoOrigemId').change(function () {
        var url = $(this).data('url');
        $.ajax({
            url: url + 'montarLotacoes',
            type: "POST",
            data: {id: $('#VinculoOrgaoOrigemId').val()},
            dataType: "html",
            success: function (response) {
                var responseJson = $.parseJSON(response);
                $("#UsuarioLotacao option").remove();
                $("#UsuarioLotacao").append(new Option("Selecione", ""));
                $.each(responseJson, function (e, i) {
                    $("#UsuarioLotacao").append(new Option(i, e));
                });

                limparSessaoLotacao(url);
            },
            error: function (response) {
                generateGrow('Problemas ao carregar lotações.', 'danger');
            }
        });
    });

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
    $('#AgendaAtendimentoHorarioInicial').change(function () {
        var horaInicio = $('#AgendaAtendimentoHorarioInicial').val();
        var arrHour = horaInicio.split(':');
        var time = new Date(2014, 12, 10, arrHour[0], arrHour[1], 00);

        //Adicionando duas horas a mais
        time.addHours(2);
        //Acrescentando 0 a esquerda.
        var dataSugerida = pad(time.getHours(), 2) + ':' + pad(time.getMinutes(), 2);
        //Setando o valor sugerido da data final
        $('#AgendaAtendimentoHorarioFinal').val(dataSugerida);
        //removendo disabled
        $('#AgendaAtendimentoHorarioFinal').removeAttr('disabled');
    });

    /*
        Habilita o campo HORA FINAL após ter preenchido o campo HORA INICIAL do AGENDAMENTO em DOMICILIO
    */
    $('#AgendaAtendimentoDomicilioHorarioInicial').change(function () {
        // console.log('entrei no change');
        var horaInicio = $('#AgendaAtendimentoDomicilioHorarioInicial').val();
        var arrHour = horaInicio.split(':');
        var time = new Date(2014, 12, 10, arrHour[0], arrHour[1], 00);

        //Adicionando duas horas a mais
        time.addHours(2);
        //Acrescentando 0 a esquerda.
        var dataSugerida = pad(time.getHours(), 2) + ':' + pad(time.getMinutes(), 2);
        //Setando o valor sugerido da data final
        $('#AgendaAtendimentoDomicilioHorarioFinal').val(dataSugerida);
        //removendo disabled
        $('#AgendaAtendimentoDomicilioHorarioFinal').removeAttr('disabled');
    });

    /**
     * Validando a diferença entre os horarios.
     */
    $('#AgendaAtendimentoHorarioFinal').change(function () {
        var horaInicio = $('#AgendaAtendimentoHorarioInicial').val();
        var arrHourIni = horaInicio.split(':');

        var horaFinal = $('#AgendaAtendimentoHorarioFinal').val();
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