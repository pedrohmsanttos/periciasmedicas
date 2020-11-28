

jQuery(function ($) {
    var tipologia = $("#tipologiaOrig").val();

	var TIPOLOGIAS = {};
    TIPOLOGIAS.risco_vida_insalubridade = parseInt($('#tipologia_risco_vida_insalubridade').val());
    TIPOLOGIAS.aposentadoria_especial = parseInt($('#tipologia_aposentadoria_especial').val());
    TIPOLOGIAS.comunicacao_de_acidente_de_trabalho = parseInt($('#tipologia_comunicacao_de_acidente_de_trabalho').val());
    TIPOLOGIAS.remocao = parseInt($('#tipologia_remocao').val());
    TIPOLOGIAS.remanejamento_funcao = parseInt($('#tipologia_remanejamento_funcao').val());
    TIPOLOGIAS.readaptacao_funcao = parseInt($('#tipologia_readaptacao_funcao').val());
    TIPOLOGIAS.reversao_aposentadoria_invalidez = parseInt($('#tipologia_reversao_aposentadoria_invalidez').val());
    TIPOLOGIAS.tipologia_comunicacao_de_acidente_de_trabalho = parseInt($('#tipologia_comunicacao_de_acidente_de_trabalho').val());
    TIPOLOGIAS.tipologia_isencao_contribuicao_previdenciaria = parseInt($('#tipologia_isencao_contribuicao_previdenciaria').val());
    
    var onloadPNE = true;
    $('.pneChoice input:radio').click(function(){
        val = $(this).val();
        if(val === '1'){
            $('#abaPNE').parent().show();
            if(!onloadPNE)$('#abaPNE').click();
        } else if (val === '0'){
            $('#abaPNE').parent().hide();
            $('.abasAtendimento li:first a:first').click();
        }
        onloadPNE = false;
    });
    $('.pneChoice input:radio:selected').click();

    if(tipologia == TIPOLOGIAS.aposentadoria_especial){
        $('.input_necessario_inspecao input').click(function(){
            if($(this).val() == 1){
                $('.necessario_inspecao').removeClass('displayNone');
            } else{
                $('#inputNumeroInspecao').val('');
                $('.necessario_inspecao').addClass('displayNone');
            }
        });
    }
    //esconde botao de isencao dessa tipologia
    if(tipologia == TIPOLOGIAS.tipologia_isencao_contribuicao_previdenciaria){
        $(".input-isencao").hide();
    }




    desabilitarBotaoAssociar();

    $('body').on('change', '.checkAssociar', function () {
        if ($(this).prop('checked')) {
            $("#hidAtendimentoPaiId").val($(this).data('id'));
            desabilitarBotaoAssociar();
        } else {
            $("#hidAtendimentoPaiId").val("");
            $(".checkAssociar").removeAttr('disabled');
        }

    });
    function desabilitarBotaoAssociar() {
        var possuiSelecionado = false;
        $.each($(".checkAssociar:checked"), function () {
            possuiSelecionado = true;
        });

        if (possuiSelecionado) {
            $.each($(".checkAssociar:not(:checked)"), function () {
                $(this).attr('disabled', true);
            });
        }

    }

    $('body').on('change', '.selecaoDependente', function () {
        var idDependente = $(this).data('id');
        $.each($(".selecaoDependente"), function () {
            $(this).prop('checked', false);
        });
        $(this).prop('checked', true);
        $("#hidDependenteId").val(idDependente);

    });

    $('body').on('change', '#inputInvalidezFisica', function () {
        tratarTipoInvalidezFisica(this);
    });

    function tratarTipoInvalidezFisica(comboSituacao) {
        var valorSelecionado = parseInt($(comboSituacao).val());
        if (valorSelecionado == 1) {
            $(".divDataDependenteInvalido").removeClass('displayNone');
        } else {
            $(".divDataDependenteInvalido").addClass('displayNone');
            $("#inputDataInvalidezFisica").val("");
        }
    }

    tratarTipoInvalidezFisicaAtosVidaCivil($("#inputTipoAtosVida"));

    $('body').on('change', '#inputTipoAtosVida', function () {
        tratarTipoInvalidezFisicaAtosVidaCivil(this);
    });

    function tratarTipoInvalidezFisicaAtosVidaCivil(comboSituacao) {
        var valorSelecionado = parseInt($(comboSituacao).val());
        if (valorSelecionado == 1) {
            $(".divDataAtosVida").removeClass('displayNone');
        } else {
            $(".divDataAtosVida").addClass('displayNone');
            $("#inputDataIncAtosVida").val("");
        }
    }

    tratarDependenteMaior($("#inputDependenteMaior"));

    $('body').on('change', '#inputDependenteMaior', function () {
        tratarDependenteMaior(this);
    });

    function tratarDependenteMaior(comboSituacao) {
        var valorSelecionado = parseInt($(comboSituacao).val());
        if (valorSelecionado == 1) {
            $(".divInvalidezFisica").removeClass('displayNone');
            $(".divAtosVidaCivil").removeClass('displayNone');
            $("#inputInvalidezFisica").attr('disabled', false);
        } else {
            $("#labelInternaloInvalidez").html('');
            $("#labelInternaloAtosVida").html('');
            $(".divInvalidezFisica").addClass('displayNone');
            $(".divDataDependenteInvalido").addClass('displayNone');
            $(".divDataAtosVida").addClass('displayNone');
            $(".divAtosVidaCivil").addClass('displayNone');
            clearSelectedItensMultiSelect('inputInvalidezFisica');
            clearSelectedItensMultiSelect('inputTipoAtosVida');
            $("#inputDataInvalidezFisica").val("");
            $("#inputDataIncAtosVida").val("");
            $("#inputInvalidezFisica").attr('disabled', true);
        }
    }




    if (tipologia == 9) {
        tratarTipoInvalidezFisica($("#inputInvalidezFisica"));
        tratarInternaloDatas($("#inputDataInvalidezFisica"));
        tratarInternaloDatas($("#inputDataIncAtosVida"));
    }

    if (tipologia == 5) {
        tratarInternaloDatas($("#AtendimentoDataInsencaoTemporaria"));
    }

    $('body').on('focusout', '#AtendimentoDataInsencaoTemporaria', function () {
        tratarInternaloDatas(this);
    });

    $('body').on('focusout', '#inputDataInvalidezFisica', function () {
        tratarInternaloDatas(this);
    });

    $('body').on('focusout', '#inputDataIncAtosVida', function () {
        tratarInternaloDatas(this);
    });

    function tratarInternaloDatas(campo) {
        var url = $(campo).data('url');
        var label = $(campo).data('label');
        var partesDatas = $(campo).val().split("/");

        if (partesDatas.length == 3) {

            var data = partesDatas[2] + "/" + partesDatas[1] + "/" + partesDatas[0];
            var dataAtual = $("#inputDataAtual").val();

            if (data >= dataAtual) {
                $.ajax({
                    url: url,
                    data: {inputDataAtual: dataAtual, dataFinal: $(campo).val()},
                    type: "POST",
                    dataType: "html",
                    success: function (response) {
                        $("#" + label).html(response);
                    }
                });
            } else {
                $("#" + label).html("");
            }

        } else {
            $("#" + label).html("");
        }

    }

    tratarSituacaoParecerTecnico($("#inputSituacao"));

    $('body').on('change', '#inputSituacao', function () {
        tratarSituacaoParecerTecnico(this);
    });

    function tratarSituacaoParecerTecnico(comboSituacao) {
        var situacao = parseInt($(comboSituacao).val());
        console.log('tratarSituacaoParecerTecnico');
        //se a situação for em exigências
        if (situacao === 1) {
            $("#divBotaoSituacao").removeClass('displayNone');
            $("#btFinalizarAtendimento").removeClass('displayNone');
            $("#btEmitirLaudo").addClass('displayNone');
        } else {
            $("#divBotaoSituacao").addClass('displayNone');
            $("#btFinalizarAtendimento").addClass('displayNone');
            $("#btEmitirLaudo").removeClass('displayNone');
            $("#inputObservacoesExigencia").val("");
            clearSelectedItensMultiSelect('picklistRequisicoes');
            atualizarPickList('picklistRequisicoes');
        }

        var arrayDurante = [
            TIPOLOGIAS.remocao, 
            TIPOLOGIAS.readaptacao_funcao, 
            TIPOLOGIAS.remanejamento_funcao,
            TIPOLOGIAS.reversao_aposentadoria_invalidez,
            TIPOLOGIAS.comunicacao_de_acidente_de_trabalho,
            TIPOLOGIAS.tipologia_isencao_contribuicao_previdenciaria

        ];
        
        if($.inArray(parseInt(  tipologia ), arrayDurante) != -1){
            if(parseInt(situacao) != 5 && parseInt(situacao) != 7){
                $(".cmpDurante").hide();
                $("#AtendimentoDuracao").val(" ");
            }else if(parseInt(situacao) == 5 || parseInt(situacao) == 7){
                $(".cmpDurante").show();
            }
        }

        //se a situação for deferida
        if (situacao === 8) {
            //$('#rowDataParecerTecnico').addClass('displayNone'); //para algumas tipologias não requer data
            $('#fieldSetSolicitarLicenca').removeClass('displayNone'); //para algumas tipologias requer data e duração
        } else {
            $('#rowDataParecerTecnico').removeClass('displayNone');
            $('#fieldSetSolicitarLicenca').addClass('displayNone');
        }

        if ($('#hidtipologia').val() == $('#tipologia_licenca_natimorto').val() ||
                $('#hidtipologia').val() == $('#tipologia_licenca_maternidade').val() ||
                $('#hidtipologia').val() == $('#tipologia_licenca_maternidade_aborto').val()) {
            $('#duranteParecer').attr('readonly', true);
        }

        if ($('#hidtipologia').val() == $('#tipologia_licenca_acompanhamento_familiar').val() ||
                $('#hidtipologia').val() == $('#tipologia_licenca_medica_tratamento_saude').val()) {

            if ($("#acaoTela").val() !== "visualizarAtendimento" && $("#acaoTela").val() !== "detalharAtendimento") {
                //Se a situação for indeferido o campo modo é desabilitado
                if (situacao === 2) {
                    $("#inputModos").prop('disabled', true);
                    $("#inputModos").val('');
                    $("#inputModos").find('option:checked').removeProp('checked');
                } else {
                    $("#inputModos").prop('disabled', false);
                }
            }
        }
    }

    function dialogEmitirLaudo() {
        $("#formBody").append($(".finalizarAtendimento"));
        $("#dialog-emissao_laudo").css("background-color", "white");
        $("#dialog-emissao_laudo").removeClass("displayNone");
        $("#dialog-emissao_laudo").dialog({
            resizable: false,
            width: 600,
            height: 200,
            dialogClass: "no-close",
            modal: true,
            position: 'center',
            draggable: false,
            buttons: {
                "Fechar": {
                    text: 'Fechar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-emissao_laudo').dialog("close");
                        $("#hidEmitirLaudo").val(false);
                    }
                },
                "Confirmar": {
                    text: 'Confirmar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-emissao_laudo').dialog("close");
                        $("#hidEmitirLaudo").val(true);
                        $('#formBody').submit();
                    }
                }

            }
        });
    }
    $('body').on('click', '#btEmitirLaudo', function (e) {
		e.preventDefault();
		validarRiscoVidaInsalubridade('btEmitirLaudo');
        
    });

    $('body').on('click', '.botaoVoltarAtendimento', function () {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: "POST",
            dataType: "html",
            success: function (response) {
                window.location = response;
            }
        });
    });

    $('body').on('click', '.detalharAtendimento', function () {
        var url = $(this).data('url');
        var idProximo = $(this).data('id');
        var acao = $(this).data('acao');
        var idAnterior = $(this).data('anterior');
        $.ajax({
            url: url,
            type: "POST",
            data: {idProximo: idProximo, acao: acao, idAnterior: idAnterior},
            dataType: "html",
            success: function (response) {
                window.location = response;
            }
        });
    });

    $('body').on('click', '#botaoExigencias', function () {
        $("#dialog-informacoes_sobre_servidor").css("background-color", "white");
        $("#dialog-informacoes_sobre_servidor").removeClass("displayNone");
        $("#dialog-informacoes_sobre_servidor").dialog({
            resizable: false,
            width: 1200,
            height: 500,
            dialogClass: "no-close",
            modal: true,
            position: 'center',
            draggable: false,
            buttons: {
                "Fechar": {
                    text: 'Fechar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $('#dialog-informacoes_sobre_servidor').dialog("close");
                    }
                },
                "Confirmar": {
                    text: 'Confirmar',
                    'class': 'btn fa estiloBotao btn-info float-right',
                    click: function () {
                        $("#inputObservacoesExigencia").val($("#observacoes_exigencia_modal").val());
                        $("#input_data_limite_exigencia").val($("#data_limite_exigencia").val());
                        clearSelectedItensMultiSelect("picklistRequisicoes");
                        $.each(($("#requisicao_exigencia_modal option")), function (index, valor) {
                            if ($(valor).prop('selected') == true) {
                                var valorSelecionado = $(valor).val();
                                $("#picklistRequisicoes").find('option[value=' + valorSelecionado + ']').prop('selected', true);
                            }
                        });
                        $('#picklistRequisicoes').multiSelect('refresh');
                        $('#dialog-informacoes_sobre_servidor').dialog("close");
                    }
                }

            },
            open: function (event, ui) {
                $("#observacoes_exigencia_modal").val($("#inputObservacoesExigencia").val());
                $("#data_limite_exigencia").val($("#input_data_limite_exigencia").val());
                clearSelectedItensMultiSelect("requisicao_exigencia_modal");
                $.each(($("#picklistRequisicoes option")), function (index, valor) {
                    if ($(valor).prop('selected') == true) {
                        var valorSelecionado = $(valor).val();
                        $("#requisicao_exigencia_modal").find('option[value=' + valorSelecionado + ']').prop('selected', true);
                    }
                });
                $('#requisicao_exigencia_modal').multiSelect('refresh');
            }
        });
    });
    function removerAlertas() {
        $('.alert').remove();
    }

    tipoIsencao();

    /**
     * Função para liberar ou esconder campo isenção
     */
    function tipoIsencao(parameters) {
        var tipoIsencao = $('#selectTipoIsencao').val();
        var hidIsencaoTemporaria = $('#hidIsencao').val();

        if (tipoIsencao === hidIsencaoTemporaria) {
            $('#divDataIsencaoTemporaria').removeClass('displayNone');
        } else {
            $('#divDataIsencaoTemporaria').addClass('displayNone');
            $('#AtendimentoDataInsencaoTemporaria').val('');
            $('#labelInternaloIsencaoTemporaria').html('');
        }
    }

    $('body').on('change', '#selectTipoIsencao', function () {
        tipoIsencao();
    });

    $('body').on('click', '#adicionarPerito', function () {
        removerAlertas();
        if ($('#hidPeritoId').val() == "") {
            growMessage('Selecione ao menos um perito!', 'danger');
        } else {
            var url = $('#adicionarPerito').data('url') + 'adicionarPerito/';
            var idPerito = $('#hidPeritoId').val();
            var nomePerito = $("#inputNomeJuntaPerito").val();
            var numeroRegistroPerito = $("#inputNumeroRegistroPerito").val();

            $.ajax({
                url: url,
                type: "POST",
                data: {idPerito: idPerito, nomePerito: nomePerito, numeroRegistroPerito: numeroRegistroPerito},
                dataType: "html",
                success: function (response) {
                    var objResponse = $.parseJSON(response);
                    $.each(objResponse, function (key, value) {
                        var table = document.getElementById("tablePeritos");
                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        cell1.innerHTML = value.nome + "<input type='hidden' name='data[Perito][Perito][]' value='"+value.id+"' >";
                        // cell1.innerHTML = value.nome + "<input type='hidden' name='data[Atendimento][Perito][]' value='"+value.id+"' >";
                        cell2.innerHTML = value.numero_registro;
                        cell3.innerHTML = '<div rel="' + value.id + '" data-url="' + $('#adicionarPerito').attr('data-url') + '" class="btn deletarPerito fa btn-danger" title="Excluir">Excluir</div>';
                    });
                    $('#hidPeritoId').val('');
                    $("#inputNumeroRegistroPerito").val('');
                    $("#inputNomeJuntaPerito").val('');
                    $('#emptyPerito').addClass('displayNone');
                }
            });
        }
    });

    $(document).on("click", ".deletarPerito", function () {
        removerAlertas();
        var url = $(this).data('url') + 'deletarPeritoSession/';        
        var id = $(this).attr('rel');
                
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
                if ($('#tablePeritos tr').length == 3) {
                    $('#emptyPerito').removeClass('displayNone');
                }
            },
            error: function (response) {
                generateGrow('Problemas ao deletar perito', 'danger');
            }
        });
       
    });

    $("#inputNomeJuntaPerito").autocomplete({
        source: $("#inputNomeJuntaPerito").data("url"),
        minLength: 4,
        response: function (event, ui) {
            $('#hidPeritoId').val('');
        },
        select: function (a, b) {
            $('#inputNomeJuntaPerito').val(b.item.nome);
            $('#inputNumeroRegistroPerito').val(b.item.numeroRegistro);
            $('#hidPeritoId').val(b.item.id);
        }
    });

    $("#inputNumeroRegistroPerito").autocomplete({
        source: $("#inputNumeroRegistroPerito").data("url"),
        response: function (event, ui) {
            $('#hidPeritoId').val('');
        },
        select: function (a, b) {
            $('#inputNomeJuntaPerito').val(b.item.nome);
            $('#inputNumeroRegistroPerito').val(b.item.numeroRegistro);
            $('#hidPeritoId').val(b.item.id);
        }
    });

    $("#escolha_inspecao").autocomplete({
        source: $("#escolha_inspecao").data("url"),
        response: function (event, ui) {
            $('#numero_inspecao').val('');
            $('#label_inspecao_selecionada').addClass('displayNone');
            $('#label_inspecao_selecionada a').text('');
        },
        select: function (a, b) {
            $('#numero_inspecao').val(b.item.numero);
            $('#label_inspecao_selecionada a').text(b.item.numero);
            $('#label_inspecao_selecionada').removeClass('displayNone');
        }
    });

    if( $('.input_necessario_inspecao input:checked').val() == 1){
        $('.necessario_inspecao').removeClass('displayNone');
    } else{
        $('#inputNumeroInspecao').val('');
        $('.necessario_inspecao').addClass('displayNone');
    }

    qualidadeId();

    /**
     * Função para qualidade 
     */
    function qualidadeId(parameters) {
        var qualidade = $('#AtendimentoQualidadeId').val();
        if (qualidade == 21) {
            $('#qualidadeOutros').removeClass('displayNone');
        } else {
            $('#inputOutros').val('');
            $('#qualidadeOutros').addClass('displayNone');
        }
    }

    $("#inputCodigoCid").autocomplete({
        source: $("#inputCodigoCid").data("url"),
        response: function (event, ui) {
            $('#AtendimentoCidId').val('');
            $('#inputDescricaoCid').val('');
        },
        select: function (a, b) {
            $('#AtendimentoCidId').val(b.item.id);
            $('#inputDescricaoCid').val(b.item.descricao);
        }
    });

    $('.downloadLaudo').on('click', function () {
        var url = $(this).data('url');
        window.location = url;
    });

    $('body').on('click', '.selecionarTodos', function () {

        if ($(this).prop('checked')) {
            $.each($(".selecionarProcesso:not(:checked)"), function () {
                var idProcesso = $(this).data('id');
                var option = new Option(idProcesso, idProcesso);
                $(this).prop('checked', true);
                $(option).attr('selected', 'selected');
                $("#procesos_selecionados").append(option);
            });
        } else {
            $.each($(".selecionarProcesso:checked"), function () {
                var idProcesso = $(this).data('id');
                $(this).prop('checked', false);
                $("#procesos_selecionados").find("option[value=" + idProcesso + "]").remove();
            });

            var possuiProcessosSelecionados = false;
            $("#procesos_selecionados option:selected").each(function () {
                possuiProcessosSelecionados = true;
                return false;
            });
            if (!possuiProcessosSelecionados) {
                $("#exportar_processos").addClass('displayNone');
            }
        }
        habilitarBotaoBaixarProcessos();
    });

    $('body').on('click', '.selecionarProcesso', function () {
        var idProcesso = $(this).data('id');
        if ($(this).prop('checked')) {
            var option = new Option(idProcesso, idProcesso);
            $(option).attr('selected', 'selected');
            $("#procesos_selecionados").append(option);
        } else {
            $("#procesos_selecionados").find("option[value=" + idProcesso + "]").remove();
        }
        habilitarBotaoBaixarProcessos();
    });

    function habilitarBotaoBaixarProcessos() {
        var possuiProcessoSelecionado = false;
        $.each($("option:selected", $("#procesos_selecionados")), function (index, valor) {
            possuiProcessoSelecionado = true;
            return false;
        });
        console.log(possuiProcessoSelecionado);
        if (possuiProcessoSelecionado) {
            $("#exportar_processos").removeClass('displayNone');
        } else {
            $("#exportar_processos").addClass('displayNone');
        }
    }

    $('body').on('click', '#exportar_processos', function () {
        var complUrl = "";

        $('#procesos_selecionados  > option:selected').each(function () {
            if (complUrl == "") {
                complUrl += "procesos_selecionados[]=" + $(this).val();
            } else {
                complUrl += "&procesos_selecionados[]=" + $(this).val();
            }
        });

        var url = $(this).attr("rel") + '?' + complUrl;
        window.location = url;
    });
    var submeter = false;
    $("#formBody").submit(function (event) {
        if($('#tipologiaOrig').val() == TIPOLOGIAS.comunicacao_de_acidente_de_trabalho){
            $('#medicina_trabalho, #seguranca_trabalho').find('input, textarea, select')
                .attr('disabled', false)
                .css('background-color', '#e2e2e4')
                .attr('readonly', true);
        }
        var tipologia_licenca_acompanhamento_familiar = parseInt($('#tipologia_licenca_acompanhamento_familiar').val());
        if ((!submeter) && ($('#hidtipologia').val() == tipologia_licenca_acompanhamento_familiar)) {
            event.preventDefault();
            exibirAlertaTipologiaFamilia();
        }
    });

    function preFinalizarAtendimento() {
        $("#formBody").append($(".finalizarAtendimento"));
        $('#formBody').submit();
    }

    function cancelarFinalizarAtendimento() {
        $("#formBody").find(".finalizarAtendimento").remove();
    }

    $('body').on('click', '#btFinalizarAtendimento', function (e) {
		e.preventDefault();
		validarRiscoVidaInsalubridade('btFinalizarAtendimento');
			
    });

	function validarRiscoVidaInsalubridade(acao){
		 var validaOk = true;
		 
		 if ($('#AgendamentoTipologiaId').val() == TIPOLOGIAS.risco_vida_insalubridade) {
			 
			 if($('#vinculo_risco_vida_insalubridade').val() == $("#ctd_vinculo_risco_vida_insalubridade").val()){
					
				if($('#AtendimentoNumeroNr').val() == ''){
					generateGrow('É necessário informar Número da NR', 'danger');
					validaOk = false;
				}
				if($('#AtendimentoNumeroAnexo').val() == ''){
					generateGrow('É necessário informar Número do Anexo', 'danger');
					validaOk = false;
				}
				if($('#AtendimentoLetra').val() == ''){
					generateGrow('É necessário informar Letra', 'danger');
					validaOk = false;
				}
				if($('#AtendimentoNaturezaAgente').val() == ''){
					generateGrow('É necessário informar Natureza do Agente', 'danger');
					validaOk = false;
				}
			}
		}
		
		if(validaOk){
			if(acao == 'salvarContinuar'){
				cancelarFinalizarAtendimento();
				$('#formBody').submit();
			}else if(acao == 'btFinalizarAtendimento'){
				preFinalizarAtendimento();
			}else if (acao == 'btEmitirLaudo'){
				dialogEmitirLaudo();
			}
		}
		
		
	}
	
 

    function exibirAlertaTipologiaFamilia() {
        var name = $('#acompanhadoNomeSemAbreviacao').val();
        var dataNascimento = $('#AgendamentoDataNascimentoAcompanhado').val();
        var url = $('#btSave').data('url') + "validarAcompanhado";

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
                    $("#dialog-alerta-acompanhado_licenca_familiar").dialog({
                        resizable: false,
                        height: 190,
                        dialogClass: "no-close",
                        width: 490,
                        modal: true,
                        buttons: {
                            "Cancelar": {
                                text: 'Cancelar',
                                'class': 'btn fa estiloBotao btn-info float-right',
                                click: function () {
                                    $('#dialog-alerta-acompanhado_licenca_familiar').dialog("close");
                                    cancelarFinalizarAtendimento();
                                    $("#hidEmitirLaudo").val(false);
                                }
                            },
                            "Ok": {
                                text: 'Ok',
                                'class': 'btn fa estiloBotao btn-info float-right',
                                click: function () {
                                    submeter = true;
                                    $('#formBody').submit();
                                }
                            }
                        }
                    });

                } else {
                    submeter = true;
                    $('#formBody').submit();
                }
            }
        });
    }
});
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