$(document).ready(function () {

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

    $('body').on('click', '.selecionarTodos', function () {

        if ($(this).prop('checked')) {
            $.each($(".selecionarProcesso:not(:checked)"), function () {
                var idProcesso = $(this).data('id');
                var option = new Option(idProcesso, idProcesso);
                $(this).prop('checked', true);
                $(option).attr('selected', 'selected');
                $("#procesos_selecionados").append(option);
            });
            habilitarBotaoBaixarProcessos();
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
            if(!possuiProcessosSelecionados){
                $("#exportar_processos").addClass('displayNone');
            }
        }
    });

    function habilitarBotaoBaixarProcessos() {
        var possuiProcessoSelecionado = false;
        $.each($("option:selected", $("#procesos_selecionados")), function (index, valor) {
            possuiProcessoSelecionado = true;
            return false;
        });
        if (possuiProcessoSelecionado) {
            $("#exportar_processos").removeClass('displayNone');
        } else {
            $("#exportar_processos").addClass('displayNone');
        }
    }

    $('body').on('click', '#exportar_processos', function () {
        var $form = $('#formularioConsulta'),
                url = $(this).attr("rel") + '?' + $($form).serialize();
        window.location = url;
    });

    $(document).on({
        ajaxStop: function () {
            $("#procesos_selecionados option:selected").each(function () {
                $(".processo_" + $(this).text()).attr('checked', 'checked');
                habilitarBotaoBaixarProcessos();
            });
            
            var possuiProcessoDesmarcado = false;
            $.each($(".selecionarProcesso:not(:checked)"), function () {
                possuiProcessoDesmarcado = true;
                return false;
            });
            if(!possuiProcessoDesmarcado){
                $(".selecionarTodos").prop('checked', true);
            }
       }
    });

    $('body').on('click', '.detalharAtendimento', function (){
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



    $('body').on('click', '.detalharPublicacao', function (){
        var url = $(this).data('url');
        window.location = url;
    });
});