/**
 * configuracaoPadrao() - CONFIGURACAO PADRAO PARA TODOS OS CAMPOS APOS SELECIONADA UMA TIPOLOGIA
 * tratarRegrasTipologia() - EXECUTA configuracaoPadrao()
 * **/
jQuery(function ($) {
    var TIPOLOGIAS = {};
    TIPOLOGIAS.licenca_maternidade = parseInt($('#tipologia_licenca_maternidade').val());
    TIPOLOGIAS.licenca_maternida_aborto = parseInt($('#tipologia_licenca_maternidade_aborto').val());
    TIPOLOGIAS.licenca_natimorto = parseInt($('#tipologia_licenca_natimorto').val());
    TIPOLOGIAS.aposentadoria_invalidez = parseInt($('#tipologia_aposentadoria_invalidez').val());
    TIPOLOGIAS.antecipacao_licenca = parseInt($('#tipologia_atencipacao_licenca').val());
    TIPOLOGIAS.isencao_contribuicao_previdenciaria = parseInt($('#tipologia_isencao_contribuicao_previdenciaria').val());
    TIPOLOGIAS.reversao_aposentadoria_invalidez = parseInt($('#tipologia_reversao_aposentadoria_invalidez').val());
    TIPOLOGIAS.avaliacao_habilitacao_dependentes = parseInt($('#tipologia_avaliacao_habilitacao_dependentes	').val());
    TIPOLOGIAS.pcd = parseInt($('#tipologia_pcd').val());
    TIPOLOGIAS.admissao_pensionista_maior_invalido = parseInt($('#tipologia_admissao_pensionista_maior_invalido').val());
    TIPOLOGIAS.informacao_para_seguro_compreensivo_habitacional = parseInt($('#tipologia_informacao_seguro_compreensivo_habitacional').val());
    TIPOLOGIAS.readaptacao_funcao = parseInt($('#tipologia_readaptacao_funcao').val());
    TIPOLOGIAS.remanejamento_funcao = parseInt($('#tipologia_remanejamento_funcao').val());
    TIPOLOGIAS.remocao = parseInt($('#tipologia_remocao').val());
    TIPOLOGIAS.risco_vida_insalubridade = parseInt($('#tipologia_risco_vida_insalubridade').val());
    TIPOLOGIAS.recurso_administrativo = parseInt($('#tipologia_recurso_administrativo').val());
    TIPOLOGIAS.exame_pre_admissional = parseInt($('#tipologia_exame_pre_admissional').val());
    TIPOLOGIAS.licenca_acompanhamento_familiar = parseInt($('#tipologia_licenca_acompanhamento_familiar').val());
    TIPOLOGIAS.licenca_medica_tratamento_saude = parseInt($('#tipologia_licenca_medica_tratamento_saude').val());
    TIPOLOGIAS.designacao_de_assistente_tecnico = parseInt($('#tipologia_designacao_de_assistente_tecnico').val());
    TIPOLOGIAS.comunicacao_de_acidente_de_trabalho = parseInt($('#tipologia_comunicacao_de_acidente_de_trabalho').val()); //CAT
    TIPOLOGIAS.sindicancia_inquerito_pad = parseInt($('#tipologia_sindicancia_inquerito_pad').val());
    TIPOLOGIAS.aposentadoria_especial = parseInt($('#tipologia_aposentadoria_especial').val());
    TIPOLOGIAS.inspecao = parseInt($('#tipologia_inspecao').val());

    var VINCULO = {};
    VINCULO.CTD = parseInt($('#vinculo_ctd').val());
    VINCULO.CLT = parseInt($('#vinculo_clt').val());
    VINCULO.ESTATUTARIO = parseInt($('#vinculo_estatutario').val());

    var TIPO_ISENCAO = {};
    TIPO_ISENCAO.servidor = parseInt($('#tipo_isencao_servidor').val());
    TIPO_ISENCAO.pensionista = parseInt($('#tipo_isencao_pensionista').val());


    var endServidor = {};
    var firstLoadEndereco = true;
    var peritoCredenciado = $('#usuario_perito_credenciado').val();
    var peritoServidor = $('#usuario_perito_servidor').val();
    var interno = $('#usuario_interno').val();
    var servidor = $('#usuario_servidor').val();
    camposDisponiveis($('#tipoUsuarioLogado').val(), $('#is_homologa').val());

    $('#AgendamentoProcessoAdministrativo').change(function (){
        var id = $('#hiddenServidorId').val();
        if (id==""){
            alertaDialog("É preciso escolher quem é o servidor da solicitação.");
            $('#AgendamentoProcessoAdministrativo').val('');
            return;
        }
        if($(this).val() == 1){
            $('.n-pad').removeClass('displayNone');
            alertaPAD();
        }else{
            $('.n-pad').addClass('displayNone');
            $('#numero_pad').val('');
            $('#label_pad_selecionado').addClass('displayNone');
            $('.n-pad input').val('');
        }
    });
    //$('.itemCid:first').siblings().remove().andSelf().find("input").val("")

    if (cidsSelecionados().length > 0) {

        if($("#cpfServidor").prop("disabled")){
            $('#AgendamentoAtendimentoDomiciliar').attr('disabled', true);
        }else{
            $('#AgendamentoAtendimentoDomiciliar').attr('disabled', false);
        }
    }

    if ($("#AgendamentoAtendimentoDomiciliar").is(':checked')) {

        if($("#cpfServidor").prop("disabled")){
            $('#AgendamentoAtendimentoDomiciliar').attr('disabled', true);
        }else{
            $('#AgendamentoAtendimentoDomiciliar').attr('disabled', false);
        }

        $('#RowAtendUnidadeProxima').show();
        var atendUndProxima = $('#AtendUnidadeProxima').val();
        if (atendUndProxima == "1") {
            $("#RowMunicipio").show();
            var municipioUnidade = $("#MunicipioAtendimento").val();

            if (municipioUnidade != "") {
                $("#RowUnidadeAtendimento").show();
            } else {
                $("#RowUnidadeAtendimento").hide();
            }
        } else {
            $("#RowMunicipio").hide();
            $("#RowUnidadeAtendimento").hide();
            $("#RowEnderecoAtendimento").show();

            if ($("#AtendEndereco").val() != "") {
                $("#RowCadastroEnderecoAtendimento").show();
            }
        }
    } else {
        $('#RowAtendUnidadeProxima').hide();
        $('#AtendUnidadeProxima').val("");

        $('#RowMunicipio').hide();
        $('#MunicipioAtendimento').val("");

        $('#comboTipologia').change();
    }

    $("#AgendamentoAtendimentoDomiciliar").click(function () {
        mudancaCid();
    });

    $('#AtendUnidadeProxima').change(function () {
        var url = $('#MunicipioAtendimento').attr('url-data') + 'getUnidadeCidMunicipio';
        var valorAtendUndProx = $('#AtendUnidadeProxima').val();


        if (valorAtendUndProx == "1") {
            limpaCamposEndereco();
            $('#MunicipioAtendimento option').each(function () {
                if ($(this).val() == endServidor.municipio_id) {
                    $('#MunicipioAtendimento').val(endServidor.municipio_id);
                    $('#MunicipioAtendimento').change();
                }
            });
            $("#RowMunicipio").show();
            $('#RowEnderecoAtendimento').hide();
            $("#RowCadastroEnderecoAtendimento").hide();

        } else if (valorAtendUndProx == "0") {
            // alert('Não');
            $("#MunicipioAtendimento").val("");
            $("#AtendEndereco").val("");

            $("#RowEnderecoAtendimento").show();
            $("#RowMunicipio").hide();
            $("#AgendamentoUnidadeAtendimentoId").val("");

            $("#RowUnidadeAtendimento").hide();
        } else if (valorAtendUndProx == "") {
            $("#RowCadastroEnderecoAtendimento").hide();
            $('#MunicipioAtendimento').val("");
            $("#RowMunicipio").hide();
            $('#RowUnidadeAtendimento').hide();
            limpaCamposEndereco();
            $("#MunicipioAtendimento").val("");
        }


    });

    $('#MunicipioAtendimento').change(function () {
        // alert($('#MunicipioAtendimento').val());
        var url = $('#MunicipioAtendimento').attr('url-data') + 'getUnidadeCidMunicipio';

        var municipioAtend = $('#MunicipioAtendimento').val();
        var atendimento_domicilio = 1;
        var municipio_proximo = 1;
        $.ajax({
            url: url,
            type: "POST",
            data: {
                municipio: municipioAtend,
                cids: cidsSelecionados(),
                atendimento_domicilio: atendimento_domicilio,
                municipio_proximo: municipio_proximo,
                idTipologia: $('#comboTipologia').val()
            },
            dataType: "html",
            success: function (response) {
                var options = "";
                options += "<option value=''>Selecione</option>"
                var objResponse = $.parseJSON(response);

                $.each(objResponse, function (key, value) {
                    options += '<option value="' + key + '">' + value + '</option>';

                    //console.log("Chave = " + key);
                    //console.log("Valor = " + value);

                });
                // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);

                if($("#cpfServidor").prop("disabled")){
                    $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
                }else{
                    $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
                }


                $("#AgendamentoUnidadeAtendimentoId").html(options);
                $("#RowUnidadeAtend").show();
                $("#RowUnidadeAtendimento").show();

            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{
                    generateGrow('Problemas ao consultar Unidades de Atendimento.', 'danger');
                }
            }
        });

    });

    $('#AtendEndereco').change(function (){
        var url = $('#MunicipioAtendimento').attr('url-data') + 'retornaUnidAtendimentoMunicipio';
        var valorAtendEndereco = $('#AtendEndereco').val();

        
        if (valorAtendEndereco == "1") {
            var cpfServidor = $("#cpfServidor").val();
            if (cpfServidor != "") {
                var setInputsEnderecoServidor = function () {
                    $("#EnderecoAtendimentoDomicilioId").val(endServidor.id);
                    $("#EnderecoAtendimentoDomicilioCep").val(endServidor.cep);
                    $("#EnderecoAtendimentoDomicilioLogradouro").val(endServidor.logradouro);
                    $("#EnderecoAtendimentoDomicilioNumero").val(endServidor.numero);
                    $("#EnderecoAtendimentoDomicilioComplemento").val(endServidor.complemento);
                    $("#EnderecoAtendimentoDomicilioBairro").val(endServidor.bairro);
                    $("#EnderecoAtendimentoDomicilioEstadoId").val(endServidor.estado_id);
                    $("#EnderecoAtendimentoDomicilioMunicipioId").val(endServidor.municipio_id);

                    if($("#EnderecoAtendimentoDomicilioMunicipioId").val() == ""){
                        generateGrow('Servidor não possui município cadastrado no endereço. Não será possível marcar atendimento domiciliar em sua residência.', 'danger');
                    }

                    $("#EnderecoAtendimentoDomicilioCep").attr('disabled', true);
                    $("#EnderecoAtendimentoDomicilioLogradouro").attr('disabled', true);
                    $("#EnderecoAtendimentoDomicilioNumero").attr('disabled', true);
                    $("#EnderecoAtendimentoDomicilioComplemento").attr('disabled', true);
                    $("#EnderecoAtendimentoDomicilioBairro").attr('disabled', true);
                    $("#EnderecoAtendimentoDomicilioEstadoId").attr('disabled', true);
                    $("#EnderecoAtendimentoDomicilioMunicipioId").attr('disabled', true);

                    $("#RowCadastroEnderecoAtendimento").show();
                    if (firstLoadEndereco) {
                        firstLoadEndereco = false;
                        if ($('#comboDiaSemana').val() !== "") {
                            $('#comboDiaSemana').change();
                        }
                    }
                };
                if (endServidor.id) {
                    setInputsEnderecoServidor();
                } else {
                    getEnderecoServidor(cpfServidor, true, setInputsEnderecoServidor);
                }
            } else {
                $("#RowCadastroEnderecoAtendimento").hide();
                generateGrow('É preciso selecionar pelos menos um usuário.', 'danger');
                $('#AtendEndereco').val("");
                $("#nomeServidor").focus();
                limpaCamposEndereco();
            }
        } else if (valorAtendEndereco == "0") {
            $("#RowCadastroEnderecoAtendimento").show();
            if (!firstLoadEndereco)limpaCamposEndereco();
        } else if (valorAtendEndereco == "") {
            $('#MunicipioAtendimento').val("");
            $("#RowMunicipio").hide();
            $("#RowCadastroEnderecoAtendimento").hide();
            limpaCamposEndereco();
        }
    });


    function limpaCamposEndereco() {
        $("#EnderecoAtendimentoDomicilioCep").val('');
        $("#EnderecoAtendimentoDomicilioLogradouro").val('');
        $("#EnderecoAtendimentoDomicilioNumero").val('');
        $("#EnderecoAtendimentoDomicilioComplemento").val('');
        $("#EnderecoAtendimentoDomicilioBairro").val('');
        $("#EnderecoAtendimentoDomicilioEstadoId").val('');
        $("#EnderecoAtendimentoDomicilioMunicipioId").val('');

        $("#EnderecoAtendimentoDomicilioCep").attr('disabled', false);
        $("#EnderecoAtendimentoDomicilioLogradouro").attr('disabled', false);
        $("#EnderecoAtendimentoDomicilioNumero").attr('disabled', false);
        $("#EnderecoAtendimentoDomicilioComplemento").attr('disabled', false);
        $("#EnderecoAtendimentoDomicilioBairro").attr('disabled', false);
        $("#EnderecoAtendimentoDomicilioEstadoId").attr('disabled', false);
        $("#EnderecoAtendimentoDomicilioMunicipioId").attr('disabled', false);
    }

    /**
     * Função que habilita e desabilita os campos da tela
     * @param {int} tipoUsuario
     */
    function camposDisponiveis(tipoUsuario, homologa) {
        homologa = homologa || false;
        if (tipoUsuario == interno || homologa) {
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

    function getEnderecoServidor(cpfServidor, showError, callback) {
        url = $('#MunicipioAtendimento').attr('url-data') + 'consultaEnderecoUsuario';
        $.ajax({
            url: url,
            type: "POST",
            data: {cpfServidor: cpfServidor},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                endServidor = objResponse;
                if (callback instanceof Function)callback();
            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{
                    if (showError) generateGrow('Problemas ao consultar Endereço do Usuário.', 'danger');
                }
            }
        });
    }

    function carregarDadosAcidentadoAgendamento(idServidor, showError, callback){
        url = $('#baseUrlDefault').attr('data-url') + 'carregarDadosAcidentadoAgendamento';
         $.ajax({
            url: url,
            type: "POST",
            data: {idServidor: idServidor},
            dataType: "html",
            success: function (response) {
                var responseJson = $.parseJSON(response);
                if(responseJson instanceof  Array && responseJson.length == 0)return;
                $("#divAcidentado div").remove();
                var divAcidentado = $("#divAcidentado");
				
				// $("#divDadosAcidentado div").remove();
                //var divDadosAcidentado = $("#divDadosAcidentado");
				
				
				
              //  console.log(responseJson[0]);

                $('#nomeServidorAcidentado').val(responseJson[0].Usuario.nome);
                $('#matriculaServidorAcidentado').val(responseJson[0].Vinculo.matricula);
                $('#dataNascimentoServidorAcidentado').val(responseJson[0].Usuario.data_nascimento);
                $('#CPFServidorAcidentado').val(responseJson[0].Usuario.cpf);
                $('#RGServidorAcidentado').val(responseJson[0].Usuario.rg);
                $('#sexoServidorAcidentado').val(responseJson[0].Sexo.nome);
                $('#estadoCivilServidorAcidentado').val(responseJson[0].EstadoCivil.nome);
				$('#escolaridadeServidorAcidentado').val(responseJson[0].Escolaridade.nome);
				
				
				var html = "";
                $.each(responseJson, function (key, objAcidentado) {

                    html+=("" +
                    "<div class='col-md-12'> <fieldset class='scheduler-border'>" +
                        "<legend class='scheduler-border'>Lotação "+ (key+1)+"</legend>"+

                            "<div class='row'>"+
                                "<div class='col-md-6'>"+
                                     "<div class='form-group'>"+
                                         "<label for='lotacao_orgao_cat'>Lotação</label>"+
                                             "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objAcidentado.Lotacao.nome || '') +"'>" +
                                     "</div>"+
                                "</div>"+
                                 "<div class='col-md-6'>"+
                                     "<div class='form-group'>"+
                                        "<label for='lotacao_orgao_cat'>Cargo</label>"+
                                            "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objAcidentado.Cargo.nome || '') +"'>"+
                                     "</div>"+
                                 "</div>"+
                             "</div>"+
                            "<div class='row'>"+
                                "<div class='col-md-8'>"+
                                    "<div class='form-group'>"+
                                        "<label for='lotacao_orgao_cat'>Função</label>"+
                                            "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objAcidentado.Funcao.nome || '') +"'>" +
                                    "</div>"+
                                "</div>"+
                                "<div class='col-md-4'>"+
                                    "<div class='form-group'>"+
                                        "<label for='lotacao_orgao_cat'>Tempo de serviço na função</label>"+
                                            "<input disabled='disabled' class='form-control' maxlength='150' type='text'>"+
                                    "</div>"+
                                "</div>"+
                                "</div>"+
                                "<div class='row'>"+
                                    "<div class='col-md-3'>"+
                                         "<div class='form-group'>"+
                                             "<label for='lotacao_orgao_cat'>Horário de trabalho entrada</label>"+
                                                "<input disabled='disabled' class='form-control' maxlength='150' type='text' >" +
                                         "</div>"+
                                    "</div>"+
                                    "<div class='col-md-3'>"+
                                        "<div class='form-group'>"+
                                            "<label for='lotacao_orgao_cat'>Horário de trabalho saída</label>"+
                                                 "<input disabled='disabled' class='form-control' maxlength='150' type='text'>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                        "</div>");
                });
				
                divAcidentado.append(html);




                $("#servidorNome").val(responseJson[0].Usuario.nome);
                $("#hidDataNascimento").val(responseJson[0].Usuario.data_nascimento);
                $("#hidCPF").val(responseJson[0].Usuario.cpf);
                $("#hidRG").val(responseJson[0].Usuario.rg);
                $("#hidSexo").val(responseJson[0].Sexo.nome);
                $("#hidEstadoCivil").val(responseJson[0].EstadoCivil.nome);
                //$("#hidLotacao").val(responseJson[0].lotacao.nome);
               // $("#hidCargo").val(responseJson[0].Cargo.nome);
               // $("#hidFuncao").val(responseJson[0].funcao.nome);
                 if (callback instanceof Function)callback();
            },
            error: function (response, status, error) {
                 if(error == 'Forbidden'){
                     window.location.reload();
                 }else{
                     if (showError) generateGrow('Problemas ao consultar Dados do Usuário.', 'danger');
                 }
            }
        });
    }



    function carregarDadosOrgaoServidor(idServidor, showError, callback){
        url = $('#baseUrlDefault').attr('data-url') + 'carregarDadosOrgaoServidor';
        $.ajax({
            url: url,
            type: "POST",
            data: {idServidor: idServidor},
            dataType: "html",
            success: function (response) {
                var objResponse = $.parseJSON(response);
                if(objResponse instanceof  Array && objResponse.length == 0)return;
                $(".divOrgao div").remove();
                var divOrgao = $(".divOrgao");
                var html = "";
				console.log('objResponse',objResponse);
                $.each(objResponse, function (key, objOrgao) {
                    html+=("<div class='col-md-12'> <fieldset class='scheduler-border'>" +
                                        "<legend class='scheduler-border'>Orgão "+ (key+1)+"</legend>"+
                                        "<div class='row'>"+
                                            "<div class='col-md-4'>"+
                                                "<div class='form-group'>"+
                                                    "<label for='lotacao_orgao_cat'>Lotação</label>"+
                                                        "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objOrgao.lotacao.nome || '')+"'>"+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='col-md-4'>"+
                                                    "<div class='form-group'>"+
                                                        "<label for='lotacao_orgao_cat'>Órgão onde é lotado o acidentado</label>"+
                                                        "<input disabled='disabled' class='form-control' maxlength='150' type='text'  value='"+(objOrgao.orgaoOrigem.orgao_origem|| '')+"'>"+
                                                    "</div>"+
                                            "</div>"+
                                            "<div class='col-md-4'>"+
                                                 "<div class='form-group'>"+
                                                    "<label for='lotacao_orgao_cat'>CNPJ</label>"+
                                                        "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objOrgao.orgaoOrigem.cnpj|| '') +"'>"+
                                                "</div>"+
                                            "</div>"+
                                        "</div>"+
                                        "<div class='row'>"+
                                            "<div class='col-md-12'>"+
                                                "<div class='form-group'>"+
                                                          "<label for='lotacao_orgao_cat'>Endereço: Rua/Av/Nº</label>"+
                                                             "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objOrgao[0].logradouro || '') +"'>"+
                                                 "</div>"+
                                            "</div>"+
                                        "</div>"+
                                        "<div class='row'>"+
                                                "<div class='col-md-4'>"+
                                                     "<div class='form-group'>"+
                                                            "<label for='lotacao_orgao_cat'>Bairro</label>"+
                                                                 "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objOrgao.endereco.bairro || '') +"'>" +
                                                    "</div>"+
                                                "</div>"+
                                                "<div class='col-md-4'>"+
                                                     "<div class='form-group'>"+
                                                         "<label for='lotacao_orgao_cat'>Município</label>"+
                                                                "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objOrgao.municipio.nome || '') +"'>" +
                                                    "</div>"+
                                                "</div>"+
                                                 "<div class='col-md-4'>"+
                                                     "<div class='form-group'>"+
                                                        "<label for='lotacao_orgao_cat'>Fone</label>"+
                                                            "<input disabled='disabled' class='form-control' maxlength='150' type='text' value='"+(objOrgao.lotacao.telefone || '') +"'>"+
                                                     "</div>"+
                                                "</div>"+
                                        "</div>"+
                                        "</div>");
                });

                divOrgao.append(html);

                if (callback instanceof Function)callback();
            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{
                    if (showError) generateGrow('Problemas ao consultar Dados do Usuário.', 'danger');
                }
            }
        });
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
        $("#hidDataNascimento").val('');
        $("#hidCPF").val('');
        $("#hidRG").val('');
        $("#hidSexo").val('');
        $("#hidEstadoCivil").val('');
        $("#hidLotacao").val('');
        $("#hidCargo").val('');
        $("#hidFuncao").val('');
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
                        getEnderecoServidor(cpf, false, function(){
                            if($('#AtendEndereco').val() == 1){
                                $('#AtendEndereco').change();
                            }
                        });

						carregarDadosAcidentadoAgendamento(responseJson.id);
                        carregarDadosOrgaoServidor(responseJson.id);
                    }
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{
                        if (showError) generateGrow('Problemas ao consultar Dados do Usuário.', 'danger');
                    }
                }
            });
        }
    });

    $("#cpfPerito").on("keyup", function (e) {
        $('#nomePerito').val('');
        $('#hiddenPeritoId').val('');
        var url = $('#baseUrlDefault').data('url') + 'getServidorCpf/perito/'+TIPOLOGIAS.designacao_de_assistente_tecnico;
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
                        $('#hiddenPeritoId').val(responseJson.id);
                        $('#nomePerito').val(responseJson.nome);
                    }
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{
                        if (showError) generateGrow('Problemas ao consultar Dados do Usuário.', 'danger');
                    }
                }
            });
        }
    });

    $("#nomePerito").autocomplete({
        source: $('#baseUrlDefault').data('url') + 'getServidorNome/perito/'+TIPOLOGIAS.designacao_de_assistente_tecnico,
        minLength: 4,
        open: function (eventi, ui) {
            $('#cpfPerito').val('');
            $('#hiddenPeritoId').val('');
        },
        select: function (a, b) {
            $('#cpfPerito').val(b.item.cpf);
            $('#hiddenPeritoId').val(b.item.id);
            aplicarMascaraCpf();
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

    $('body').on('change', '#AgendamentoVinculo', function () {
        $vinculoSelecionado =  parseInt($(this).val());
        $hasVinculoInfo = false;
        if($vinculoSelecionado === VINCULO.CTD){
            $("#fileContratoTrabalho").removeClass('displayNone');
            $hasVinculoInfo = true;
        }else{
            $("#fileContratoTrabalho").addClass('displayNone');
        }
        if($vinculoSelecionado == VINCULO.CLT){
            $hasVinculoInfo = true;
        }
        if($hasVinculoInfo){
            $("#div-vinculo_infos").removeClass('displayNone');
        }else{
            $("#div-vinculo_infos").addClass('displayNone');
        }
    });

    $('body').on('change', '#AgendamentoCursoFormacao', function () {
        $cursoFormacaoSelecionado =  parseInt($(this).val());
        if($cursoFormacaoSelecionado === 1){ //sim
            $("#fileCursoFormacao").removeClass('displayNone');
        }else{
            $("#fileCursoFormacao").addClass('displayNone');
        }
    });

    $('body').on('change', '#AgendamentoTreinamentoDesvioFuncao', function () {
        $treinamentoFormacaoSelecionado =  parseInt($(this).val());
        if($treinamentoFormacaoSelecionado === 1){ //sim
            $("#selectedTreinamentoDesvioFuncao").removeClass('displayNone');
        }else{
            $("#selectedTreinamentoDesvioFuncao").addClass('displayNone');
        }
    });


    $('body').on('change', '.carregarHorarios', function () {
        var url = $(this).data('url');
        var idTipologia = $('#comboTipologia').val();
        var idUnidade = $('#AgendamentoUnidadeAtendimentoId').val();
        var idDiaSemana = $('#comboDiaSemana').val();
        var data_apartir_de = $('#AgendamentoDataAPartir').val();
        var encaixe = $(this).is(':checkbox');

        var atendiDomiciliar = $('#AgendamentoAtendimentoDomiciliar').prop('checked');
        var atendUndProxima = $('#AtendUnidadeProxima').val();
        var municipio = $('#MunicipioAtendimento').val();

        var municipioEndereco = $("#EnderecoAtendimentoDomicilioMunicipioId").val();
        var atendEndereco = $("#AtendEndereco").val();

        var isOk = false;
        if (idTipologia != "" && idUnidade != "" && idDiaSemana != "") {
            isOk = true;
        }

        if (atendiDomiciliar == true && atendUndProxima == "1" && municipio != "" && idUnidade != null && idDiaSemana != "") {
            url = $('#baseUrlDefault').data('url') + 'carregarHorariosAtendimentoDomicilio';
            isOk = true;
        } else if (municipioEndereco != "" && atendUndProxima === "0" && atendEndereco != ""  && idDiaSemana != "") {
            url = $('#baseUrlDefault').data('url') + 'carregarHorariosAtendimentoMunicipioEndereco';
            isOk = true;
        }

        if (isOk) {
            consultarHorarios(idTipologia, idUnidade, idDiaSemana, url, data_apartir_de, encaixe, atendiDomiciliar, municipioEndereco, cidsSelecionados());
        }
    });

    $('#EnderecoAtendimentoDomicilioMunicipioId').change(function(){
        $('#comboDiaSemana').val('');
        $('#AgendamentoDataHora').val('').html('');
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

    function consultarHorarios(idTipologia, idUnidade, idDiaSemana, url, data_apartir_de, encaixe, atendiDomiciliar, municipioEndereco, cids) {
        $.ajax({
            url: url,
            type: "POST",
            data: {
                tipologia_id: idTipologia,
                unidade_id: idUnidade,
                dia_semana: idDiaSemana,
                data_inicial: data_apartir_de,
                municipio_id: municipioEndereco,
                checkEncaixe: $('.checkEncaixe').prop('checked'),
                cids:cids

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
                    var adicionado = false;
                    window.globalHoraAg = horaAgendamento;
                    $.each(responseJson, function (index, valor) {
                        if (valor != null) {
                            if(valor <= horaAgendamento){
                                $('#AgendamentoDataHora').append(new Option(valor, valor));
                                if (horaAgendamento == valor) {
                                    $('#AgendamentoDataHora').val(valor);
                                    existeHorario = true;
                                }
                            }else{
                                if(!adicionado && !existeHorario){
                                    adicionado = true;
                                    if($.trim(horaAgendamento) != ""){
                                        $('#AgendamentoDataHora').append(new Option(horaAgendamento, horaAgendamento));
                                    }
                                }
                                if($.trim(valor) != ""){
                                    $('#AgendamentoDataHora').append(new Option(valor, valor)); 
                                }
                            }
                        }
                    });
                    if (!adicionado && !existeHorario && $('#AgendamentoId').val()) {
                        $('#AgendamentoDataHora').append(new Option(horaAgendamento, horaAgendamento));
                    }
                    if(horaAgendamento){
                        $('#AgendamentoDataHora').val(horaAgendamento);
                    }
                } else if (responseJson.length == 0) {

                    if(!$("#cpfServidor").prop("disabled")){
                        var errorMsg = 'Não existem horários disponíveis';
                        if (atendiDomiciliar) {
                            errorMsg = 'Não existem horários disponíveis ou essa Tipologia não tem atendimento em domicílio';
                        }
                        generateGrow(errorMsg, 'danger');
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
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{
                    if (showError) generateGrow('Problemas ao consultar horário.', 'danger');
                }
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
            $("#hidDataNascimento").val('');
            $("#hidCPF").val('');
            $("#hidRG").val('');
            $("#hidSexo").val('');
            $("#hidEstadoCivil").val('');
            $("#hidLotacao").val('');
            $("#hidCargo").val('');
            $("#hidFuncao").val('');
        },
        select: function (a, b) {
            $('#cpfServidor').val(b.item.cpf);
            $('#hiddenServidorId').val(b.item.id);
            $('#data_obito_servidor').val(b.item.data_obito);
            aplicarMascaraCpf();
            carregarLicencasConcedidas(true);
            getEnderecoServidor(b.item.cpf, false, function(){
                if($('#AtendEndereco').val() == 1){
                    $('#AtendEndereco').change();
                }
            });
            carregarDadosAcidentadoAgendamento(b.item.id);
            carregarDadosOrgaoServidor(b.item.id);

        }
    });

    /**
     * Habilitar campos Tipologia
     */

    function habilitarCamposTipologia() {
        if ($('#comboTipologia').val() != "") {
            $('#afterSolicitarLicenca').removeClass('displayNone');
        } else {
            $('#afterSolicitarLicenca').addClass('displayNone');
        }
        esconderDuranteApartirDe(false);
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

    // CAT
    $('body').on('change', '#registro_policial_cat', function () {
        var rpi = $(this).val();
        if (rpi ==1) {
            $('#anexo_registro_policial').removeClass('displayNone');
        } else {
            $('#anexo_registro_policial').addClass('displayNone');
        }
    });

    $('body').on('change', '#assistencia_medica_hospitalar_acidente_doenca', function () {
        var rpi = $(this).val();
        if (rpi ==1) {
            $('#local_assistencia_medica_hospitalar').removeClass('displayNone');
        } else {
            $('#local_assistencia_medica_hospitalar').addClass('displayNone');
        }
    });

    $('body').on('change', '#testemunha_acidente_doenca', function () {
        var rpi = $(this).val();
        if (rpi ==1) {
            $('.dvtestemunha_acidente').removeClass('displayNone');
        } else {
            $('.dvtestemunha_acidente').addClass('displayNone');
        }
    });

    $('body').on('change', '#chkbxPlantonista', function () {
        if($('#chkbxPlantonista').is(":checked")){
            $('#div_expediente_plantonista').removeClass('displayNone');
        }else{
            $('#div_expediente_plantonista').addClass('displayNone');
            $('#expediente_plantonista').val('');
            $('#div_outro').addClass('displayNone');

        }       
    
    });

    $('body').on('change', '#expediente_plantonista', function () {
        var rpi = $(this).val();
        if (rpi == 4) {
            $('#div_outro').removeClass('displayNone');
        } else {
            $('#div_outro').addClass('displayNone');
        }
    });


    if($('#chkbxPlantonista').is(":checked")){
        $('#div_expediente_plantonista').removeClass('displayNone');
        if($('#expediente_plantonista').val() == 4){
            $('#div_outro').removeClass('displayNone');
        }
    }  

    $('#chkbxPlantonista').on('change', function() {
        $('#chkbxDiarista').not(this).prop('checked', false);  
    });

    $('#chkbxDiarista').on('change', function() {
        $('#chkbxPlantonista').not(this).prop('checked', false);
        $('#div_expediente_plantonista').addClass('displayNone');
        $('#expediente_plantonista').val('');
        $('#div_outro').addClass('displayNone');
    });


    tratarRegrasTipologia(true);
    $('body').on('change', '#comboTipologia', function () {
        //Habilitando campos de acordo com a tipologia.
        habilitarCamposTipologia();
        //Aplicando regras da tipologia
        tratarRegrasTipologia();
        mudancaCid();
    });

    function configuracaoPadrao(loadEdit){

        $('.div-pad').show();

        $("#inputDuracao").removeAttr('readonly');

        $(".selectedRiscoVidaInsalubridade").addClass('displayNone');
        $("#fileContratoTrabalho").addClass('displayNone');
        $("#AgendamentoVinculo").addClass('displayNone');

        $(".areaCids").removeClass('displayNone');

        $("#divAtendimentoDomicilio").removeClass('displayNone');
        $("#RowUnidadeAtendimento").removeClass('displayNone');

        $("#divEncaixeDiaDataHora").removeClass('displayNone');
        $('#comboDiaSemana').parents('div:eq(1)').show();  //select de dias da semana
        $('#AgendamentoDataHora').parents('div:eq(1)').show(); //select de data hora

        $('#data-livre').hide();
        $('#hora-livre').hide();

        $("#fileCursoFormacao").addClass('displayNone');
        $("#selectedTreinamentoDesvioFuncao").addClass('displayNone');

        $("#divConfirmaDivulgacao").removeClass('displayNone');

        $('#hidCAT').addClass('displayNone');
        $('#divReadaptacaoDefinitiva').addClass('displayNone');
        $("#hidReadaptacaoDefinitiva").prop('checked', false);
        $('.col-domiciliar').show();
        $("#labelCid").text('CID*');
        //$("#labelCid").text('CID');

        if(!loadEdit){
            $('#AgendamentoUnidadeAtendimentoId').val('');
            $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);

            $(".chefiaImediata").removeClass('displayNone');
            $('.colunaObito').addClass('displayNone');

            $("#div-tip"+TIPOLOGIAS.isencao_contribuicao_previdenciaria).addClass('displayNone');
            $("#div-data_aposentadoria").addClass('displayNone');
        }

        $('.fields-inspecao').hide();

        $('#div-tratamento_acidente').addClass('displayNone');
        $('#div-tratamento_acidente_sim').addClass('displayNone');

        $('#hNomeChefe').text('Nome do Chefe*');
        $('#hMatriculaChefe').text('Matrícula*');
        $('.row-periodo').show();
        $('.fields-designacao').hide();



        $('#tipoTipologia').val('');
        $('.div-tipo').addClass('displayNone');
        $('#div-oficio').addClass('displayNone');

        $('.divAposentadoriaEspecial').addClass('displayNone');

        $('.divDeclaracaoAtribuicao').addClass('displayNone');

        $('#div-recurso_adm').addClass('displayNone');

        var text = 'Autorizo a divulgação das informações médicas e seu diagnóstico codificado (CID) para fins de perícias medica, ';
        text += 'conforme a resolução do Conselho Federal de Medicina- CFM nº 1658/2002';
        $('#labelAutorizoDivulgacao').text(text);
        $('#hidAutorizoDivulgacao').parents('.row:first').show();
    }

    function limparDadosPretenso(){
        $("#AgendamentoDataNascimentoPretenso").val("");
        $("#AgendamentoCpfPretenso").val("");
        $("#AgendamentoNomePretenso").val("");
        $("#AgendamentoSexoIdPretenso").val("");
    }

    /**
     * Aplicando regras de acordo com a tipologia.
     * @returns {undefined}
     */
    function tratarRegrasTipologia(loadEdit) {
        console.log('tratarRegrasTipologia', loadEdit);

        configuracaoPadrao(loadEdit);

        if($("#hidReadaptacaoDefinitiva").is(':checked')){
            $('#divDurante').addClass('displayNone');
        }

        if($('#AgendamentoProcessoAdministrativo').val() == 1){
            $('.n-pad').removeClass('displayNone');
            if ( $('#numero_pad').val() != ''){
                $('#label_pad_selecionado').removeClass('displayNone');
            }
        }

        var hasAutorizacaoDivulgacao = true;
        var requiredCID = true;
        var hideCID = false;

        var tipologiaSelecionada = parseInt($('#comboTipologia').val());

        if($.inArray(tipologiaSelecionada, [TIPOLOGIAS.admissao_pensionista_maior_invalido,
                TIPOLOGIAS.isencao_contribuicao_previdenciaria]) != -1){
            $('.chefiaImediata').addClass('displayNone');

        }

        if($.inArray(tipologiaSelecionada, [TIPOLOGIAS.remanejamento_funcao,
                TIPOLOGIAS.readaptacao_funcao, TIPOLOGIAS.remocao]) != -1){
            $('.divDeclaracaoAtribuicao').removeClass('displayNone');

        }


        if(tipologiaSelecionada == TIPOLOGIAS.admissao_pensionista_maior_invalido){
            $('.colunaObito').removeClass('displayNone');
            $('.dadosPretenso').removeClass('displayNone');
        }

        if(tipologiaSelecionada != TIPOLOGIAS.admissao_pensionista_maior_invalido){
            limparDadosPretenso();
        }

        if(!loadEdit && tipologiaSelecionada == TIPOLOGIAS.reversao_aposentadoria_invalidez){
            alertaDialog("A data para a tipologia Reversão de aposentadoria por invalidez entrará em vigor a partir da informada.");
        }

        if(tipologiaSelecionada === TIPOLOGIAS.inspecao){
            $('.fields-inspecao').show();
            $('#labelAutorizoDivulgacao').text("Autorizo a divulgação das informações aqui declaradas");
        }

        if(tipologiaSelecionada === TIPOLOGIAS.sindicancia_inquerito_pad){
            $('#comboDiaSemana').parents('div:eq(1)').hide();  //select de dias da semana
            $('#comboDiaSemana').val('');
            $('#AgendamentoDataHora').parents('div:eq(1)').hide(); //select de data hora
            $('#AgendamentoDataHora').val('');

            $('#data-livre').show();
            $('#hora-livre').show();
        }

        if(tipologiaSelecionada === TIPOLOGIAS.recurso_administrativo){
            $('#div-recurso_adm').removeClass('displayNone');
        }

        if (tipologiaSelecionada === TIPOLOGIAS.antecipacao_licenca) {
            $("#inputDuracao").removeAttr('readonly');
        }
        if (tipologiaSelecionada === TIPOLOGIAS.licenca_maternidade) {
            $('#inputDuracao').val('180');
            desabilitarCampoDuracao(true);
        }

        if (tipologiaSelecionada === TIPOLOGIAS.comunicacao_de_acidente_de_trabalho) {
            var idServidor = $.trim($('#hiddenServidorId').val());
            if(idServidor != ''){
                carregarDadosAcidentadoAgendamento(idServidor);
                carregarDadosOrgaoServidor(idServidor);
            }
            console.log('chegou');
            $('#hidCAT').removeClass('displayNone');
            $('#registro_policial_cat').change();
            $('#assistencia_medica_hospitalar_acidente_doenca').change();
            $('#testemunha_acidente_doenca').change();
        }

        if ($.inArray(tipologiaSelecionada, [TIPOLOGIAS.licenca_maternida_aborto, TIPOLOGIAS.licenca_natimorto]) !== -1) {
            $("#inputDuracao").val('30');
            desabilitarCampoDuracao(true);
        }

        if($.inArray(tipologiaSelecionada , [TIPOLOGIAS.admissao_pensionista_maior_invalido, TIPOLOGIAS.isencao_contribuicao_previdenciaria])!= -1){
            $('.div-pad').hide();
        }

        //TIPOLOGIAS COM READAPTACAO
        if ($.inArray(tipologiaSelecionada, [TIPOLOGIAS.readaptacao_funcao, TIPOLOGIAS.remanejamento_funcao, TIPOLOGIAS.remocao]) !== -1) {
            $('#divReadaptacaoDefinitiva').removeClass('displayNone');
        }
        switch (tipologiaSelecionada){
            case TIPOLOGIAS.readaptacao_funcao:
                $('#hidReadaptacaoDefinitiva').siblings('label').text('Readaptação Definitiva');
                break;
            case TIPOLOGIAS.remanejamento_funcao:
                $('#hidReadaptacaoDefinitiva').siblings('label').text('Remanejamento Definitivo');
                break;
            case TIPOLOGIAS.remocao:
                $('#hidReadaptacaoDefinitiva').siblings('label').text('Remoção Definitiva');
                break;
        }


        if (tipologiaSelecionada === TIPOLOGIAS.risco_vida_insalubridade) {
            exibirAlertaTipologiaInsalubridade();
            habilitarRiscoVidaInsalubridade(false);
        }

        if(tipologiaSelecionada === TIPOLOGIAS.isencao_contribuicao_previdenciaria){
            $("#div-tip"+TIPOLOGIAS.isencao_contribuicao_previdenciaria).removeClass('displayNone');
            if($("#AgendamentoTipoIsencao").val() == TIPO_ISENCAO.servidor ){
                $("#div-data_aposentadoria").removeClass('displayNone');
            }

        }

        //TIPOLOGIAS SEM APARTIR DE
        if ($.inArray(tipologiaSelecionada, [
                TIPOLOGIAS.isencao_contribuicao_previdenciaria, TIPOLOGIAS.aposentadoria_invalidez]) !== -1) {
            $(".colunaApartirDe").addClass('displayNone');
            $("#AgendamentoDataAPartir").val('');
        }

        //TIPOLOGIAS SEM DURACAO
        if ($.inArray(tipologiaSelecionada, [
                TIPOLOGIAS.aposentadoria_invalidez, TIPOLOGIAS.isencao_contribuicao_previdenciaria,
                TIPOLOGIAS.reversao_aposentadoria_invalidez, TIPOLOGIAS.avaliacao_habilitacao_dependentes, TIPOLOGIAS.pcd,
                TIPOLOGIAS.admissao_pensionista_maior_invalido, TIPOLOGIAS.informacao_para_seguro_compreensivo_habitacional,
                TIPOLOGIAS.aposentadoria_especial,  TIPOLOGIAS.risco_vida_insalubridade]) !== -1) {
            $("#inputDuracao").val('');
            $("#divDurante").addClass('displayNone');
        }

        //TIPOLOGIAS SEM DURANTE OU APARTIR DE
        if ($.inArray(tipologiaSelecionada, [
                TIPOLOGIAS.recurso_administrativo, TIPOLOGIAS.exame_pre_admissional,
                TIPOLOGIAS.designacao_de_assistente_tecnico,
                TIPOLOGIAS.sindicancia_inquerito_pad, TIPOLOGIAS.inspecao,
                TIPOLOGIAS.comunicacao_de_acidente_de_trabalho,
                TIPOLOGIAS.admissao_pensionista_maior_invalido]) !== -1) {
            esconderDuranteApartirDe(true);
        }
        if (tipologiaSelecionada === TIPOLOGIAS.licenca_medica_tratamento_saude){
            $('#div-tratamento_acidente').removeClass('displayNone');
            if($('#tratamento_acidente').val() == 1){
                $('#div-tratamento_acidente_sim').removeClass('displayNone');
            }
        }

        if (tipologiaSelecionada === TIPOLOGIAS.licenca_medica_tratamento_saude || tipologiaSelecionada === TIPOLOGIAS.licenca_acompanhamento_familiar) {
            $("#inputDuracao").removeAttr('readonly');
            if(!loadEdit)$("#inputDuracao").val('');
        }

        //TIPOLOGIAS SEM OBRIGATORIEDADE DE CID
        if($.inArray(tipologiaSelecionada, [TIPOLOGIAS.recurso_administrativo, TIPOLOGIAS.isencao_contribuicao_previdenciaria,
                TIPOLOGIAS.licenca_maternida_aborto, TIPOLOGIAS.licenca_natimorto]) !== -1){
            requiredCID = false;
        }

        //TIPOLOGIAS SEM OBRIGATORIEDADE DE CID, NEM TEM AUTORIZACAO
        if ($.inArray(tipologiaSelecionada , [TIPOLOGIAS.exame_pre_admissional, TIPOLOGIAS.sindicancia_inquerito_pad])!== -1) {
            hasAutorizacaoDivulgacao = false;
            requiredCID = false;
        }
        //TIPOLOGIAS SEM ATENDIMENTO DOMICILIAR
        if ($.inArray(tipologiaSelecionada,[TIPOLOGIAS.exame_pre_admissional, TIPOLOGIAS.readaptacao_funcao,
                TIPOLOGIAS.remanejamento_funcao, TIPOLOGIAS.reversao_aposentadoria_invalidez, TIPOLOGIAS.risco_vida_insalubridade,
            TIPOLOGIAS.sindicancia_inquerito_pad, TIPOLOGIAS.inspecao]) !== -1){
            $('#AgendamentoAtendimentoDomiciliar').attr('checked', false);
            $('.col-domiciliar').hide();
        }

        //TIPOLOGIAS SEM ATENDIMENTO DOMICILIAR, UNIDADE, ENCAIXE, DIA OU DATA/HORA E CID NAO OBRIGATORIO
        if($.inArray(tipologiaSelecionada, [TIPOLOGIAS.comunicacao_de_acidente_de_trabalho,
                    TIPOLOGIAS.aposentadoria_especial, TIPOLOGIAS.risco_vida_insalubridade, TIPOLOGIAS.inspecao]) !== -1){
            $('#divAtendimentoDomicilio').addClass('displayNone');
            $('#RowUnidadeAtendimento').addClass('displayNone');
            $('#divEncaixeDiaDataHora').addClass('displayNone');
            $('#comboDiaSemana').val('');
            $('#AgendamentoDataHora').val('');
            requiredCID = false;
        }

        if ($.inArray(tipologiaSelecionada, [TIPOLOGIAS.designacao_de_assistente_tecnico]) !== -1){
            $('#hNomeChefe').text('Nome do Chefe');
            $('#hMatriculaChefe').text('Matrícula');
            $('.row-periodo').hide();
            $('.fields-designacao').show();

            if($("#cpfServidor").prop("disabled")){
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
            }else{
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
            }

            // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);

            hasAutorizacaoDivulgacao = false;
            requiredCID = false;
        }
        //TIPOLOGIAS QUE NAO POSSUEM CID | SEM CID
        if($.inArray(tipologiaSelecionada, window.arrTipologiasSemCid) !== -1){
            hideCID = true;
            requiredCID = false;
        }

        //ESPECIFICO DE APOSENTADORIA ESPECIAL
        if(tipologiaSelecionada === TIPOLOGIAS.aposentadoria_especial){
            $('.divAposentadoriaEspecial').removeClass('displayNone');
            $('#labelAutorizoDivulgacao').text('Autorizo a divulgação das informações aqui declaradas');
        }

        if(!hasAutorizacaoDivulgacao){
            $('#hidAutorizoDivulgacao').parents('.row:first').hide();
            $('#hidAutorizoDivulgacao').attr('checked', false);
        }

        if(!requiredCID){
            $("#labelCid").text('CID');
        }else{
            habilitarCamposAfterCid();
        }

        if(hideCID){
            $('.areaCids').addClass('displayNone');
            $('.itemCid:first').siblings().remove().andSelf().find("input").val("");
        }

        visibilidadeFieldsetAcompanhado();

        var divTipo = false;
        var divOficio = false;
        if(-1 != $.inArray(tipologiaSelecionada, [TIPOLOGIAS.sindicancia_inquerito_pad])){
            divTipo = true;
            divOficio = true;
        }
        if(divTipo){
            $('.div-tipo').removeClass('displayNone');
        }

        if(divOficio){
            $('#div-oficio').removeClass('displayNone');
        }
    }

    function habilitarRiscoVidaInsalubridade(){
        $(".selectedRiscoVidaInsalubridade").removeClass('displayNone');

        $("#AgendamentoVinculo").removeClass('displayNone');
        $("#divConfirmaDivulgacao").addClass('displayNone');

        $vinculoSelecionado =  parseInt($('#AgendamentoVinculo').val());
        if($vinculoSelecionado === VINCULO.CTD){
                $("#fileContratoTrabalho").removeClass('displayNone');
        }else{
                $("#fileContratoTrabalho").addClass('displayNone');
        }

        $cursoFormacaoSelecionado =  parseInt($('#AgendamentoCursoFormacao').val());
        if($cursoFormacaoSelecionado === 1){ //sim
            $("#fileCursoFormacao").removeClass('displayNone');
        }else{
            $("#fileCursoFormacao").addClass('displayNone');
        }
        $treinamentoFormacaoSelecionado =  parseInt($("#AgendamentoTreinamentoDesvioFuncao").val());
        if($treinamentoFormacaoSelecionado === 1){ //sim
            $("#selectedTreinamentoDesvioFuncao").removeClass('displayNone');
        }else{
            $("#selectedTreinamentoDesvioFuncao").addClass('displayNone');
        }
    }

    function esconderDuranteApartirDe(disabled) {
        if (disabled) {
            $(".colunaApartirDe").addClass('displayNone');
            $("#AgendamentoDataAPartir").val('');
            $("#divDurante").addClass('displayNone');
            $("#inputDuracao").val('');
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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{
                        if (showError) generateGrow('Problemas ao consultar dados.', 'danger');
                    }
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

    function exibirAjuda(div) {
        $('#'+div).removeClass('displayNone');
        $("div#"+div).dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#"+div).dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: {
                "Ok": {
                    text: 'Ok',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#'+div).addClass('displayNone');
                        $(this).dialog("close");
                    }
                }
            }
        });
    }

    $('#helperDeclaracao').click(function(){
        exibirAjuda('dialog-Declaracao')
    });

    $('#helperPPP').click(function(){
        exibirAjuda('dialog-PPP')
    });

    $('#helperLTCAT').click(function(){
        exibirAjuda('dialog-LTCAT')
    });

    visibilidadeFieldsetAcompanhado();

    function visibilidadeFieldsetAcompanhado() {
        var tipologiaSelecionada = parseInt($('#comboTipologia').val());

        if (tipologiaSelecionada === TIPOLOGIAS.licenca_acompanhamento_familiar) {
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
     * Criando Grow
     */


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

    

    $("#escolha_processo").autocomplete({
        source: function (request, response) {
            if($('#hiddenServidorId').val() == ''){
                alertaDialog('É preciso escolher o servidor.');
                generateGrow('É preciso escolher o servidor.', 'danger');
                $("#escolha_processo").val('');
                return false;
            }
            $.ajax({
                url: $("#escolha_processo").data("url")+'/'+$('#hiddenServidorId').val()+'/'+$("#escolha_processo").val(),
                dataType: "json",
                success: function (data) {
                    response(data);
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){                        
                        window.location.reload();
                    }else{                        
                        generateGrow('Problemas ao consultar dados.', 'danger');
                        console.log(response);
                    }
                }
            });
        },
        response: function (event, ui) {
            
            $('#numero_processo').val('');
            $('#label_processo_selecionado').parents('div:first').addClass('displayNone');
            $('#label_processo_selecionado a').text('');
        },
        select: function (a, b) {
            $('#numero_processo').val(b.item.numero);
            $('#label_processo_selecionado a').text(b.item.label);
            $('#label_processo_selecionado').parents('div:first').removeClass('displayNone');
            if(b.item.cid_id){
                //$('#AgendamentoCidId').val(b.item.cid_id);
                mudancaCid();
            }
            if(b.item.id_tipo == 5){
                $('.chefiaImediata').addClass('displayNone');
                
            }
        }

    });


    $("#escolha_exig").autocomplete({
        source: function (request, response) {
            if($('#hiddenServidorId').val() == ''){
                alertaDialog('É preciso escolher o servidor.');
                generateGrow('É preciso escolher o servidor.', 'danger');
                $("#escolha_exig").val('');
                return false;
            }
            $.ajax({
                url: $("#escolha_exig").data("url")+'/'+$('#hiddenServidorId').val()+'/'+$("#escolha_exig").val()+'/'+$("#comboTipologia").val(),
                dataType: "json",
                success: function (data) {
                    response(data);
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){ 
                        window.location.reload();
                    }else{
                        generateGrow('É preciso escolher uma tipologia.', 'danger');
                        console.log(response);
                    }
                }
            });
        },
        response: function (event, ui) {
            $('#numero_exigencia').val('');
            $('#label_processo_selecionado').parents('div:first').addClass('displayNone');
            $('#label_processo_selecionado a').text('');  
        },
        select: function (a, b) {
            $('#numero_exigencia').val(b.item.numero);
            $('#label_processo_selecionado a').text(b.item.label);
            $('#label_processo_selecionado').parents('div:first').removeClass('displayNone');
            if(b.item.cid_id){
                //$('#AgendamentoCidId').val(b.item.cid_id);
                mudancaCid();
            }
        }
    });



    $("#escolha_pad").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: $("#escolha_pad").data("url")+'/'+$('#hiddenServidorId').val()+'/'+$("#escolha_pad").val(),
                dataType: "json",
                success: function (data) {
                    response(data);
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{
                        if (showError) generateGrow('Problemas ao consultar dados.', 'danger');
                    }
                }
            });
        },
        response: function (event, ui) {
            $('#numero_pad').val('');
            $('#label_pad_selecionado').addClass('displayNone');
            $('#label_pad_selecionado a').text('');
        },
        select: function (a, b) {
            $('#numero_pad').val(b.item.numero);
            $('#label_pad_selecionado a').text(b.item.label);
            $('#label_pad_selecionado').removeClass('displayNone');
        }
    });

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
    //$('#AgendamentoCidId').on('change',
    window.mudancaCid = function() {

        //if ($('#comboTipologia').val() == TIPOLOGIAS.designacao_de_assistente_tecnico)return false;

        if($.inArray( $('#comboTipologia').val(), [TIPOLOGIAS.comunicacao_de_acidente_de_trabalho ])!= -1)return false;
        habilitarCamposAfterCid();
        var url = $('#baseUrlDefault').data('url') + 'getUnidadeCid/';
        var valueTipologia = $('#comboTipologia').val();
        var cids = [];
        $('.selectedCid').each(function(){
            cids.push($(this).val());
        });

        if($("#cpfServidor").prop("disabled")){
            $('#AgendamentoAtendimentoDomiciliar').attr('disabled', true);
        }else{
            $('#AgendamentoAtendimentoDomiciliar').attr('disabled', false);
        }
        

        if ($("#AgendamentoAtendimentoDomiciliar").is(':checked')){
            $("#RowUnidadeAtendimento").hide();
            $("#RowAtendUnidadeProxima").show();

            // console.log('Entrei aqui');
            var options = "";
            options += "<option value=''>Selecione</option>";
            $("#AgendamentoUnidadeAtendimentoId").html(options);
            // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);

            if($("#cpfServidor").prop("disabled")){
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
            }else{
                $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
            }

            var atendUndProxima = $('#AtendUnidadeProxima').val();
            var municipio = $('#MunicipioAtendimento').val();

            if (atendUndProxima == "1" && municipio != "") {
                // console.log('Entrei aqui tambem');
                var municipioAtend = municipio
                var atendimento_domicilio = 1;
                var municipio_proximo = 1;
                url = $('#MunicipioAtendimento').attr('url-data') + 'getUnidadeCidMunicipio';
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        municipio: municipioAtend,
                        cids: cids,
                        idTipologia: valueTipologia,
                        atendimento_domicilio: atendimento_domicilio,
                        municipio_proximo: municipio_proximo
                    },
                    dataType: "html",
                    success: function (response) {
                        //var options = "";
                        //options += "<option value=''>Selecione</option>"
                        var objResponse = $.parseJSON(response);

                        $.each(objResponse, function (key, value) {
                            options += '<option value="' + key + '">' + value + '</option>';
                        });
                        // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
                        if($("#cpfServidor").prop("disabled")){
                            $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
                        }else{
                            $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
                        }
                        $("#AgendamentoUnidadeAtendimentoId").html(options);
                        $("#RowUnidadeAtend").show();
                        $("#RowUnidadeAtendimento").show();

                        if (!endServidor.id) {
                            var cpfServidor = $("#cpfServidor").val();
                            getEnderecoServidor(cpfServidor);
                        }

                    },
                    error: function (response, status, error) {
                        if(error == 'Forbidden'){
                            window.location.reload();
                        }else{
                            $('.itemCid:first').siblings().remove().andSelf().find("input").val("");
                            generateGrow('Problemas ao consultar Unidades de Atendimento.', 'danger');
                        }
                    }
                });
            }
        } else {
            $("#RowUnidadeAtendimento").show();
            $("#AtendUnidadeProxima").val("");
            $("#RowAtendUnidadeProxima").hide();
            $("#AtendEndereco").val("");
            $("#RowMunicipio").hide();
            $("#RowUnidadeAtend").hide();
            $('#RowEnderecoAtendimento').hide();
            $("#RowCadastroEnderecoAtendimento").hide();
            $("#MunicipioAtendimento").val("");
            $('#AgendamentoUnidadeAtendimentoId').html('');

            $.ajax({
                url: url,
                type: "POST",
                data: {idTipologia: valueTipologia, cids: cids},
                dataType: "html",
                success: function (response) {

                    // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);

                    if($("#cpfServidor").prop("disabled")){
                        $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
                    }else{
                        $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
                    }

                    var responseJson = $.parseJSON(response);
                    $('#AgendamentoUnidadeAtendimentoId').find('option').remove();
                    $('#AgendamentoUnidadeAtendimentoId').append(new Option('Selecione', ''));
                    $.each(responseJson, function (index, valor) {
                        if (valor.id != null) {
                            $('#AgendamentoUnidadeAtendimentoId').append(new Option(valor.name, valor.id));
                        }
                    });
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                        $('.itemCid:first').siblings().remove().andSelf().find("input").val("");
                        generateGrow('Problemas ao consultar Unidades de Atendimento.', 'danger');
                    }
                }
            });
        }
    };

    $('#comboLicencasConcedidas').on('change', function () {
        var url = $('#baseUrlDefault').data('url') + 'getUnidadeCidAtendimento/';
        var urlConsultarDataFinalLicenca = $('#baseUrlDefault').data('url') + 'getDataFinalLicenca/';
        var idAtendimento = $('#comboLicencasConcedidas').val();
        if ($('#formBody').data('action') !== "deletar") {
            if (idAtendimento !== "") {
                // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);

                if($("#cpfServidor").prop("disabled")){
                    $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
                }else{
                    $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
                }

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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
                }
            });

            $.ajax({
                url: urlConsultarDataFinalLicenca,
                type: "get",
                data: {idAtendimento: idAtendimento},
                dataType: "html",
                success: function (response) {
                    $("#inputAte").val(response);
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
                }
            });
        }


    });


    if ($('#formBody').data('action') === "deletar") {
        $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
        $('#hidEncaixe').attr('disabled', true);
    }
    if ($('#formBody').data('action') === "homologar") {
        $("#divConfirmaAcordo").removeClass('displayNone');
        //$('#hidAutorizoDivulgacao').attr('disabled', false);
    }

    /**
     * Habilitando as funções de acordo com o Edit
     */
    habilitarCamposAfterCid();
    /**
     * Habilitar Campos
     */
    function habilitarCamposAfterCid() {
        var tipologiaSelecionada =  $('#comboTipologia').val();
        var valueCID = $('#AgendamentoCidId').val();
        if($.isArray(valueCID))valueCID = valueCID[0];
        if ($('#formBody').data('action') !== "deletar") {
            if (valueCID !== "" || $.inArray( tipologiaSelecionada, [ TIPOLOGIAS.designacao_de_assistente_tecnico,
                    TIPOLOGIAS.exame_pre_admissional, TIPOLOGIAS.sindicancia_inquerito_pad]) != -1) {
                // $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);

                if($("#cpfServidor").prop("disabled")){
                    $('#AgendamentoUnidadeAtendimentoId').attr('disabled', true);
                }else{
                    $('#AgendamentoUnidadeAtendimentoId').attr('disabled', false);
                }
            }
        }
    }


    $("#AgendamentoDataHora").on('change', function(){
        var url = $("#btSave").data('url') + "checaFeriado";
        var data = $(this).val();

        $.ajax({
            url: url,
            type: "POST",
            data: {data_feriado: data},
            dataType: "json", 
            success: function (response) {
                // console.log(response);
                if(response.tipo == "true"){
                    generateGrow('A data escolhida é um feriado! Selecione outra data.', 'danger');
                    $("#btSave").hide();
                }else{
                    $("#btSave").show();
                }
                // console.log(response.tipo);
            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{
                    generateGrow('Problemas ao consultar Feriados.', 'danger');
                }
            }
        });


    });

    $("#AgendamentoDataLivre").on('change',function(){
        var url = $("#btSave").data('url') + "checaFeriado";
        var data = $(this).val();

        $.ajax({
            url: url,
            type: "POST",
            data: {data_feriado: data},
            dataType: "json", 
            success: function (response) {
                console.log(response);
                if(response.tipo == "true"){
                    generateGrow('A data escolhida é um feriado! Selecione outra data.', 'danger');
                    $("#btSave").hide();
                }else{
                    $("#btSave").show();
                }
                // console.log(response.tipo);
            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{
                    generateGrow('Problemas ao consultar Feriados.', 'danger');
                }
            }
        });
    });

    function existeAgendamento(botao) {

        var url = $(botao).data('url');
        url = url + 'validarUnicidadeAgendamentoAjax';
        var retorno = false;
        var encaixe = $('#hidEncaixe').is(':checked');
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
                numero_processo: $('#numero_processo').val(),
                encaixe: encaixe
            },
            dataType: "html",
            success: function (response) {
                var responseJson = $.parseJSON(response);
                if (responseJson.status == "danger") {
                    retorno = true;
                    $("#id_agendamento_vigente").val(responseJson.idAgendamentoVigente);
                }
            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{

                }
            }
        });
        return retorno;
    }

    $('#btSave').click(function (e) {
        if($('#formBody').data('action') == "homologar"){

            var tipologiaSelecionada =  $('#comboTipologia').val();

            if(validarCamposHomolog(e) && tipologiaSelecionada == TIPOLOGIAS.comunicacao_de_acidente_de_trabalho){
                validarAgendamentoHomologa();
            }else{
                validarAgendamentoHomologa();
            }

        }else{
            
            validarAgendamentoHomologa();
        }

    });

    function validarAgendamentoHomologa(){
        if ($('#comboTipologia').val() == TIPOLOGIAS.licenca_acompanhamento_familiar) {
            exibirAlertaTipologiaFamilia();
        } else if ($('#comboTipologia').val() == TIPOLOGIAS.risco_vida_insalubridade) {
            validaRiscoVidaInsalubridade();
        } else {
            validarAgendamento(this);
        }
    }

    // $('#btSave').click(function (e) {
         
    //     if ($('#comboTipologia').val() == TIPOLOGIAS.licenca_acompanhamento_familiar) {
    //         exibirAlertaTipologiaFamilia();
    //     } else if ($('#comboTipologia').val() == TIPOLOGIAS.risco_vida_insalubridade) {
    //         validaRiscoVidaInsalubridade();
    //     } else {

    //         validarAgendamento(this);
    //     }

    // });

    function validarCamposHomolog(e){
        var retorno = true;
        var tipologiaSelecionada = $('#comboTipologia').val();
        //CAT tem id 29
        if (tipologiaSelecionada == 29) {
            if($("#AgendamentoCATLicencaMedicaChefiaImediata").val() == ""){
                retorno = false;
                generateGrow('É necessário selecionar se houve licença médica.', 'danger');            
                
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#AgendamentoCATUltimoDiaTrabalhadoChefiaImediata").val() == ""){
                retorno = false;            
                generateGrow('É necessário preencher o último dia trabalhado.', 'danger');
                
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#AgendamentoCATNomeChefiaImediata").val() == ""){
                retorno = false;            
                generateGrow('É necessário preencher o nome da chefia imediata.', 'danger');
                
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#AgendamentoCATCpfChefiaImediata").val() == ""){
                retorno = false;            
                generateGrow('É necessário preencher o cpf da chefia imediata.', 'danger');
                
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#AgendamentoCATTelefoneChefiaImediata").val() == ""){
                retorno = false;            
                generateGrow('É necessário preencher o telefone da chefia imediata.', 'danger');
                
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#AgendamentoCATCargoChefiaImediata").val() == ""){
                retorno = false;            
                generateGrow('É necessário preencher o cargo da chefia imediata.', 'danger');
                
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#acidentado_em_atividade").val() == ""){
                retorno = false;            
                generateGrow('É necessário selecionar se o acidentado estava executando atividades do seu cargo/função.', 'danger');
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }else if($("#medidas_evitar_ocorrencia").val() == ""){
                retorno = false;            
                generateGrow('É necessário preencher que medidas tomou ou deverão ser tomadas para evitar novas ocorrências.', 'danger');
                $("#formBody").submit(function(e){
                    e.preventDefault();
                });
            }
        }
        
        if(retorno == true){
          $('#formBody').unbind('submit').submit();
          return retorno;  
        } 
    }






    //controle para limpar chefias selecionadas e depois excluídas
    $('.chefias').blur(function(){
        setTimeout(function(){
            if($.trim($(this).val()) == ''){
                $(this).parents('tr:first').find('[name*=chefe_imediato_]:hidden, input:text:visible').val('');
            }
        }, 300);
    });

	function validaRiscoVidaInsalubridade(){
		var validaOk = true;
		if(($('#formBody').data('action') === "homologar")){

			if((!$("#hidAutorizoAcordo").is(':checked'))){
				 generateGrow('É necessário aceitar o acordo.', 'danger');
			}else{
				$('#formBody').submit();
			}

			validaOk = false;

		}else if( ($('#formBody').data('action') === "adicionar") || ($('#formBody').data('action') === "editar")){

			if($('#AgendamentoCursoFormacao').val() == ''){
				generateGrow('É necessário informar se o requerente realizou o curso de formação na área.', 'danger');
				validaOk = false;
			}else{
				if(($('#AgendamentoCursoFormacaoCertificado').val() == '') && $('#AgendamentoCursoFormacao').val() ==1){
					generateGrow('É necessário anexar o arquivo do certificado curso de formação.', 'danger');
					validaOk = false;
				}

			}
			if($('#AgendamentoGratificacaoRiscoVidaSaude').val() == ''){
				generateGrow('É necessário informar se recebeu gratificação de risco de vida ou saúde', 'danger');
				validaOk = false;
			}

			if($('#AgendamentoVinculo').val() == VINCULO.CTD){ // CTD
				if($('#AgendamentoContratoTrabalho').val() == ''){
					generateGrow('É necessário anexar contrato de trabalho', 'danger');
					validaOk = false;
				}
				if($('#AgendamentoEditalConcurso').val() == ''){
					generateGrow('É necessário anexar edital de concurso', 'danger');
					validaOk = false;
				}
			}
			
			if($('#AgendamentoDesvioFuncao').val() == ''){
				generateGrow('É necessário informar se o requerente encontra-se em desvio da função', 'danger');
				validaOk = false;
			}
			
			if($('#AgendamentoTreinamentoDesvioFuncao').val() == ''){
				generateGrow('É necessário informar se recebeu treinamento para exercer a função em desvio.', 'danger');
				validaOk = false;
			}else{
				if($('#AgendamentoDescricaoDesvioFuncao').val() == '' && ($('#AgendamentoTreinamentoDesvioFuncao').val() == 1)){
					generateGrow('É necessário justificar o treinamento (função em desvio).', 'danger');
					validaOk = false;
				}
			}
			
			if($('#AgendamentoProcessoAdministrativo').val() == '' &&
                    $('#comboTipologia').val() !=  TIPOLOGIAS.isencao_contribuicao_previdenciaria){
				generateGrow('É necessário informar se responde a algum processo administrativo disciplinar - PAD.', 'danger');
				validaOk = false;
			}
			
			if($('#AgendamentoHorarioTrabalhoInicial').val() == ''){
				generateGrow('É necessário informar horário trabalho inicial', 'danger');
				validaOk = false;
			}
			
			if($('#AgendamentoHorarioTrabalhoFinal').val() == ''){
				generateGrow('É necessário informar horário trabalho inicial', 'danger');
				validaOk = false;
			}
		}
		
		if(validaOk){
            validarAgendamento($("#btSave"));
		}
		
		
	}

    // function validarCamposCat(){
    //         var retorno = true;
    //         var tipologiaSelecionada = $('#comboTipologia').val();
    //         //CAT tem id 29
    //         if (tipologiaSelecionada == 29) {
    //             if(($('#chkbxPlantonista').is(":checked")) && ($('#expediente_plantonista').val() == "")){
    //                 console.log('talvez seja eu ae');
    //             }
    //         }
    //     }

    function validarAgendamento(campo) {
        // if($("#hidEncaixe").prop('checked') == false){
			

            if (existeAgendamento($("#btSave"))) {  


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
									
									if(url == undefined || $.trim(url) == ""){
										url = $("#btSave").data("url");
									}
                                    url = url + "editar/" + $("#id_agendamento_vigente").val();
									//console.log(url);
                                    window.location.href = url;
                                }
                            }
                        }
                    });
                

            } else {
            	
            	var tipologiaSelecionada = parseInt($('#comboTipologia').val());
            	if ($.inArray(tipologiaSelecionada, [TIPOLOGIAS.aposentadoria_especial, TIPOLOGIAS.comunicacao_de_acidente_de_trabalho, TIPOLOGIAS.risco_vida_insalubridade, TIPOLOGIAS.inspecao]) !== -1) {
            		
            		  $('#formBody').submit();
            		
            	}else{
            	
            			$('#dialog-ConfirmarAgendamento').removeClass('displayNone');
            			$("dialog-ConfirmarAgendamento").dialog().prev().find(".ui-dialog-titlebar-close").hide();
            			$("#dialog-ConfirmarAgendamento").dialog({
            				resizable: false,
            				height: 190,
            				width: 490,
            				modal: true,
            				buttons: {
            					"Cancelar": {
            						text: 'Cancelar',
            						'class': 'btn fa estiloBotao btn-info float-right',
            						click: function () {
            							$('#dialog-ConfirmarAgendamento').dialog("close");
            						}
            					},
            					"Ok": {
            						text: 'Ok',
            						'class': 'btn fa estiloBotao btn-info float-right',
            						click: function () {
										// PORTO
										$('#dialog-ConfirmarAgendamento').dialog("close");
										$("body").addClass("loading");
            							$('#formBody').submit();
            						}
            					}
            				}
            			});
            	}

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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

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

    $("#AgendamentoTipoIsencao").bind('change', function() {
        if ($("#AgendamentoTipoIsencao").val() == TIPO_ISENCAO.servidor) {
            $("#div-data_aposentadoria").removeClass('displayNone');
        }else{
            $("#div-data_aposentadoria").addClass('displayNone');
        }
    });

    $('#tratamento_acidente').change(function () {
        $('#blockAll').hide();
        $('#div-tratamento_acidente').removeClass('blockDestaque');
        if($('#tratamento_acidente').val() == 1){
            var id = $('#hiddenServidorId').val();
            if (id==""){
                alertaDialog("É preciso escolher quem é o servidor da solicitação.");
                $('#tratamento_acidente').val('');
                return;
            }
            var url = $('#baseUrlDefault').data('url') + 'jslistProcessosCAT/' + id;
            $.ajax({
                url: url,
                type: "POST",
                dataType: "html",
                success: function (response) {
                    var responseJson = $.parseJSON(response);
                    $('#div-tratamento_acidente_sim').removeClass('displayNone');
                    $('#tratamento_acidente_processo').html('<option value="">Selecione</option>');
                    if(responseJson.length == 0){
                        $('#blockAll').show();
                        $('#div-tratamento_acidente').addClass('blockDestaque');
                        alertaDialog("É necessário dar entrada na comunicação de acidente de trabalho – CAT.");
                    }else{
                        for(var i in responseJson){
                            $('#tratamento_acidente_processo').append(
                                '<option value="'+responseJson[i]+'">'+responseJson[i]+'</option>');
                        }
                    }
                    console.log(responseJson);
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{
                        $('#AgendamentoCidId').val('');
                        generateGrow('Problemas ao consultar Unidades de Atendimento.', 'danger');
                    }
                }
            });
        }else{
            $('#div-tratamento_acidente_sim').addClass('displayNone');
        }
    });


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
            },
            error: function (response, status, error) {
                if(error == 'Forbidden'){
                    window.location.reload();
                }else{

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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
                }
            });
        },
        minLength: 4,
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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
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
                },
                error: function (response, status, error) {
                    if(error == 'Forbidden'){
                        window.location.reload();
                    }else{

                    }
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

    if ($('#AtendEndereco').val() !== "") {
        $('#AtendEndereco').change();
    }
    function alertaPAD() {
        $("div#dialog-alerta-PAD").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-alerta-PAD").removeClass("displayNone");
        $("#dialog-alerta-PAD").dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: [{
                text: 'Ok',
                'class': 'btn fa estiloBotao btn-info float-right zTop',
                click: function () {
                    stop();
                    $(this).dialog("close");
                }
            }]
        }).parent().addClass('zTop');
    }

    function alertaDialog(txt) {
        $("div#dialog-alerta").dialog().prev().find(".ui-dialog-titlebar-close").hide();
        $("#dialog-alerta").removeClass("displayNone");
        $("#dialog-alerta p").text(txt);
        $("#dialog-alerta").dialog({
            resizable: false,
            height: 190,
            width: 490,
            modal: true,
            buttons: [{
                text: 'Ok',
                'class': 'btn fa estiloBotao btn-info float-right zTop',
                click: function () {
                    stop();
                    $(this).dialog("close");
                }
            }]
        }).parent().addClass('zTop');
    }

    $("#hidReadaptacaoDefinitiva").change(function(){
        if($("#hidReadaptacaoDefinitiva").is(':checked')){
            $('#divDurante').addClass('displayNone');
            $('#divDurante input').val('');
        }else{
            $('#divDurante').removeClass('displayNone');
        }
    });

});
function invalideHour(field) {
    field.value = '';
    generateGrow('A hora informada está inválida.', 'danger');
}

function generateGrow(message, priority) {

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

function cidsSelecionados(){
    var arrCids = [];
    $(".selectedCid").each(function(){
        if($(this).val() != "") arrCids.push($(this).val());
    });
    return arrCids;
}

function existeCidSelecionado(){
    return (cidsSelecionados().length > 0);
}



/** Fim do arquivo **/