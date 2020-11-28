$(document).ready(function () {

    RELATORIO_TIPO = {};
    $('input.hid_relatorio_tipo').each(function () {
        RELATORIO_TIPO[$(this).attr('id')] = parseInt($(this).val());
    });

    RELATORIO_AGRUPAMENTO = {};
    $('input.hid_relatorio_agrupamento').each(function () {
        RELATORIO_AGRUPAMENTO[$(this).attr('id')] = parseInt($(this).val());
    });


    $('#tipo_relatorio').bind('change', function () {
        if (parseInt($(this).val()) === RELATORIO_TIPO.relatorio_dias_de_licenca) {
            $('#div-tipologia_id').addClass('displayNone');
            $('#div-tipologia_id select').val('');
        } else {
            $('#div-tipologia_id').removeClass('displayNone');
        }


        if (parseInt($(this).val()) === RELATORIO_TIPO.relatorio_agrupados) {
            $('#div-tipo_agrupamento').removeClass('displayNone');
        } else {
            $('#div-tipo_agrupamento select').val('');
            $('#div-tipo_agrupamento').addClass('displayNone');
        }
    });

    $('#formularioRelatorio').submit(function (e) {
        var isOk = true;
        e.preventDefault();

        if ($('#tipo_relatorio').val() == '') {
            alert('É preciso escolher o tipo de relatório');
            isOk = false;
        }

        if ($('#tipo_agrupamento').is(':visible') && $('#tipo_agrupamento').val() == "") {
            alert('É preciso escolher o tipo de agrupamento');
            isOk = false;
        }
        if (isOk) {

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
        }
    });
    function pdfRelatorio() {
        if ($('#allData').length > 0) {
            html2canvas($('#allData')[0], {
                onrendered: function (canvas) {
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    var w = $('#allData').width();
                    var h = $('#allData').height();
                    var pos = ( w > h) ? 'l' : 'p';
                    var pdf = new jsPDF(pos, 'pt', [w - w * 0.2, h - h * 0.2]);
                    pdf.addImage(imgData, 'JPEG', 5, 5);
                    pdf.save("relatorio.pdf");
                },
                background: '#fff'
            });
        } else {
            alert('Selecione um relatório antes');
        }
    }

    //$('#btn-imprimir').click(pdfRelatorio);

    $("#btn-imprimir").on('click', function(e){
        var isOk = true;
        e.preventDefault();

        if($.trim($("#RelatorioDataInicial").val()) == ""){
            alert('É preciso escolher uma data inicial');
            isOk = false;
        }
        if($.trim($("#RelatorioDataFinal").val()) == ""){
            alert('É preciso escolher uma data final');
            isOk = false;
        }

        if ($('#tipo_relatorio').val() == '') {
            alert('É preciso escolher o tipo de relatório');
            isOk = false;
        }

        if ($('#tipo_agrupamento').is(':visible') && $('#tipo_agrupamento').val() == "") {
            alert('É preciso escolher o tipo de agrupamento');
            isOk = false;
        }
        if (isOk) {
            var urlPdf = "";
            var url =  $("#formularioRelatorio").attr('action')+ '/impressao?';
            url +=  $("#formularioRelatorio").serialize();
            if(document.location.protocol == ""){
                urlPdf = document.location.protocol + "//" + document.location.hostname + url;
            }else{
                urlPdf = document.location.protocol + "//" + document.location.hostname + ":" + document.location.port + url;
            }
            window.location.replace(urlPdf);
        }

    });
});