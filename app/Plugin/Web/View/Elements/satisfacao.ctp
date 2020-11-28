<?php
/*
  * http://rateyo.fundoocode.ninja
  */
?>

<div class="modal fade" id="satisfacaoModal" tabindex="-1" role="dialog" aria-labelledby="procurarServidorModalTitle"
     aria-hidden="true" style="z-index: 8999; background: none" >
    <div class="modal-dialog" role="document" style="width: 800px">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h5 class="modal-title" id="procurarServidorModalTitle" style="float:left">Avalie este agendamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body clearfix" style="padding-bottom: 0">
                <div class="form-group row">
                    <label class="col-md-4" for="avalie-rating">Sua avaliação para este produto *</label>
                    <div class="col-md-8" id="rateYo"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4" for="busca-matricula">Você recomenda este produto?</label>
                    <div class="col-md-8">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value=""> Sim &nbsp;
                            </label>
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value=""> Não
                            </label>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="titulo-avaliacao">Título da avaliação *</label>
                    <div class="col-md-9">
                        <input name="matricula" class="form-control" id="titulo-avaliacao" type="text"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3" for="texto-avaliacao">Escreva sua avaliação ao lado *</label>
                    <div class="col-md-9">
                        <textarea name="matricula" class="col-md-8 form-control" id="texto-avaliacao" type="text"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-limpar" class="btn btn-danger " style="box-shadow: none !important;">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;
                    Limpar
                </button>
                <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" id="btn-confirmar" class="btn btn-primary">Confirmar</button>
            </div>

        </div>
    </div>
</div>

