/**
 * Uso:
 *
 * *type* = [+](int|float[1-9+])
 *
 * <input type="text" class="typeNumber typeNumber_*type*" />
 */

jQuery.fn.classList = function() {return this[0].className.split(/\s+/);};

jQuery(function ($) {

    function isNumberFormat(value, event, format) {
        //format: [+](int|float)[1->9+]
        //numpad: 96 - 105 => 0 - 9,
        //numpad minus: 109
        //key: 48 - 57 => 0 - 9
        //arrows: 37 - 40 :
        //backspace, tab, delete: 8, 9, 46
        //comma, minus: 188, 189
        var decimais = '';
        var onlyPositive = false;
        if(format.indexOf('+')>= 0){
            onlyPositive = true;
        }

        var isInt = false;
        if(format.indexOf('int') >= 0){
            isInt = true;
        }else{
            decimais = Math.abs(format.replace(/[^0-9]/gi, ''));
        }

        var key = event.which;
        var isOk = false;
        //numbers, sem o shift pressionado
        if(( key >= 48 && key <= 57 && !event.shiftKey ) || (key >= 96 && key <= 105) ){
            if(decimais > 0){
                var commaPos = value.indexOf(',');
                if(commaPos == -1){
                    isOk = true;
                }else if(event.target.selectionStart <= commaPos){
                    isOk = true;
                }else if(event.target.selectionStart > commaPos){
                    if(value.length - commaPos < decimais+1){
                        isOk = true;
                    }
                }
            }else{
                isOk = true;
            }
        }else if(key == 109 || key == 189){ //minus
            //condição de positivo, só insere menos se n tiver sinal e se for o primeiro carater
            if(!onlyPositive && value.indexOf('-') == -1 && event.target.selectionStart == 0){
                isOk = true;
            }
            //condição int, só insere vírgula caso n exista vírgula no número
        }else if(!isInt && key == 188 && value.indexOf(',') == -1){
            if(decimais > 0){
                if(value.length - event.target.selectionStart <= decimais+1){
                    isOk = true;
                }
            }else{
                isOk = true;
            }
            //jQuery inArray
        }else if ($.inArray(key, [37,38,39,40, 8,9, 46]) >= 0 ){
            isOk = true;
        }
        return isOk;
    }

    $('.typeNumber').each(function(){

        var input = this;
        var arr = $(this).classList();
        for (var i in arr){
            var className=arr[i];
            if(className.indexOf('typeNumber_') >=0){
                var type = className.match(/typeNumber_(.+)/)[1];
                $(input).bind("keydown", function(event){ return isNumberFormat(this.value, event, type); });
            }
        }

    });


});