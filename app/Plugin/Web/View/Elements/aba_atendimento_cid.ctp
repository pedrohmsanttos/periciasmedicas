<?php  $urlGetCid = Router::url(['controller'=>'Atendimento','action'=>'getCidId']); ?>

<div class="row">
    <?php if(isset($atendimentoCids) && !empty($atendimentoCids)):  ?>
        <?php $aux = 1; ?>
        <?php foreach ($atendimentoCids as $atendCid): ?>
            <div class="row itemCid" style="margin-left: 1%" id="cid<?=$aux?>">
                <div class="col-md-2" >
                    <div class="form-group">
                        <label for="inputCodigoCid<?=$aux?>">Código</label>
                        <input id="inputCodigoCid<?=$aux?>" class="form-control ui-autocomplete-input" value="<?= $atendCid['codigoCid'] ?>" maxlength="255" data-url="<?= $urlGetCid ?>" autocomplete="off" type="text" onKeyPress="carregaCid(inputCodigoCid<?=$aux?>);" data-order="<?=$aux?>" <?= $formDisabledCID ?>>
                    </div>

                    <div class="form-group">
                        <input id="inputCidId<?=$aux?>" class="form-control ui-autocomplete-input" name="data[Cid][Cid][]" value="<?= $atendCid['idCid'] ?>" maxlength="255" autocomplete="off" type="hidden">
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                            <label for="inputDescricaoCid<?=$aux?>">Descrição</label>
                            <input id="inputDescricaoCid<?=$aux?>" class="form-control ui-autocomplete-input" name="data[Cid][Descricao][]" value="<?= $atendCid['descricaoCid'] ?>" maxlength="255" autocomplete="off" type="text" disabled>
                        </div>
                </div>

                <div class="col-md-5" style="float: right; margin-top: 1.5%;">
                   <button class="btn fa fa-plus estiloBotao btn-success" value="true" id="novoCid<?=$aux?>" type="button" data-order="<?=$aux?>" onClick="replicaCid(cid<?=$aux?>);" <?= $formDisabledCID ?>></button>
                   <?php if($aux > 1): ?>
                    <button class="btn fa fa-trash-o estiloBotao btn-danger" value="true" id="removerCid<?=$aux?>" name="removerCid" onClick="removeCid(cid<?=$aux?>)"  type="button" <?= $formDisabledCID ?>> </button>
                   <?php  endif; ?>
                </div>
            </div> <!-- END CID 1 -->
            <?php $aux++; ?>
        <?php  endforeach; ?>
            <div id="endCid" data-total="<?=$aux?>"></div>
    <?php else: ?>
            
        <div class="row itemCid" style="margin-left: 1%" id="cid1">
            <div class="col-md-2" >
                <div class="form-group">
                    <label for="inputCodigoCid1">Código</label>
                    <input id="inputCodigoCid1" class="form-control ui-autocomplete-input" maxlength="255" data-url="<?= $urlGetCid ?>" autocomplete="off" type="text" onKeyPress="carregaCid(inputCodigoCid1);" data-order="1" <?= $formDisabledCID ?>>
                </div>

                <div class="form-group">
                    <input id="inputCidId1" class="form-control ui-autocomplete-input" name="data[Cid][Cid][]" maxlength="255" autocomplete="off" type="hidden">
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                        <label for="inputDescricaoCid1">Descrição</label>
                        <input id="inputDescricaoCid1" class="form-control ui-autocomplete-input" maxlength="255" autocomplete="off" type="text" disabled>
                    </div>
            </div>

            <div class="col-md-5" style="float: right; margin-top: 1.5%;">
               <button class="btn fa fa-plus estiloBotao btn-success" value="true" id="novoCid1" type="button" data-order="1" onClick="replicaCid(cid1);" <?= $formDisabledCID ?> > </button>
            </div>
        </div>
            <div id="endCid" data-total="1"></div>

    <?php endif; ?>
</div>   

