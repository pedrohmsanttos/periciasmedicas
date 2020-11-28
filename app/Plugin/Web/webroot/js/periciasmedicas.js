jQuery(function ($) {
    /* SCRIPTS REFERENTE AS MASCARAS. */
    if ($.fn.mask) {
        aplicarMascaraCpf();
        $(".cnpj").mask("99.999.999/9999-99");
        $(".cep").mask("99999-999");
        $(".hour").mask("99:99");
        $(".altura").mask("9,99");

		var teste = {
			money: function() {
				var el = this
				,exec = function(v) {
					v = v.replace(/\D/g,"");
					v = new String(Number(v));
					var len = v.length;
					if (1== len)
						v = v.replace(/(\d)/,"0,0$1");
					else if (2 == len)
						v = v.replace(/(\d)/,"0,$1");
					else if (len > 2) {
						v = v.replace(/(\d{2})$/,',$1');
					}
					return v;
				};

				setTimeout(function(){
					el.value = exec(el.value);
				},1);
			}

		}


        $(".temperatura").mask("99,9");
        $('.telefone').focusout(function () {
            aplicarMascaraTelefone(this);
        }).trigger('focusout');
    }
    /* FIM SCRIPTS REFERENTE AS MASCARAS. */

    /* SCRIPTS REFERENTE A DATEPICKER. */
    if ($.fn.datepicker) {
        $('.inputData').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            buttonImageOnly: true,
            showButtonPanel: true,
            buttonImage: "../img/datepicker.gif",
            showOn: 'both'
        });
    }
    /* FIM SCRIPTS REFERENTE A DATEPICKER. */

    $(".soNumero").on("keyup", function (event) {
        soNumero($(this));
    });
    $(".soNumero").on("change", function (event) {
        soNumero($(this));
    });
    $(".soNumero").on("focus", function (event) {
        soNumero($(this));
    });
    $(".soNumero").on("hover", function () {
        soNumero($(this));
    });
    $(".soNumero").on("mouseout", function () {
        soNumero($(this));
    });

    function soNumero(element) {
        $(element).val($(element).val().replace(/[^0-9]/gi, ''));
    }

});

function aplicarMascaraCpf() {
    $(".cpf").mask("999.999.999-99");
}

function aplicarMascaraPeso(campo) {
    var peso, element;
    element = $(campo);
    element.unmask();
    peso = element.val().replace(/\D/g, '');
    if (peso.length > 4) {
        element.mask("999,9");
    } else {
        element.mask("99,9?9");
    }
}

function aplicarMascaraTelefone(campo) {
    var phone, element;
    element = $(campo);
    console.log('campo',campo);
    console.log('element',element);
    element.unmask();
    phone = element.val().replace(/\D/g, '');
    console.log('phone',phone);
    console.log('phonelength',phone.length);
    if (phone.length > 10) {
        element.mask("(99) 99999-999?9");
    }else if (phone.length ==  8 || phone.length ==  9) {
        element.mask("9999-999?9");
    }else {
        element.mask("(99)9999-9999?9");
    }
}


var JEffects = {
    handleEnter: function (field, event) {
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which
                : event.charCode;
        if (keyCode == 13) {
            var i;
            for (i = 0; i < field.form.elements.length; i++)
                if (field == field.form.elements[i])
                    break;
            i = (i + 1) % field.form.elements.length;
            field.form.elements[i].focus();
            return false;
        } else
            return true;
    }
}

