<div class="row float-right btn-edit">
    <div id="loading-consultar" class="displayNone" style="
        float:left;
        background: rgba( 255, 255, 255, .8 ) url('img/l6BEEsW.gif') 50% 50% no-repeat;
        width: 30px;
        height: 30px;
        margin-right: 5px" ></div>
    <?php

    echo $this->Form->button ( ' Imprimir', array (
        'class' => 'btn fa fa-print estiloBotao btn-primary',
        'id'=> 'btn-imprimir','evalScripts' => true,
        'type' =>'button',
        'data-acao' => 'impressao'
    ) );

    echo $this->Form->button(__('bt_consultar'), array(
        'class' => 'btn fa fa-search estiloBotao btn-info',
        'update' => '#main-content', 'evalScripts' => true
    ));

    $acaoConsulta = "index";
    if(isset($acaoBotaoLimpar)):
        $acaoConsulta = $acaoBotaoLimpar;
    endif;

    $urlConsulta = Router::url(array('controller' => "$controller", 'action' => $acaoConsulta));
//    pr($urlConsulta);die;
    echo $this->Form->button(__('bt_limpar'), array(
        'class' => 'btn fa fa-eraser estiloBotao btn-danger',
        'type' => 'button',
        'onclick' => "location.href = '$urlConsulta'"
    ));

    echo $this->Form->input('limitConsulta', array('type' => 'hidden', 'id' => 'limitConsulta'));
    ?>
</div>