<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('observacoes_cid', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            //'value' => $arrAtendimento['Atendimento']['observacoes_cid'],
            'onkeyup' => "limitarTamanho(this,1000);",
            'onblur' => "limitarTamanho(this,1000);",
            'label' => __('atendimento_laudo_label_cid_observacoes')));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input('procedimento_exames', array('div' => array('class' => 'form-group'),
            'class' => 'form-control',
            'disabled' => $formDisabled,
            'type' => 'textarea',
            //'value' => $arrAtendimento['Atendimento']['procedimento_exames'],
            'onkeyup' => "limitarTamanho(this,1000);",
            'onblur' => "limitarTamanho(this,1000);",
            'label' => __('atendimento_laudo_label_cid_procedimento_exames')));
        ?>
    </div>
</div>


<script type="text/javascript">
   function carregaCid(id) {
        var url = id.getAttribute('data-url');
        var ordem = id.getAttribute('data-order');
        var inputHiddenCidId = "#inputCidId" + $.trim(ordem);
        var inputDescricaoCid = "#inputDescricaoCid" + $.trim(ordem);
        
        $(id).autocomplete({
            source: url,
            response: function (event, ui) {
                $(inputHiddenCidId).val('');
                $(inputDescricaoCid).val('');
            },
            select: function (a, b) {
                $(inputHiddenCidId).val(b.item.id);
                $(inputDescricaoCid).val(b.item.descricao);
            }
        });
    }

    function replicaCid(cid){
         

        var cidItem = parseInt($( "#endCid" ).data('total'));
        var idInputCodigoOld = "#inputCodigoCid" + cidItem;
        var idInputDescricaoOld = "inputDescricaoCid" + cidItem;
        var idInputCidOld = "inputCidId" + cidItem;

        var elementCodigo = "#cid " + idInputCodigoOld;
        var elementDescricao = "#cid " + idInputDescricaoOld;

        // console.log(element);

        // if($.trim($(elementCodigo).val()) == "" && $.trim($(elementDescricao).val()) == ""){
        //     generateGrow('Informe algum CID para poder adicionar um novo', 'danger');
        // }else{

            var novoItem = parseInt($( "#endCid" ).data('total')) + 1;
          
            var idInputCodigo = "inputCodigoCid" + novoItem;
            var idInputDescricao = "inputDescricaoCid" + novoItem;
            var idInputCid = "inputCidId" + novoItem;
            var idNovoCid = "novoCid" + novoItem
            var url = "<?= $urlGetCid ?>";
            var idCid = 'cid' + novoItem;

            // "+idInputCodigo+"," + idInputDescricao +"

            var html = "<div class='row itemCid' style='margin-left: 1%' id='"+idCid+"'><div class='col-md-2'><div class='form-group'><label for='" + idInputCodigo + "'>Código</label><input id='" +idInputCodigo +"' class='form-control ui-autocomplete-input' maxlength='255' data-url='"+ url +"' autocomplete='off' type='text' onKeyPress='carregaCid("+idInputCodigo+");' data-order='"+ novoItem+"'></div><div class='form-group'><input id='"+idInputCid+"' class='form-control ui-autocomplete-input' name='data[Cid][Cid][]' maxlength='255' autocomplete='off' type='hidden'></div></div><div class='col-md-5'><div class='form-group'><label for='"+idInputDescricao+"'>Descrição</label><input id='"+idInputDescricao+"' class='form-control ui-autocomplete-input' maxlength='255' autocomplete='off' type='text' disabled></div></div><div class='col-md-5' style='float: right; margin-top: 1.5%;'><button class='btn fa fa-plus estiloBotao btn-success' value='true' id='"+idNovoCid+"' type='button' data-order='"+novoItem+"' onClick='replicaCid();'></button><button class='btn fa fa-trash-o estiloBotao btn-danger' value='true' id='removerCid"+novoItem+"' type='button' onClick='removeCid("+idCid+")'></button></div></div><div id='endCid' data-total='"+novoItem+"'></div>";

            // var classe = ".endCid" + $.trim(ordem);
            $("#endCid").after(html);
            $("#endCid").remove();

        // }

    }

    function removeCid(idCid){
       $(idCid).remove();
    }
</script>
