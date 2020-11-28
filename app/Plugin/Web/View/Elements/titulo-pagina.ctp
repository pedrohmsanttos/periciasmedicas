<?php $contollerConsulta = $this->request->controller ?>

<header class="panel-heading"> 
    <div class="row">
        <div class="col-md-<?echo ($contollerConsulta == "AgendaSistema") ? 8 : 10 ?>">
            <div class="panel-title"><?= $titulo ?></div>
        </div>
        <div class = "col-md-2">
            <?php
            if (isset($exibirBotaoNovo)):
                if(!isset($feminino)){
                    $feminino = false;
                }
                ?>
                <?php
                echo $this->element('botao_novo', array('feminino' => $feminino));
                ?>
                <?php
            endif;
            if (isset($acao) && $acao == Configure::read('ACAO_VISUALIZAR')):
                echo $this->element('botoes-default-grid', array('id' => $this->request->data[$controller]['id'], 'model' => $controller));
            endif;
            ?>
        </div>
        <?php if(($contollerConsulta == "AgendaSistema")): ?>
            <div class = "col-md-2">
            <?php
            $urlImprimir = Router::url(array('controller' => "AgendaPerito", 'action' => 'index'));
                echo $this->Form->button("  Imprimir Agenda", array(
                    'class' => 'btn fa fa-print estiloBotao btn-success float-right',
                    'type' => 'button',
                    'onclick' => "jQuery('body').addClass('loading');location.href = '$urlImprimir'"
                )); 
            ?>

            </div>
        <?php endif; ?>
    </div>
</header>