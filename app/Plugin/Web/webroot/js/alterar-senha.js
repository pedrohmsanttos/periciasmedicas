
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


$('#edit-submit').click(function () {
    var validaSenha = $("#edit-pass").val();
    var confirmaSenha = $("#edit-confirm-pass").val();

    if(validaSenha != ""){

        if(confirmaSenha == ""){
            generateGrow('Campo de confirmação de senha é obrigatório.', 'danger');
            return false;
        }

        if(confirmaSenha != validaSenha){
            generateGrow('Campos senha e cofirmação não conferem.', 'danger');
            return false;
        }

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
    
    }else{
        generateGrow('Campos senha e confirmação são obrigatórios.', 'danger');
        return false;
    }

});

if ($.fn.datepicker) {
    $('.input-data').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        buttonImageOnly: true,
        showButtonPanel: true,
        buttonImage: "../img/datepicker.gif",
        showOn: 'both'
    });
}

$("#edit-rg").bind("keyup blur focus", function (e) {
    e.preventDefault();
    var expre = /[^\d]/g;
    $(this).val($(this).val().replace(expre, ''));
});