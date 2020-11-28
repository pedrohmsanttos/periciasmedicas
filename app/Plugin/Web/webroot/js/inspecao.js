/**
 * Created by thyago.machado on 15/06/2016.
 */

$(document).ready(function(){
    $('#m_poluicao').bind("keydown", function(event){ return isNumberFormat(this.value, event, $(this).attr('data-type')) });
    $('#m_ruido').bind("keydown", function(event){ return isNumberFormat(this.value, event, $(this).attr('data-type')) });
    $('#m_temperatura').bind("keydown", function(event){ return isNumberFormat(this.value, event, $(this).attr('data-type')) });
    $('#m_luminosidade').bind("keydown", function(event){ return isNumberFormat(this.value, event, $(this).attr('data-type')) });
});
