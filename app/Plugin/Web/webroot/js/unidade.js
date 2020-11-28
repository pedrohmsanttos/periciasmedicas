jQuery(function ($) {

    $("#inputNomeResponsavel").autocomplete({
        source: $("#inputNomeResponsavel").data("url"),
        minLength: 4,
        response: function (event, ui) {
            $('#hiddenResponsavelId').val('');
        },
        open: function (eventi, ui) {
            $('#hiddenResponsavelId').val('');
            $('#inputTelefoneResponsavel').val('');
        },
        select: function (a, b) {
            $('#inputNomeResponsavel').val(b.item.nome);
            var possuiTelefone = $("#inputNomeResponsavel").data('telefone');

            if (possuiTelefone == true) {
                $('#inputTelefoneResponsavel').val(b.item.telefone);
                $('#hiddenResponsavelId').val(b.item.id);
                aplicarMascaraTelefone($('.telefone'));
            }
        }
    });
});