JEffects.masks = {
    hora: function (vHora) {
        hora = vHora.value;
        nHora = hora.replace(/:/, '');
        nHora = nHora.replace(/[^0-9]/, '');
        var h = nHora.substring(0, 2);
        var m = nHora.substring(2, 4);
        //var s = nHora.substring(4, 6);
        if (nHora.length == 1)
            vHora.value = h;
        //if (nHora.length == 2)
        //	vHora.value = h + ":";
        if (nHora.length >= 3)
            vHora.value = h + ":" + m;
    },
    data: function (obj) {
        var pass = obj.value;
        var expr = /[0123456789]/;
        for (i = 0; i < pass.length; i++) {
            // charAt -> retorna o caractere posicionado no ÃƒÂ­ndice especificado
            var lchar = obj.value.charAt(i);
            var nchar = obj.value.charAt(i + 1);
            if (i == 0) {
                // search -> retorna um valor inteiro, indicando a posiÃƒÂ§ao do
                // inicio da primeira
                // ocorrencia de expReg dentro de instStr. Se nenhuma ocorrencia
                // for encontrada o mÃƒÂ©todo retornara -1
                // instStr.search(expReg);
                if ((lchar.search(expr) != 0) || (lchar > 3)) {
                    obj.value = "";
                }
            } else if (i == 1) {
                if (lchar.search(expr) != 0) {
                    // substring(indice1,indice2)
                    // indice1, indice2 -> serÃƒÂ¡ usado para delimitar a string
                    var tst1 = obj.value.substring(0, (i));
                    obj.value = tst1;
                    continue;
                }
                if ((nchar != '/') && (nchar != '')) {
                    var tst1 = obj.value.substring(0, (i) + 1);
                    if (nchar.search(expr) != 0)
                        var tst2 = obj.value.substring(i + 2, pass.length);
                    else
                        var tst2 = obj.value.substring(i + 1, pass.length);
                    obj.value = tst1 + '/' + tst2;
                }
            } else if (i == 4) {
                if (lchar.search(expr) != 0) {
                    var tst1 = obj.value.substring(0, (i));
                    obj.value = tst1;
                    continue;
                }
                if ((nchar != '/') && (nchar != '')) {
                    var tst1 = obj.value.substring(0, (i) + 1);
                    if (nchar.search(expr) != 0)
                        var tst2 = obj.value.substring(i + 2, pass.length);
                    else
                        var tst2 = obj.value.substring(i + 1, pass.length);
                    obj.value = tst1 + '/' + tst2;
                }
            }
            if (i >= 6) {
                if (lchar.search(expr) != 0) {
                    var tst1 = obj.value.substring(0, (i));
                    obj.value = tst1;
                }
            }
        }
        if (pass.length > 10)
            obj.value = obj.value.substring(0, 10);
        return true;
    }
}
function formatoData(campo) {

    var digits = "0123456789/";
    var campo_temp;

    for (var i = 0; i < campo.value.length; i++) {
        campo_temp = campo.value.substring(i, i + 1);

        if (digits.indexOf(campo_temp) == -1) {
            campo.value = "";
        }
    }

    return campo;
}

function naoBissexto(ano) {
    var bissexto = false;
    if (ano % 4 == 0 || ano % 400 == 0) {
        bissexto = true;
    }
    return bissexto;
}

function VerificaData(campo, cData) {
    var data = cData;
    var tam = data.length;
    if (tam != 10) {
        campo.value = "";
        return false;
    }
    var dia = data.substr(0, 2);
    var mes = data.substr(3, 2);
    var ano = data.substr(6, 4);
    if (ano < 1600) {
        campo.value = "";
        return false;
    }

    if (mes > 12 || mes < 1) {
        campo.value = "";
        return false;
    }

    switch (mes) {
        case '01':
            if (dia <= 31)
                return (true);
            break;
        case '02':
            if (dia <= 30) {
                if (naoBissexto(ano)) {
                    if (dia <= 29)
                        return (true);
                }
                else {
                    if (dia <= 28)
                        return (true);
                }
            }
            break;
        case '03':
            if (dia <= 31)
                return (true);
            break;
        case '04':
            if (dia <= 30)
                return (true);
            break;
        case '05':
            if (dia <= 31)
                return (true);
            break;
        case '06':
            if (dia <= 30)
                return (true);
            break;
        case '07':
            if (dia <= 31)
                return (true);
            break;
        case '08':
            if (dia <= 31)
                return (true);
            break;
        case '09':
            if (dia <= 30)
                return (true);
            break;
        case '10':
            if (dia <= 31)
                return (true);
            break;
        case '11':
            if (dia <= 30)
                return (true);
            break;
        case '12':
            if (dia <= 31)
                return (true);
            break;
    }
    campo.value = "";
    return false;
}

function limitarTamanho(campo, limite) {

    if ((limite - campo.value.length) <= 0) {
        campo.value = campo.value.substr(0, limite);
    }
}