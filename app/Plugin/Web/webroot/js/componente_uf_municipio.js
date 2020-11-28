$(document).ready(function () {
    $('body').on('change', '.comboEstado', function () {
        var valorSelecionado = $("option:selected", this).attr('value');
        var comboMunicipio = $(this).attr('municipio_id');
        var url = $(this).attr('data-url');
        if (valorSelecionado != "") {
            $.ajax({
                url: url,
                dataType: 'json',
                data: {estado_id: valorSelecionado},
                type: 'get',
                success: function (response) {
                    $('#' + comboMunicipio).find('option').remove();
                    $('#' + comboMunicipio).append(new Option('Selecione', ''));
                    $.each(response, function (index, valor) {
                        $('#' + comboMunicipio).append(new Option(valor, index));
                    });
                }
            });
        } else {
            $('#' + comboMunicipio).find('option').remove();
            $('#' + comboMunicipio).append(new Option('Selecione', ''));
        }
    });
});