/**  Adição de comentário **/
jQuery(function ($) {

    var peritoCredenciado = $('#usuario_perito_credenciado').val();
    var peritoServidor = $('#usuario_perito_servidor').val();
    var interno = $('#usuario_interno').val();
    var servidor = $('#usuario_servidor').val();
    camposDisponiveis($('#tipoUsuarioLogado').val());


    // if( $("#AgendamentoAtendimentoDomiciliar").is(':checked') ){
    //         $("#RowAtendUnidadeProxima").show();
    //         $('#AtendUnidadeProxima').val("");
    //         $('#MunicipioAtendimento').val("");

            

    //   } else {
    //         $("#RowAtendUnidadeProxima").hide();
    //         $("#RowMunicipio").hide();
    //         $("#RowUnidadeAtend").hide();

    //   }

     $("#AgendamentoAtendimentoDomiciliar").click(function () { 
     // event.preventDefault(); 
      if( $("#AgendamentoAtendimentoDomiciliar").is(':checked') ){
            $("#RowAtendUnidadeProxima").show();
            $('#AtendUnidadeProxima').val("");
            $('#MunicipioAtendimento').val("");

            

      } else {
            $("#RowAtendUnidadeProxima").hide();
            $("#RowMunicipio").hide();
            $("#RowUnidadeAtend").hide();
            $('#RowEnderecoAtendimento').hide();
            $("#RowCadastroEnderecoAtendimento").hide();

      }
  
   });

     $('#AtendUnidadeProxima').change(function(){
        var url = $('#MunicipioAtendimento').attr('url-data') + 'retornaUnidAtendimentoMunicipio';
        var valorAtendUndProx = $('#AtendUnidadeProxima').val();
        // alert(valorAtendUndProx);
        // var url = "<?=  Router::url('/admin/Agendamento/', true); ?>"; 
        // url = url + "retornaUnidAtendimentoMunicipio"

        if(valorAtendUndProx == "1"){
            $("#RowMunicipio").show();
            // $('#AtendEndereco').hide();
            // $("#RowCadastroEnderecoAtendimento").hide();
             $('#MunicipioAtendimento').change(function(){
                // alert($('#MunicipioAtendimento').val());
                var municipioAtend = $('#MunicipioAtendimento').val();
                // alert(url);
                
                // retornaUnidAtendimentoMunicipio
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {municipio: municipioAtend},
                    dataType: "html",
                    success: function (response) {
                        var options = "";
                        options += "<option value=''>Selecione</option>"
                        var objResponse = $.parseJSON(response);
                        
                        $.each(objResponse, function (key, value) {
                            options += '<option value="' + key + '">' + value + '</option>';
                            // console.log("Chave = " + key);
                            // console.log("Valor = " + value);
                            
                        });
                        $("#UnidadeAtendimento").html(options);
                        $("#RowUnidadeAtend").show();
                        // $('#UsuarioFuncao').val('');
                        // $('#emptyFuncao').fadeOut(1500);
                    },
                    error: function (response) {
                        generateGrow('Problemas ao consultar Unidades de Atendimento.', 'danger');
                    }
                });

             });

        }else if(valorAtendUndProx == "0"){
            // alert('Não');
            $("#RowEnderecoAtendimento").show();
        }else if(valorAtendUndProx == ""){
            $('#MunicipioAtendimento').val("");
            $("#RowMunicipio").hide();
        }


    });

    $('#AtendEndereco').change(function(){
        var url = $('#MunicipioAtendimento').attr('url-data') + 'retornaUnidAtendimentoMunicipio';
        var valorAtendEndereco = $('#AtendEndereco').val();
        // alert(valorAtendUndProx);
        console.log(valorAtendEndereco);
        // var url = "<?=  Router::url('/admin/Agendamento/', true); ?>"; 
        // url = url + "retornaUnidAtendimentoMunicipio"

        if(valorAtendEndereco == "1"){
            console.log('entrei');    
            $("#RowCadastroEnderecoAtendimento").show();
             // $('#MunicipioAtendimento').change(function(){
             //    // alert($('#MunicipioAtendimento').val());
             //    var municipioAtend = $('#MunicipioAtendimento').val();
                

             // });

        }else if(valorAtendEndereco == "0"){
            // alert('Não');
            $("#RowCadastroEnderecoAtendimento").show();
        }else if(valorAtendEndereco == ""){
            $('#MunicipioAtendimento').val("");
            $("#RowMunicipio").hide();
            $("#RowCadastroEnderecoAtendimento").hide();
        }


    });


    /**
     * Função que habilita e desabilita os campos da tela
     * @param {int} tipoUsuario
     */
    function camposDisponiveis(tipoUsuario) {
        if (tipoUsuario == interno) {
            $('#fieldsetServidor').removeClass('displayNone');
        } else {
            $('#fieldsetServidor').addClass('displayNone');
        }

        if ($('#comboTipologia').val() !== "") {
            $('#afterSolicitarLicenca').removeClass('displayNone');
        } else {
            $('#afterSolicitarLicenca').addClass('displayNone');
        }
    }
    
    /**
     * Função que escapa os dados de campos que não são necessarios
     * @param {type} string
     * @returns {unresolved}
     */
    function escapeRegExp(string) {
        return string.replace(/([._\-*+?^=!:${}()|\[\]\/\\])/g, "");
    }

    /**
     * Função para o AutoComplete de acordo com o CPF do servidor
     */
    $("#cpfServidor").on("keyup", function (e) {
        $('#nomeServidor').val('');
        $('#hiddenServidorId').val('');
        var url = $('#baseUrlDefault').data('url') + 'getServidorCpf/';
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
                        $('#hiddenServidorId').val(responseJson.id);
                        $('#nomeServidor').val(responseJson.nome);
                        $('#data_obito_servidor').val(responseJson.data_obito);
                        carregarLicencasConcedidas(true);
                    }
                }
            });
        }
    });

    var url = $("#comboTipologia").data('url');
    var idTipologia = $('#comboTipologia').val();
    var idUnidade = $('#AgendamentoUnidadeAtendimentoId').val();
    var idDiaSemana = $('#comboDiaSemana').val();
    var data_apartir_de = $('#AgendamentoDataAPartir').val();

    if (idTipologia != "" && idUnidade != "" && idDiaSemana != "") {
        consultarHorarios(idTipologia, idUnidade, idDiaSemana, url, data_apartir_de);
    }

    $('body').on('change', '.carregarHorarios', function () {
        var url = $(this).data('url');
        var idTipologia = $('#comboTipologia').val();
        var idUnidade = $('#AgendamentoUnidadeAtendimentoId').val();
        var idDiaSemana = $('#comboDiaSemana').val();
        var data_apartir_de = $('#AgendamentoDataAPartir').val();
        if (idTipologia != "" && idUnidade != "" && idDiaSemana != "") {
            consultarHorarios(idTipologia, idUnidade, idDiaSemana, url, data_apartir_de, $(this).is(':checkbox'));
        }
    });

    var qualidadeSelecionada = parseInt($('#AgendamentoQualidadeId').val());
    tratarQualidadeSelecionada(qualidadeSelecionada);

    $('body').on('change', '#AgendamentoQualidadeId', function () {
        var qualidadeSelecionada = parseInt($('#AgendamentoQualidadeId').val());
        tratarQualidadeSelecionada(qualidadeSelecionada);
    });

    function tratarQualidadeSelecionada(qualidadeSelecionada) {
        var qualidadeOutros = parseInt($("#qualidade_outros").val());
        if (qualidadeSelecionada === qualidadeOutros) {
            $(".qualidadeOutros").removeClass('displayNone');
        } else {
            $(".qualidadeOutros").addClass('displayNone');
            $("#AgendamentoOutros").val("");
        }
    }

    function consultarHorarios(idTipologia, idUnidade, idDiaSemana, url, data_apartir_de, encaixe) {
        $.ajax({
            url: url,
            type: "POST",
            data: {
                tipologia_id: idTipologia,
                unidade_id: idUnidade,
                dia_semana: idDiaSemana,
                data_inicial: data_apartir_de,
                checkEncaixe: $('.checkEncaixe').prop('checked')
            },
            dataType: "html",
            success: function (response) {

                var responseJson = $.parseJSON(response);
               
                $('#AgendamentoDataHora').find('option').remove();
                $('#AgendamentoDataHora').append(new Option('Selecione', ''));
                if (responseJson.length > 0) {
                    if (encaixe === false) {
                        $("#hidEncaixe").removeAttr('checked');
                        // $("#hidEncaixe").attr('disabled', true);
                    }
                    var existeHorario = false;
                    var horaAgendamento = $("#inputDataHora").val();
                    $.each(responseJson, function (index, valor) {
                        if (valor != null) {
                            $('#AgendamentoDataHora').append(new Option(valor, valor));
                            if (horaAgendamento == valor) {
                                $('#AgendamentoDataHora').val(valor);
                                existeHorario = true;
                            }
                        }
                    });
                    if (!existeHorario && $('#AgendamentoId').val()) {
                        $('#AgendamentoDataHora').append(new Option(horaAgendamento, horaAgendamento));
                        $('#AgendamentoDataHora').val(horaAgendamento);
                    }
                } else {
                    if ($('#formBody').data('action') !== "deletar" && encaixe === false) {
                        $("#hidEncaixe").attr('disabled', false);
                    }
                    var data_hora_edicao = $("#inputDataHora").val();
                    if (data_hora_edicao != "") {
                        var option = new Option($("#inputDataHora").val(), $("#inputDataHora").val());
                        $(option).prop('selected', true);
                        $('#AgendamentoDataHora').append(option);
                    }
                }

            },
            error: function (response) {
                location.reload(true);
            }
        });
    }

    /**
     * Função para o AutoComplete de acordo com o CPF do servidor
     */
    $("#nomeServidor").autocomplete({
        source: $('#baseUrlDefault').data('url') + 'getServidorNome/',
        minLength: 4,
        open: function (eventi, ui) {
            $('#cpfServidor').val('');
            $('#hiddenServidorId').val('');
        },
        select: function (a, b) {
            $('#cpfServidor').val(b.item.cpf);
            $('#hiddenServidorId').val(b.item.id);
            $('#data_obito_servidor').val(b.item.data_obito);
            aplicarMascaraCpf();
            carregarLicencasConcedidas(true);
        }
    });
    //Get Matricula da Chefia UM
    $('#chefiaImediataMatriculaUm').change(function () {
        var url = $('#baseUrlDefault').data('url') + 'getVinculoByMatricula/';
        var matricula = $(this).val();
        $.ajax({
            url: url,
            type: "POST",
            data: {matricula: matricula},
            dataType: "html",
            success: function (response) {
                //IMPLEMENTAR!
//                console.log(response);
            }
        });
    });
    /**
     * Habilitar campos Tipologia
     */
    function habilitarCamposTipologia() {
        //Desabilitar Campo Durante
//        desabilitarCampoDuracao(false);
        $("#divDurante").removeClass('displayNone');
        $('#divReadaptacaoDefinitiva').addClass('displayNone');
        if ($('#comboTipologia').val() != "") {
            $('#afterSolicitarLicenca').removeClass('displayNone');
        } else {
            $('#afterSolicitarLicenca').addClass('displayNone');
        }
        desabilitarCampoSolicitarLicenca(false);
    }

    $('body').on('change', '#hidAutorizoDivulgacao', function () {
        var checkSelecionado = $(this).prop('checked');
        if (!checkSelecionado) {
            exibirAlertaTipologiaResolucaoCmf();
            $('#botaoSalvar').addClass('displayNone');
        } else {
            $('#botaoSalvar').removeClass('displayNone');
        }
    });

    tratarRegrasTipologia(false);
    $('body').on('change', '#comboTipologia', function () {
        //Habilitando campos de acordo com a tipologia.
        habilitarCamposTipologia();
        //Aplicando regras da tipologia
        tratarRegrasTipologia(true);

    });
    /**
     * Aplicando regras de acordo com a tipologia.
     * @returns {undefined}
     */
    function tratarRegrasTipologia(eventoChange) {
        var tipologia_licenca_maternidade = parseInt($('#tipologia_licenca_maternidade').val());
        var tipologia_licenca_maternida_aborto = parseInt($('#tipologia_licenca_maternidade_aborto').val());
        var tipologia_licenca_natimorto = parseInt($('#tipologia_licenca_natimorto').val());
        var tipologia_aposentadoria_invalidez = parseInt($('#tipologia_aposentadoria_invalidez').val());
        var tipologia_antecipacao_licenca = parseInt($('#tipologia_atencipacao_licenca').val());
        var tipologia_isencao_contribuicao_previdenciaria = parseInt($('#tipologia_isencao_contribuicao_previdenciaria').val());
        var tipologia_reversao_aposentadoria_invalidez = parseInt($('#tipologia_reversao_aposentadoria_invalidez').val());
        var tipologia_avaliacao_habilitacao_dependentes = parseInt($('#tipologia_avaliacao_habilitacao_dependentes	').val());
        var tipologia_pcd = parseInt($('#tipologia_pcd').val());
        var tipologia_admissao_pensionista_maior_invalido = parseInt($('#tipologia_admissao_pensionista_maior_invalido').val());
        var tipologia_informacao_para_seguro_compreensivo_habitacional = parseInt($('#tipologia_informacao_seguro_compreensivo_habitacional').val());
        var tipologia_readaptacao_funcao = parseInt($('#tipologia_readaptacao_funcao').val());
        var tipologia_remanejamento_funcao = parseInt($('#tipologia_remanejamento_funcao').val());
        var tipologia_remocao = parseInt($('#tipologia_remocao').val());
        var tipologia_risco_vida_insalubridade = parseInt($('#tipologia_risco_vida_insalubridade').val());
      
        var tipologia_recurso_administrativo = parseInt($('#tipologia_recurso_administrativo').val());
        var tipologia_exame_pre_admissional = parseInt($('#tipologia_exame_pre_admissional').val());
        var tipologia_licenca_acompanhamento_familiar = parseInt($('#tipologia_licenca_acompanhamento_familiar').val());
        var tipologia_licenca_medica_tratamento_saude = parseInt($('#tipologia_licenca_medica_tratamento_saude').val());
        var tipologia_informativo_de_acidente_de_trabalho = parseInt($('#tipologia_informativo_de_acidente_de_trabalho').val());


        var tipologiaSelecionada = parseInt($('#comboTipologia').val());

        if (tipologiaSelecionada === tipologia_antecipacao_licenca) {
            $("#inputDuracao").removeAttr('readonly');
        }
        if (tipologiaSelecionada === tipologia_licenca_maternidade) {
            $('#inputDuracao').val('180');
            desabilitarCampoDuracao(true);
        }
        console.log('tipologiaSelecionada',tipologiaSelecionada);
        console.log('tipologia_informativo_de_acidente_de_trabalho',tipologia_informativo_de_acidente_de_trabalho);
        if(tipologiaSelecionada === tipologia_informativo_de_acidente_de_trabalho){
            $('.hidCAT').removeClass('displayNone');
            $('#registro_policial_cat').change();
            $('#assistencia_medica_hospitalar_acidente_doenca').change();
            $('#testemunha_acidente_doenca').change();
            $('#chkbxPlantonista').change();
            $('#expediente_plantonista').change();
            $('#plantonista_outro').change();



        }else{
            $('.hidCAT').addClass('displayNone');
        }



        if ($.inArray(tipologiaSelecionada, [tipologia_licenca_maternida_aborto, tipologia_licenca_natimorto]) !== -1) {
            $("#inputDuracao").val('30');
            desabilitarCampoDuracao(true);
        }

        if ($.inArray(tipologiaSelecionada, [tipologia_aposentadoria_invalidez, tipologia_isencao_contribuicao_previdenciaria,
            tipologia_reversao_aposentadoria_invalidez, tipologia_avaliacao_habilitacao_dependentes, tipologia_pcd,
            tipologia_admissao_pensionista_maior_invalido, tipologia_informacao_para_seguro_compreensivo_habitacional]) !== -1) {
            removerCampoDurante();
        }

        if ($.inArray(tipologiaSelecionada, [tipologia_readaptacao_funcao, tipologia_remanejamento_funcao, tipologia_remocao]) !== -1) {
            $('#divReadaptacaoDefinitiva').removeClass('displayNone');
            $("#inputDuracao").removeAttr('readonly');
        } else {
            $("#hidReadaptacaoDefinitiva").prop('checked', false);
        }

        if (tipologiaSelecionada === tipologia_risco_vida_insalubridade) {
            removerCampoDurante();
            exibirAlertaTipologiaInsalubridade();
        }

        if ($.inArray(tipologiaSelecionada, [tipologia_recurso_administrativo, tipologia_exame_pre_admissional]) !== -1) {
            $("#AgendamentoDataAPartir").val('');
            $("#inputDuracao").val('');
            desabilitarCampoSolicitarLicenca(true);
        }
        if (tipologiaSelecionada === tipologia_licenca_medica_tratamento_saude || tipologiaSelecionada === tipologia_licenca_acompanhamento_familiar) {
            $("#inputDuracao").removeAttr('readonly');
            $("#inputDuracao").val('');
        }

        desabilitarFieldsetAcompanhado();
    }

    function desabilitarCampoSolicitarLicenca(disabled) {
        if (disabled) {
            $(".colunaApartirDe").addClass('displayNone');
            $("#divDurante").addClass('displayNone');
        } else {
            $(".colunaApartirDe").removeClass('displayNone');
            $("#divDurante").removeClass('displayNone');
        }
    }

    function carregarLicencasConcedidas(eventoChange) {
        var idServidor = $("#hiddenServidorId").val();
        var url = $("#comboLicencasConcedidas").data('url');
        var tipologiaSelecionada = parseInt($('#comboTipologia').val());

        if (idServidor != "" && eventoChange == true) {
            $.ajax({
                url: url,
                type: "get",
                data: {idServidor: idServidor, tipologiaSelecionada: tipologiaSelecionada},
                dataType: "html",
                success: function (response) {
                    var responseJson = $.parseJSON(response);
                    $('#comboLicencasConcedidas').find('option').remove();
                    $('#comboLicencasConcedidas').append(new Option('Selecione', ''));
                    $.each(responseJson, function (index, valor) {
                        $('#comboLicencasConcedidas').append(new Option(valor, index));
                    });
                }
            });
        }
    }

    function exibirAlertaTipologiaInsalubridade() {
        $('#dialog-alerta-tipologia-insalubridade').removeClass('displayNone');
        $("div#dialog-alerta-tipologia-insalubridade").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-alerta-tipologia-insalubridade").dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: {
                "Ok": {
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-alerta-tipologia-insalubridade').addClass('displayNone');
                        $(this).dialog("close");
                    }
                }
            }
        });
    }

    function exibirAlertaTipologiaResolucaoCmf() {
        $('#dialog-alerta-resolucao-cmf').removeClass('displayNone');
        $("div#dialog-alerta-resolucao-cmf").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-alerta-resolucao-cmf").dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: {
                "Ok": {
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-alerta-resolucao-cmf').addClass('displayNone');
                        $(this).dialog("close");
                    }
                }
            }
        });
    }

    desabilitarFieldsetAcompanhado();

    /**
     * Função para habilitar e desabilitar o campo Durante
     * @returns {undefined}
     */
    function desabilitarFieldsetAcompanhado() {
        var tipologia_licenca_acompanhamento_familiar = parseInt($('#tipologia_licenca_acompanhamento_familiar').val());
        var tipologiaSelecionada = parseInt($('#comboTipologia').val());

        if (tipologiaSelecionada === tipologia_licenca_acompanhamento_familiar) {
            $("#fieldSetInformacoesAcompnhado").removeClass('displayNone');
        } else {
            $("#acompanhadoCidId").val('');
            $("#acompanhadoDoencaId").val('');
            $("#acompanhadoNomeSemAbreviacao").val('');
            $("#AgendamentoDataNascimentoAcompanhado").val('');
            $("#certidaoNascimentoAcompanhado").val('');
            $("#cpfAcompanhado").val('');
            $("#rgAcompanhado").val('');
            $("#orgaoExpedidorAcompanhado").val('');
            $("#nomeMaeAcompanhado").val('');
            $("#porqueVoceUnicaPessoaCuidar").val('');
            $("#porqueAssistenciaIncompativel").val('');
            $("#fieldSetInformacoesAcompnhado").addClass('displayNone');
        }
    }

    /**
     * Função para habilitar e desabilitar o campo Durante
     * @returns {undefined}
     */
    function desabilitarCampoDuracao(disabled) {
        if (disabled) {
            $("#inputDuracao").attr('readonly', disabled);
        } else {
            $("#inputDuracao").removeAttr('readonly');
            $("#inputDuracao").val('');
        }
    }

    /**
     * Função que remove o campo Durante
     * @returns {undefined}
     */
    function removerCampoDurante() {
        $("#inputDuracao").val('');
        $("#divDurante").addClass('displayNone');
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

    var respIncompativel = parseInt($('.ClassAssistenciaIncompativelCargo:checked').val());
    tratarAssistencaiImcompativelCargo(respIncompativel);

    $('.ClassAssistenciaIncompativelCargo').change(function () {
        var respIncompativel = parseInt($(this).val());
        tratarAssistencaiImcompativelCargo(respIncompativel);
    });

    function tratarAssistencaiImcompativelCargo(respIncompativel) {
        if (respIncompativel === 1) {
            $('#divPorqueAssistenciaIncompativel').removeClass('displayNone');
        } else {
            $('#porqueAssistenciaIncompativel').val('');
            $('#divPorqueAssistenciaIncompativel').addClass('displayNone');
        }
    }

    /**
     * Função para o AutoComplete de acordo com o CID
     */
    $("#acompanhadoCidId").autocomplete({
        source: $('#baseUrlDefault').data('url') + 'getCidAcompanhante/',
        select: function (a, b) {
            $('#hidAcompanhadoCidId').val(b.item.id);
            $('#acompanhadoDoencaId').val(b.item.nome_doenca);
        }
    });
    /**
     * Função para o AutoComplete de acordo com o CID
     */
    $("#acompanhadoDoencaId").autocomplete({
        source: $('#baseUrlDefault').data('url') + 'getCidNomeAcompanhante/',
//        minLength: 4,
        select: function (a, b) {
            $('#hidAcompanhadoCidId').val(b.item.id);
            $('#acompanhadoCidId').val(b.item.nome);
        }
    });
    /**
     * Função para limpar no change
     */
    $("#acompanhadoCidId").keydown(function (event) {
        if (event.keyCode != 13) {
            $('#hidAcompanhadoCidId').val('');
        }
    });
    /**
     * Função para limpar no change
     */
    $("#acompanhadoDoencaId").keydown(function () {
        if (event.keyCode != 13) {
            $('#hidAcompanhadoCidId').val('');
        }
    });
    /**
     * Função que faz a busca no change para montar combos de unidade de Atendimento
     */
    $('#AgendamentoCidId').on('change', function () {
        habilitarCamposAfterCid();
        var url = $('#baseUrlDefault').data('url') + 'getUnidadeCid/';
        var valueTipologia = $('#AgendamentoCidId').val();
        $.ajax({
            url: url,
            type: "POST",
            data: {idTipologia: valueTipologia},
            dataType: "html",
            success: function (response) {
                var responseJson = $.parseJSON(response);
                $('#AgendamentoUnidadeAtendimentoId').find('option').remove();
                $('#AgendamentoUnidadeAtendimentoId').append(new Option('Selecione', ''));
                $.each(responseJson, function (index, valor) {
                    if (valor.id != null) {
                        $('#AgendamentoUnidadeAtendimentoId').append(new Option(valor.name, valor.id));
                    }
                });
            }
        });
    });

    $('#comboLicencasConcedidas').on('change', function () {
        var url = $('#baseUrlDefault').data('url') + 'getUnidadeCidAtendimento/';
        var urlConsultarDataFinalLicenca = $('#baseUrlDefault').data('url') + 'getDataFinalLicenca/';
        var idAtendimento = $('#comboLicencasConcedidas').val();
        if ($('#formBody').data('action') !== "deletar") {
            if (idAtendimento !== "") {
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
            } else {
                $('#AgendamentoUnidadeAtendimentoId').val('');
                $('#inputAte').val('');
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
            }
        }
        if (idAtendimento != "") {
            $.ajax({
                url: url,
                type: "get",
                data: {idAtendimento: idAtendimento},
                dataType: "html",
                success: function (response) {
                    var responseJson = $.parseJSON(response);
                    $('#AgendamentoUnidadeAtendimentoId').find('option').remove();
                    $('#AgendamentoUnidadeAtendimentoId').append(new Option('Selecione', ''));
                    $.each(responseJson, function (index, valor) {
                        if (valor.id != null) {
                            $("#AgendamentoCidId").find("option:selected").removeAttr("selected");
                            $("#AgendamentoCidId").find('option[value=' + valor.cid + ']').attr('selected', true);
                            $("#AgendamentoCidId").val(valor.cid);
                            $('#AgendamentoUnidadeAtendimentoId').append(new Option(valor.name, valor.id));
                        }
                    });
                }
            });

            $.ajax({
                url: urlConsultarDataFinalLicenca,
                type: "get",
                data: {idAtendimento: idAtendimento},
                dataType: "html",
                success: function (response) {
                    $("#inputAte").val(response);
                }
            });
        }


    });


    if ($('#formBody').data('action') === "deletar") {
        $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
        // $('#hidEncaixe').attr('disabled', true);
    }

    /**
     * Habilitando as funções de acordo com o Edit
     */
    habilitarCamposAfterCid();
    /**
     * Habilitar Campos
     */
    function habilitarCamposAfterCid() {
        var valueTipologia = $('#AgendamentoCidId').val();
        if ($('#formBody').data('action') !== "deletar") {
            if (valueTipologia !== "") {
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
            } else {
                $('#AgendamentoUnidadeAtendimentoId').val('');
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
            }
        }
    }

    function existeAgendamento(botao) {
        var url = $(botao).data('url');
        url = url + 'validarUnicidadeAgendamentoAjax';
        var retorno = false;
         var encaixe = $('#hidEncaixe').is(':checkbox');
        $.ajax({
            url: url,
            type: "get",
            async: false,
            data: {
                unidade: $("#AgendamentoUnidadeAtendimentoId").val(),
                servidor: $("#hiddenServidorId").val(),
                tipologia: $("#comboTipologia").val(),
                data_hora: $("#AgendamentoDataHora").val(),
                id: $("#AgendamentoId").val(),
                encaixe: encaixe
            },
            dataType: "html",
            success: function (response) {
                var responseJson = $.parseJSON(response);
                if (responseJson.status == "danger") {
                    retorno = true;
                    $("#id_agendamento_vigente").val(responseJson.idAgendamentoVigente);
                }
            }
        });
        return retorno;
    }

    $('#btSave').click(function () {
        var tipologia_licenca_acompanhamento_familiar = parseInt($('#tipologia_licenca_acompanhamento_familiar').val());

        if ($('#comboTipologia').val() == tipologia_licenca_acompanhamento_familiar) {
            exibirAlertaTipologiaFamilia();
        } else {
            validarAgendamento(this);
        }
    });

    function validarAgendamento(campo) {
        // if($("#hidEncaixe").prop('checked') == false){
            if (existeAgendamento(campo)) {

                
                    var botao = campo;
                    $('#dialog-alerta-unicidade-agendamento-vigente').removeClass('displayNone');
                    $("div#dialog-alerta-unicidade-agendamento-vigente").dialog().prev().find(".ui-dialog-titlebar-close").hide();
                    $("#dialog-alerta-unicidade-agendamento-vigente").dialog({
                        resizable: false,
                        height: 190,
                        width: 490,
                        modal: true,
                        buttons: {
                            "Não": {
                                text: 'Não',
                                'class': 'btn fa estiloBotao btn-info float-right',
                                click: function () {
                                    $('#dialog-alerta-unicidade-agendamento-vigente').dialog("close");
                                }

                            },
                            "Sim": {
                                text: 'Sim',
                                'class': 'btn fa estiloBotao btn-info float-right',
                                click: function () {
                                    var url = $(botao).data('url');
                                    url = url + "editar/" + $("#id_agendamento_vigente").val();
                                    window.location.href = url;
                                }
                            }
                        }
                    });


            } else {
                $('#formBody').submit()
            }
        // }
    }

    /**
     * Dialog Tipologia Licença Familiar
     */
    function dialogTipologiaLicencaFamiliar() {
        //Dialog Submite
        $('#dialog-alerta-tipologia-licenca-familiar').removeClass('displayNone');
        $("div#dialog-alerta-tipologia-licenca-familiar").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-alerta-tipologia-licenca-familiar").dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: {
                "Cancelar": {
                    text: 'Cancelar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-alerta-tipologia-licenca-familiar').dialog("close");
                    }
                },
                "Ok": {
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        validarAgendamento($("#btSave"));
                    }
                }
            }
        });
    }

    function exibirAlertaTipologiaFamilia() {
        var name = $('#acompanhadoNomeSemAbreviacao').val();
        var dataNascimento = $('#AgendamentoDataNascimentoAcompanhado').val();
        var url = $('#btSave').data('url') + "validarAcompanhado";

        if (name !== "" && dataNascimento !== "") {
            $.ajax({
                url: url,
                type: "get",
                data: {
                    nome: name,
                    dataNascimento: dataNascimento
                },
                success: function (response) {
                    var responseJson = $.parseJSON(response);
                    if (responseJson) {
                        $('#dialog-alerta-acompanhado_licenca_familiar').removeClass('displayNone');
                        $("div#dialog-alerta-acompanhado_licenca_familiar").dialog().prev().find(".ui-dialog-titlebar-close").hide();
                        $("#dialog-alerta-acompanhado_licenca_familiar").dialog({
                            resizable: false,
                            height: 190,
                            width: 490,
                            modal: true,
                            buttons: {
                                "Cancelar": {
                                    text: 'Cancelar',
                                    'class': 'btn fa estiloBotao btn-info float-right',
                                    click: function () {
                                        $('#dialog-alerta-acompanhado_licenca_familiar').dialog("close");
                                    }
                                },
                                "Ok": {
                                    text: 'Ok',
                                    'class': 'btn fa estiloBotao btn-info float-right',
                                    click: function () {
                                        $('#dialog-alerta-acompanhado_licenca_familiar').dialog("close");
                                        dialogTipologiaLicencaFamiliar();
                                    }
                                }
                            }
                        });

                    } else {
                        dialogTipologiaLicencaFamiliar();
                    }
                }
            });
        } else {
            dialogTipologiaLicencaFamiliar();
        }

    }

    chefiaImediata();

    /**
     * Habilitação de campos
     */
    function chefiaImediata() {
        var orgaoOrigemUm = $('#ChefiaImediataUmOrgaoOrigemId').val();
        var orgaoOrigemDois = $('#ChefiaImediataDoisOrgaoOrigemId').val();
        var orgaoOrigemTres = $('#ChefiaImediataTresOrgaoOrigemId').val();

        if (orgaoOrigemUm === "") {
            $('#ChefiaImediataUmLotacao').attr('disabled', true);
            $('#chefiaImediataUm').attr('disabled', true);
            $('#chefiaImediataMatriculaUm').attr('disabled', true);
        }

        if (orgaoOrigemDois === "") {
            $('#ChefiaImediataDoisLotacao').attr('disabled', true);
            $('#chefiaImediataDois').attr('disabled', true);
            $('#chefiaImediataMatriculaDois').attr('disabled', true);
        }

        if (orgaoOrigemTres === "") {
            $('#ChefiaImediataTresLotacao').attr('disabled', true);
            $('#chefiaImediataTres').attr('disabled', true);
            $('#chefiaImediataMatriculaTres').attr('disabled', true);
        }

    }

    /**
     * Carregando lotação
     */
    $('.orgaoOrigemChefia').change(function () {
        var url = $('#baseUrlDefault').data('url') + 'getLotacoesOrgao/';
        var valueOrgaoOrigem = $(this).val();
        var chefia = $(this).data('chefia');

        $.ajax({
            url: url,
            type: "POST",
            data: {id: valueOrgaoOrigem},
            dataType: "html",
            success: function (response) {
                if (valueOrgaoOrigem != "") {
                    var responseJson = $.parseJSON(response);
                    $('#ChefiaImediata' + chefia + 'Lotacao').find('option').remove();
                    $('#ChefiaImediata' + chefia + 'Lotacao').append(new Option('Selecione', ''));
                    $.each(responseJson, function (index, valor) {
                        if (index != null) {
                            $('#ChefiaImediata' + chefia + 'Lotacao').append(new Option(valor, index));
                        }
                    });

                    $('#ChefiaImediata' + chefia + 'Lotacao').removeAttr('disabled');


                    limpaNomeMatriculaChefia(true, chefia);
                } else {
                    $('#ChefiaImediata' + chefia + 'Lotacao').find('option').remove();
                    $('#ChefiaImediata' + chefia + 'Lotacao').append(new Option('Selecione', ''));
                    $('#ChefiaImediata' + chefia + 'Lotacao').attr('disabled', true);
                    limpaNomeMatriculaChefia(true, chefia);
                }
            }
        });
    });


    /**
     * Ações de acordo com a lotação
     */
    $('.lotacaoChefia').change(function () {
        var valueLotacao = $(this).val();
        var chefia = $(this).data('chefia');
        if (valueLotacao !== "") {
            limpaNomeMatriculaChefia(false, chefia);
        } else {
            limpaNomeMatriculaChefia(true, chefia);
        }

    });

    /**
     * Limpar nome matricula da chefia
     * @param {str} limpar
     * @param {str} chefia
     */
    function limpaNomeMatriculaChefia(limpar, chefia) {
        if (limpar) {
            $('#hidChefeImediato' + chefia + 'Id').val('');
            $('#chefiaImediata' + chefia).val('');
            $('#chefiaImediata' + chefia).attr('disabled', true);
            $('#chefiaImediataMatricula' + chefia).val('');
            $('#chefiaImediataMatricula' + chefia).attr('disabled', true);
        } else {
            $('#hidChefeImediato' + chefia + 'Id').val('');
            $('#chefiaImediata' + chefia).val('');
            $('#chefiaImediata' + chefia).removeAttr('disabled');
            $('#chefiaImediataMatricula' + chefia).val('');
            $('#chefiaImediataMatricula' + chefia).removeAttr('disabled');
        }
    }


    /**
     * AUTOCOMPLETE DE ACORDO COM O NOME DO CHEFE
     */
    /**
     * Função para o AutoComplete de acordo com a lotação e órgão 
     */
    $("#chefiaImediataUm").autocomplete({
        source: function (request, response) {
            $('#hidChefeImediatoUmId').val("");
            $('#chefiaImediataMatriculaUm').val("");
            $.ajax({
                url: $('#baseUrlDefault').data('url') + 'getServidorByNome/',
                dataType: "json",
                data: {
                    nome: request.term,
                    orgao_origem_id: $('#ChefiaImediataUmOrgaoOrigemId').val(),
                    lotacao_id: $('#ChefiaImediataUmLotacao').val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 4,
        select: function (a, b) {
            if (b.item.id == $('#hidChefeImediatoDoisId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataUm').val('');
                $('#hidChefeImediatoUmId').val('');
                $('#chefiaImediataMatriculaUm').val("");
                return false;
            } else if (b.item.id == $('#hidChefeImediatoTresId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataUm').val('');
                $('#hidChefeImediatoUmId').val('');
                $('#chefiaImediataMatriculaUm').val("");
                return false;
            } else {
                $('#hidChefeImediatoUmId').val(b.item.id);
                $('#chefiaImediataMatriculaUm').val(b.item.matricula);
            }
        }
    });

    /**
     * Função para o AutoComplete de acordo com a lotação e órgão 
     */
    $("#chefiaImediataDois").autocomplete({
        source: function (request, response) {
            $('#hidChefeImediatoDoisId').val("");
            $('#chefiaImediataMatriculaDois').val("");
            $.ajax({
                url: $('#baseUrlDefault').data('url') + 'getServidorByNome/',
                dataType: "json",
                data: {
                    nome: request.term,
                    orgao_origem_id: $('#ChefiaImediataDoisOrgaoOrigemId').val(),
                    lotacao_id: $('#ChefiaImediataDoisLotacao').val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function (a, b) {
            if (b.item.id == $('#hidChefeImediatoUmId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataDois').val("");
                $('#hidChefeImediatoDoisId').val("");
                $('#chefiaImediataMatriculaDois').val("");
                return false;
            } else if (b.item.id == $('#hidChefeImediatoTresId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataDois').val("");
                $('#hidChefeImediatoDoisId').val("");
                $('#chefiaImediataMatriculaDois').val("");
                return false;
            } else {
                $('#hidChefeImediatoDoisId').val(b.item.id);
                $('#chefiaImediataMatriculaDois').val(b.item.matricula);
            }
        }
    });

    /**
     * Função para o AutoComplete de acordo com a lotação e órgão 
     */
    $("#chefiaImediataTres").autocomplete({
        source: function (request, response) {
            $('#hidChefeImediatoTresId').val("");
            $('#chefiaImediataMatriculaTres').val("");
            $.ajax({
                url: $('#baseUrlDefault').data('url') + 'getServidorByNome/',
                dataType: "json",
                data: {
                    nome: request.term,
                    orgao_origem_id: $('#ChefiaImediataTresOrgaoOrigemId').val(),
                    lotacao_id: $('#ChefiaImediataTresLotacao').val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function (a, b) {
            if (b.item.id == $('#hidChefeImediatoUmId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataTres').val("");
                $('#hidChefeImediatoTresId').val("");
                $('#chefiaImediataMatriculaTres').val("");
                return false;
            } else if (b.item.id == $('#hidChefeImediatoDoisId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataTres').val("");
                $('#hidChefeImediatoTresId').val("");
                $('#chefiaImediataMatriculaTres').val("");
                return false;
            } else {
                $('#hidChefeImediatoTresId').val(b.item.id);
                $('#chefiaImediataMatriculaTres').val(b.item.matricula);
            }
        }
    });



    /**
     * AUTOCOMPLETE DE ACORDO COM A MATRICULA DO CHEFE
     */
    /**
     * Função para o AutoComplete de acordo com a lotação e órgão 
     */
    $("#chefiaImediataMatriculaUm").autocomplete({
        source: function (request, response) {
            $('#chefiaImediataUm').val('');
            $('#hidChefeImediatoUmId').val('');
            $.ajax({
                url: $('#baseUrlDefault').data('url') + 'getServidorByMatricula/',
                dataType: "json",
                data: {
                    nome: request.term,
                    orgao_origem_id: $('#ChefiaImediataUmOrgaoOrigemId').val(),
                    lotacao_id: $('#ChefiaImediataUmLotacao').val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
//        minLength: 3,
        select: function (a, b) {
            if (b.item.id == $('#hidChefeImediatoDoisId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataUm').val('');
                $('#hidChefeImediatoUmId').val('');
                return false;
            } else if (b.item.id == $('#hidChefeImediatoTresId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataUm').val('');
                $('#hidChefeImediatoUmId').val('');
                return false;
            } else {
                $('#hidChefeImediatoUmId').val(b.item.id);
                $('#chefiaImediataUm').val(b.item.nome);
            }
        }
    });

    /**
     * Função para o AutoComplete de acordo com a lotação e órgão 
     */
    $("#chefiaImediataMatriculaDois").autocomplete({
        source: function (request, response) {
            $('#chefiaImediataDois').val("");
            $('#hidChefeImediatoDoisId').val("");
            $.ajax({
                url: $('#baseUrlDefault').data('url') + 'getServidorByMatricula/',
                dataType: "json",
                data: {
                    nome: request.term,
                    orgao_origem_id: $('#ChefiaImediataDoisOrgaoOrigemId').val(),
                    lotacao_id: $('#ChefiaImediataDoisLotacao').val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
//        minLength: 3,
        select: function (a, b) {
            if (b.item.id == $('#hidChefeImediatoUmId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataDois').val("");
                $('#hidChefeImediatoDoisId').val("");
                return false;
            } else if (b.item.id == $('#hidChefeImediatoTresId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataDois').val("");
                $('#hidChefeImediatoDoisId').val("");
                return false;
            } else {
                $('#hidChefeImediatoDoisId').val(b.item.id);
                $('#chefiaImediataDois').val(b.item.nome);
            }
        }
    });

    /**
     * Função para o AutoComplete de acordo com a lotação e órgão 
     */
    $("#chefiaImediataMatriculaTres").autocomplete({
        source: function (request, response) {
            $('#chefiaImediataTres').val("");
            $('#hidChefeImediatoTresId').val("");
            $.ajax({
                url: $('#baseUrlDefault').data('url') + 'getServidorByMatricula/',
                dataType: "json",
                data: {
                    nome: request.term,
                    orgao_origem_id: $('#ChefiaImediataTresOrgaoOrigemId').val(),
                    lotacao_id: $('#ChefiaImediataTresLotacao').val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
//        minLength: 3,
        select: function (a, b) {
            if (b.item.id == $('#hidChefeImediatoUmId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataTres').val("");
                $('#hidChefeImediatoTresId').val("");
                return false;
            } else if (b.item.id == $('#hidChefeImediatoDoisId').val()) {
                generateGrow('Já temos essa mesma chefia ligada à este agendamento.', 'danger');

                $('#chefiaImediataTres').val("");
                $('#hidChefeImediatoTresId').val("");
                return false;
            } else {
                $('#hidChefeImediatoTresId').val(b.item.id);
                $('#chefiaImediataTres').val(b.item.nome);
            }
        }
    });

});
/** Fim do arquivo **/